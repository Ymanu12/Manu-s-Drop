<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Contact;
use App\Models\Orders;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function index()
    {
        Gate::authorize('access-admin');

        $orders = Orders::with('orderItems')->orderBy('created_at', 'DESC')->take(10)->get();

        $dashboardDatas = DB::select(" 
            SELECT 
                sum(total) AS TotalAmount,
                sum(if(status='ordered', total, 0)) AS TotalOrderedAmount,
                sum(if(status='delivered', total, 0)) AS TotalDeliveredAmount,
                sum(if(status='canceled', total, 0)) AS TotalCanceledAmount,
                count(*) AS Total,
                sum(if(status='ordered', 1, 0)) AS TotalOrdered,
                sum(if(status='delivered', 1, 0)) AS TotalDelivered,
                sum(if(status='canceled', 1, 0)) AS TotalCanceled
            FROM orders
        ");

        $monthlyDatas = DB::select("SELECT M.id As MonthNo, M.name As MonthName,
                IFNULL(D.TotalAmount,0) As TotalAmount,
                IFNULL(D.TotalOrderedAmount,0) As TotalOrderedAmount,
                IFNULL(D.TotalDeliveredAmount,0) As TotalDeliveredAmount,
                IFNULL(D.TotalCanceledAmount,0) As TotalCanceledAmount 
            FROM month_names M
            LEFT JOIN (
                SELECT DATE_FORMAT(created_at, '%b') As MonthName,
                    MONTH(created_at) As MonthNo,
                    sum(total) As TotalAmount,
                    sum(if(status='ordered',total,0)) As TotalOrderedAmount,
                    sum(if(status='delivered',total,0)) As TotalDeliveredAmount,
                    sum(if(status='canceled',total,0)) As TotalCanceledAmount
                FROM Orders 
                WHERE YEAR(created_at)=YEAR(NOW()) 
                GROUP BY YEAR(created_at), MONTH(created_at), DATE_FORMAT(created_at, '%b')
                ORDER BY MONTH(created_at)
            ) D ON D.MonthNo=M.id
        ");

        $AmountSeries = collect($monthlyDatas)->pluck('TotalAmount')->map(fn ($value = 0) => (float) $value)->values()->all();
        $OrderedSeries = collect($monthlyDatas)->pluck('TotalOrderedAmount')->map(fn ($value = 0) => (float) $value)->values()->all();
        $DeliveredSeries = collect($monthlyDatas)->pluck('TotalDeliveredAmount')->map(fn ($value = 0) => (float) $value)->values()->all();
        $CanceledSeries = collect($monthlyDatas)->pluck('TotalCanceledAmount')->map(fn ($value = 0) => (float) $value)->values()->all();

        $TotalAmount = collect($monthlyDatas)->sum('TotalAmount');
        $TotalOrderedAmount = collect($monthlyDatas)->sum('TotalOrderedAmount');
        $TotalDeliveredAmount = collect($monthlyDatas)->sum('TotalDeliveredAmount');
        $TotalCanceledAmount = collect($monthlyDatas)->sum('TotalCanceledAmount');

        $dashboard = $dashboardDatas[0] ?? null;

        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();

        $todayOrdersCount = Orders::whereDate('created_at', $today)->count();
        $todayRevenue = (float) Orders::whereDate('created_at', $today)->sum('total');
        $todayDeliveredCount = Orders::where('status', 'delivered')->whereDate('updated_at', $today)->count();
        $pendingOrdersCount = Orders::where('status', 'ordered')->count();

        $weeklyRevenue = (float) Orders::whereBetween('created_at', [$weekStart, now()])->sum('total');
        $averageOrderValue = (float) Orders::avg('total');
        $newCustomersThisMonth = User::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $newMessagesCount = Contact::whereDate('created_at', '>=', now()->subDays(7))->count();

        $lowStockProducts = Product::query()
            ->select('id', 'name', 'quantity', 'stock_status', 'updated_at')
            ->where('quantity', '<=', 5)
            ->orderBy('quantity')
            ->take(5)
            ->get();

        $recentMessages = Contact::query()
            ->select('id', 'name', 'email', 'phone', 'comment', 'created_at')
            ->latest()
            ->take(5)
            ->get();

        $recentActivities = AdminAuditLog::query()
            ->with('user')
            ->latest()
            ->take(6)
            ->get();

        return view('admin.index', compact(
            'orders',
            'dashboard',
            'dashboardDatas',
            'AmountSeries',
            'OrderedSeries',
            'DeliveredSeries',
            'CanceledSeries',
            'TotalAmount',
            'TotalOrderedAmount',
            'TotalDeliveredAmount',
            'TotalCanceledAmount',
            'todayOrdersCount',
            'todayRevenue',
            'todayDeliveredCount',
            'pendingOrdersCount',
            'weeklyRevenue',
            'averageOrderValue',
            'newCustomersThisMonth',
            'newMessagesCount',
            'lowStockProducts',
            'recentMessages',
            'recentActivities'
        ));
    }

    public function search(Request $request)
    {
        Gate::authorize('access-admin');

        $validated = $request->validate([
            'query' => 'required|string|min:2|max:100',
        ]);

        $results = Product::query()
            ->select('id', 'name', 'slug', 'image', 'regular_price', 'sale_price', 'stock_status', 'quantity')
            ->where('name', 'LIKE', '%' . $validated['query'] . '%')
            ->limit(8)
            ->get();

        return response()->json($results);
    }
}


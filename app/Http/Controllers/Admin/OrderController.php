<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AuditsAdminActions;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Orders;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use AuditsAdminActions;

    public function orders()
    {
        $this->authorize('viewAny', Orders::class);

        $orders = Orders::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders', compact('orders'));
    }

    public function order_details($order_id)
    {
        $order = Orders::findOrFail($order_id);
        $this->authorize('view', $order);

        $orderItems = OrderItem::where('orders_id', $order_id)->orderBy('id')->paginate(12);
        $transaction = Transaction::where('orders_id', $order_id)->first();

        return view('admin.order-details', compact('order', 'orderItems', 'transaction'));
    }

    public function update_order_status(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'order_status' => 'required|in:ordered,delivered,canceled',
        ]);

        $order = Orders::findOrFail($validated['order_id']);
        $this->authorize('update', $order);
        $order->status = $validated['order_status'];
        $order->delivered_date = $validated['order_status'] === 'delivered' ? Carbon::now() : null;
        $order->canceled_date = $validated['order_status'] === 'canceled' ? Carbon::now() : null;
        $order->save();

        $transaction = Transaction::where('orders_id', $order->id)->first();

        if ($transaction) {
            $transaction->status = $validated['order_status'] === 'delivered' ? 'approved' : 'pending';
            $transaction->save();
        }

        $this->auditAdminAction('order.status_updated', Orders::class, $order->id, ['status' => $validated['order_status']]);

        return back()->with('status', 'Status changed successfully!');
    }
}

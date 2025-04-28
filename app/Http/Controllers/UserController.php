<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Orders;
use App\Models\OrderItem;
use App\Models\Transaction;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function orders()
    {
        $orders = Orders::where('user_id', Auth::user()->id)->orderBy('created_at','DESC')->paginate(10);
        return view('user.orders', compact('orders'));
    }

    public function order_details($order_id)
    {
        $order = Orders::where('user_id', Auth::user()->id)->where('id',$order_id)->first();
        if($order)
        {
            $orderItems = OrderItem::where('orders_id', $order_id)->orderBy('id')->paginate(12);
            $transaction = Transaction::where('orders_id', $order_id)->first();
            return view('user.order-details', compact('order','orderItems','transaction'));
        }
        else
        {
            return redirect()->route('login');
        }
    }

    public function order_cancel(Request $request)
    {
        // Trouver la commande
        $order = Orders::find($request->order_id);

        // Vérifier si l'ordre existe
        if (!$order) {
            return back()->with('error', 'Order not found!');
        }

        // Mettre à jour le statut
        $order->status = 'canceled';
        $order->canceled_date = Carbon::now();
        $order->save();

        // Retourner avec un message de succès
        return back()->with('status', 'Order has been cancelled successfully!');
    }


    public function address()
    {
        $addresses = Address::where('user_id', Auth::id())->get();
        return view('user.addresses', compact('addresses'));
    }

    public function address_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            // ajoute les autres validations ici
        ]);

        Address::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'locality' => $request->locality,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'landmark' => $request->landmark,
            'zip' => $request->zip,
            'type' => $request->type,
            'is_default' => $request->is_default ?? 0,
        ]);

        return redirect()->back()->with('success', 'Address added successfully!');
    }

    public function address_destroy($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();
        return redirect()->back()->with('success', 'Address deleted successfully!');
    }
}

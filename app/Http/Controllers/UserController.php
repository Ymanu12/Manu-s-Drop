<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\OrderItem;
use App\Models\Orders;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function orders()
    {
        $orders = Orders::where('user_id', Auth::id())->orderBy('created_at', 'DESC')->paginate(10);
        return view('user.orders', compact('orders'));
    }

    public function order_details($order_id)
    {
        $order = Orders::where('user_id', Auth::id())->where('id', $order_id)->first();

        if (!$order) {
            abort(404);
        }

        $orderItems = OrderItem::where('orders_id', $order_id)->orderBy('id')->paginate(12);
        $transaction = Transaction::where('orders_id', $order_id)->first();

        return view('user.order-details', compact('order', 'orderItems', 'transaction'));
    }

    public function order_cancel(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
        ]);

        $order = Orders::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            abort(404);
        }

        $order->status = 'canceled';
        $order->canceled_date = Carbon::now();
        $order->save();

        return back()->with('status', 'Order has been cancelled successfully!');
    }

    public function addresses_index()
    {
        $addresses = Address::where('user_id', Auth::id())->get();
        return view('user.addresses', compact('addresses'));
    }

    public function addresses_create()
    {
        return view('user.addresses-create');
    }

    public function addresses_store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'zip' => 'required|string|max:10',
            'locality' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:50',
            'is_default' => 'nullable|boolean',
        ]);

        if (!empty($validated['is_default'])) {
            Address::where('user_id', Auth::id())->update(['is_default' => 0]);
        }

        $validated['user_id'] = Auth::id();

        Address::create($validated);

        return redirect()->route('user.addresses')->with('success', 'Address added successfully.');
    }

    public function addresses_edit(Address $address)
    {
        $this->ensureAddressOwnership($address);

        return view('user.addresses-edit', compact('address'));
    }

    public function addresses_update(Request $request, Address $address)
    {
        $this->ensureAddressOwnership($address);

        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'zip' => 'required',
        ]);

        if ($request->boolean('is_default')) {
            Address::where('user_id', Auth::id())->update(['is_default' => 0]);
        }

        $address->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'locality' => $request->locality,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'landmark' => $request->landmark,
            'zip' => $request->zip,
            'type' => $request->type,
            'is_default' => $request->boolean('is_default') ? 1 : 0,
        ]);

        return redirect()->route('user.addresses')->with('success', 'Address updated successfully.');
    }

    public function addresses_destroy(Address $address)
    {
        $this->ensureAddressOwnership($address);

        $address->delete();
        return redirect()->route('user.addresses')->with('success', 'Address deleted successfully.');
    }

    public function addresses_set_default(Address $address)
    {
        $this->ensureAddressOwnership($address);

        Address::where('user_id', Auth::id())->update(['is_default' => 0]);
        $address->update(['is_default' => 1]);

        return redirect()->route('user.addresses')->with('success', 'Main address updated successfully.');
    }

    public function account_update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'old_password' => 'nullable',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->email = $request->email;

        if ($request->filled('old_password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'The old password is incorrect']);
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Account updated successfully!');
    }

    public function account_edit()
    {
        return view('user.account-details');
    }

    public function update_theme(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark',
        ]);

        $user = Auth::user();
        $user->theme = $validated['theme'];
        $user->save();

        return response()->json([
            'status' => 'ok',
            'theme' => $user->theme,
        ]);
    }

    protected function ensureAddressOwnership(Address $address): void
    {
        if ((int) $address->user_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
    }
}

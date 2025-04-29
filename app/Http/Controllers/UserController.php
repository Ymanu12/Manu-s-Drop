<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Orders;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\Address;
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

    public function addresses_index()
    {
        $addresses = Address::where('user_id', auth()->id())->get();
        return view('user.addresses', compact('addresses'));
    }

    public function addresses_create()
    {
        return view('user.addresses-create');
    }

    public function addresses_store(Request $request)
    {
        // Validation
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

        // Si l'adresse doit être par défaut, désactiver les autres adresses par défaut
        if (!empty($validated['is_default'])) {
            Address::where('user_id', Auth::id())->update(['is_default' => 0]);
        }

        // Ajouter l'user_id aux données validées
        $validated['user_id'] = Auth::id();

        // Créer l'adresse
        Address::create($validated);

        return redirect()->route('user.addresses')->with('success', 'Address added successfully.');
    }

    public function addresses_edit(Address $address)
    {
        // $this->authorize('update', $address); // Facultatif : pour la sécurité
        return view('user.addresses-edit', compact('address'));
    }

    public function addresses_update(Request $request, Address $address)
    {
        // $this->authorize('update', $address);

        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'zip' => 'required',
        ]);

        if ($request->is_default) {
            // Si l'utilisateur veut que cette adresse soit par défaut,
            // on remet toutes ses autres adresses à is_default = 0
            Address::where('user_id', auth()->id())->update(['is_default' => 0]);
        }
        
        // Ensuite on met à jour l'adresse choisie
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
            'is_default' => $request->is_default ? 1 : 0,
        ]);        

        return redirect()->route('user.addresses')->with('success', 'Address updated successfully.');
    }

    public function addresses_destroy(Address $address)
    {
        // $this->authorize('delete', $address);

        $address->delete();
        return redirect()->route('user.addresses')->with('success', 'Address deleted successfully.');
    }

    public function addresses_set_default(Address $address)
    {
        // Vérifier que l'adresse appartient bien à l'utilisateur connecté
        if ($address->user_id != auth()->id()) {
            abort(403); // interdit
        }

        // Mettre toutes les adresses de l'utilisateur à is_default = 0
        Address::where('user_id', auth()->id())->update(['is_default' => 0]);

        // Mettre celle choisie à is_default = 1
        $address->update(['is_default' => 1]);

        return redirect()->route('user.addresses')->with('success', 'Adresse principale mise à jour.');
    }

    public function account_update(Request $request)
    {
        $user = auth()->user();

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
    
}
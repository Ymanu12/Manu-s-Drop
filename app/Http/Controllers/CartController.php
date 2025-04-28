<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\Coupon;
use App\Models\Address;
use App\Models\Orders;
use App\Models\OrderItem;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }

    public function add_to_cart(Request $request)
    {
        Cart::instance('cart')->add(
            $request->id,
            $request->name,
            $request->quantity,
            $request->price
        )->associate(\App\Models\Product::class);

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function increase_cart_quantity($rowId)
    {
        if (!Cart::instance('cart')->content()->has($rowId)) {
            return redirect()->back()->withErrors('Product not found in cart.');
        }

        $item = Cart::instance('cart')->get($rowId);
        $newQty = $item->qty + 1;

        Cart::instance('cart')->update($rowId, $newQty);
        return redirect()->back()->with('success', 'Quantity increased.');
    }

    public function decrease_cart_quantity($rowId)
    {
        if (!Cart::instance('cart')->content()->has($rowId)) {
            return redirect()->back()->withErrors('Product not found in cart.');
        }

        $item = Cart::instance('cart')->get($rowId);
        $newQty = $item->qty - 1;

        if ($newQty < 1) {
            Cart::instance('cart')->remove($rowId);
            return redirect()->back()->with('success', 'Product removed from cart.');
        }

        Cart::instance('cart')->update($rowId, $newQty);
        return redirect()->back()->with('success', 'Quantity decreased.');
    }

    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back()->with('success', 'Item removed from cart');
    }

    public function empty_cart()
    {
        Cart::instance('cart')->destroy();
        return redirect()->back()->with('success', 'Item detroy from cart');
    }

    public function apply_coupon_code(Request $request)
    {
        $coupon_code = $request->coupon_code;
        if(isset($coupon_code)) {

            $coupon = Coupon::where('code', $coupon_code)
                ->where('expiry_date', '>=', Carbon::today())
                ->where('cart_value', '<=', Cart::instance('cart')->subtotal())
                ->first();

            if(!$coupon) {
                return redirect()->back()->with('error', 'Invalid coupon code!');
            } else {
                Session::put('coupon', [
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'cart_value' => $coupon->cart_value
                ]);
                $this->calculateDiscount();
                return redirect()->back()->with('status', 'coupon has been applied!');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid coupon code!');
        }
    }

    public function calculateDiscount()
    {
        $discount = 0;
        if(Session::has('coupon')) {
            if(Session::get('coupon')['type'] == 'fixed') {
                $discount = Session::get('coupon')['value'];
            } else {
                $discount = (Cart::instance('cart')->subtotal() * Session::get('coupon')['value']) / 100;
            }
        }

        $subtotalAfterDiscount = Cart::instance('cart')->subtotal() - $discount;
        $taxAfterDiscount = ($subtotalAfterDiscount * config('cart.tax')) / 100;
        $totalAfterDiscount = $subtotalAfterDiscount + $taxAfterDiscount;

        Session::put('discounts', [
            'discount' => number_format(floatval($discount), 2, '.', ''),
            'subtotal' => number_format(floatval($subtotalAfterDiscount), 2, '.', ''),
            'tax' => number_format(floatval($taxAfterDiscount), 2, '.', ''),
            'total' => number_format(floatval($totalAfterDiscount), 2, '.', '')
        ]);
    }

    public function remove_coupon_code()
    {
        Session::forget('coupon');
        Session::forget('discounts');
        return back()->with('success','coupon has been removed!');
    }

    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $address = Address::where('user_id', $user->id)
                        ->where('is_default', true)
                        ->first();

        return view('checkout', compact('address'));
    }

    public function place_an_order(Request $request)
    {
        $user_id = Auth::user()->id;
        $address = Address::where('user_id', $user_id)->where('is_default', 'true')->first();

        if(!$address) {
            $request->validate([
                'name' => 'required|max:100',
                'phone' => 'required|numeric',
                'zip' => 'required|numeric',
                'state' => 'required',
                'city' => 'required',
                'address' => 'required',
                'locality' => 'required',
                'landmark' => 'required',
            ]);

            $address = new Address();
            $address->name = $request->name;
            $address->phone = $request->phone;
            $address->zip = $request->zip;
            $address->state = $request->state;
            $address->city = $request->city;
            $address->address = $request->address;
            $address->locality = $request->locality;
            $address->landmark = $request->landmark;
            $address->country = 'Togo';
            $address->user_id = $user_id;
            $address->is_default = true;
            $address->save();
        }

        $this->setAmountforCheckout();

        $order = new Orders();
        $order->user_id = $user_id;
        $checkout = Session::get('checkout', []);  // Valeur par défaut = tableau vide

        // dd(Session::get('checkout'));

        $order->subtotal = isset($checkout['subtotal']) ? str_replace(',', '', $checkout['subtotal']) : 0;
        $order->discount = isset($checkout['discount']) ? str_replace(',', '', $checkout['discount']) : 0;
        $order->tax = isset($checkout['tax']) ? str_replace(',', '', $checkout['tax']) : 0;
        $order->total = isset($checkout['total']) ? str_replace(',', '', $checkout['total']) : 0;
        $order->name = $address->name;
        $order->phone = $address->phone;
        $order->locality = $address->locality;
        $order->address = $address->address;
        $order->city = $address->city;
        $order->state = $address->state;
        $order->country = $address->country;
        $order->landmark = $address->landmark;
        $order->zip = $address->zip;
        $order->save();

        foreach(Cart::instance('cart')->content() as $item)
        {
            $orderItem = new OrderItem();
            $orderItem->product_id = $item->id;
            $orderItem->orders_id = $order->id;
            $orderItem->price = $item->price;
            $orderItem->quantity = $item->qty;
            $orderItem->save(); // Ajout de cette ligne manquante
        }

        if($request->mode == "card")
        {
            //
        }
        elseif($request->mode == "paypal")
        {
            //
        }
        elseif($request->mode == "cod")
        {
            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->orders_id = $order->id;
            $transaction->mode = $request->mode;
            $transaction->status = "pending";
            $transaction->save();
        }

        Cart::instance('cart')->destroy();
        Session::forget('checkout');
        Session::forget('coupon');
        Session::forget('discounts');
        Session::put('orders_id', $order->id);

        return redirect()->route('cart.order.confirmation');
    }

    public function setAmountforcheckout()
    {
        if(!Cart::instance('cart')->count() > 0)
        {
            Session::forget('checkout');
            return;
        }

        if(Session::has('coupon'))
        {
            Session::put('checkout',[
                'discount' => Session::get('discounts')['discount'],
                'subtotal' => Session::get('discounts')['subtotal'],
                'tax' => Session::get('discounts')['tax'],
                'total' => Session::get('discounts')['total'],
            ]);
        }
        else
        {
            Session::put('checkout',[
                'discount' => 0,
                'subtotal' => Cart::instance('cart')->subtotal(),
                'tax' => Cart::instance('cart')->tax(),
                'total' => Cart::instance('cart')->total(),
            ]);
        }
    }

    public function order_confirmation()
    {
        if (Session::has('orders_id')) {
            $orderId = Session::get('orders_id');
            $order = Orders::find($orderId);

            if ($order) {
                return view('order-confirmation', compact('order'));
            }

            // Si l'ID est dans la session mais ne correspond à aucune commande
            Session::forget('orders_id');
        }

        return redirect()->route('cart.index')->with('error', 'Commande introuvable ou expirée.');
    }

    public function orders()
    {
        $orders = Orders::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders', compact('orders'));
    }

    public function order_details($order_id)
    {
        $order = Orders::find($order_id);
        $orderItems = OrderItem::where('orders_id', $order_id)->orderBy('id')->paginate(12);
        $transaction = Transaction::where('orders_id', $order_id)->first();
        return view('admin.order-details', compact('order','orderItems','transaction'));
    }
}

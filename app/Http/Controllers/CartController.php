<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\Coupon;
use Carbon\Carbon;

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

}

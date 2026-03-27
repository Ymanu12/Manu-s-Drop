<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\OrderItem;
use App\Models\Orders;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }

    public function add_to_cart(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $product = Product::findOrFail($validated['id']);

        if ($product->stock_status !== 'instock' || (int) $product->quantity < 1) {
            return back()->with('error', 'This product is currently unavailable.');
        }

        $existingItem = $this->findCartItemByProductId('cart', $product->id);
        $requestedQty = $validated['quantity'];
        $newQty = $existingItem ? ($existingItem->qty + $requestedQty) : $requestedQty;

        if ($newQty > (int) $product->quantity) {
            return back()->with('error', 'Requested quantity exceeds available stock.');
        }

        if ($existingItem) {
            Cart::instance('cart')->update($existingItem->rowId, $newQty);
        } else {
            Cart::instance('cart')->add(
                $product->id,
                $product->name,
                $requestedQty,
                $this->resolveProductPrice($product)
            )->associate(Product::class);
        }

        return back()->with('success', 'Product added to cart!');
    }

    public function increase_cart_quantity($rowId)
    {
        if (!Cart::instance('cart')->content()->has($rowId)) {
            return back()->withErrors('Product not found in cart.');
        }

        $item = Cart::instance('cart')->get($rowId);
        $product = Product::find($item->id);

        if (!$product || $product->stock_status !== 'instock') {
            Cart::instance('cart')->remove($rowId);
            return back()->with('error', 'This product is no longer available.');
        }

        $newQty = $item->qty + 1;

        if ($newQty > (int) $product->quantity) {
            return back()->with('error', 'Requested quantity exceeds available stock.');
        }

        Cart::instance('cart')->update($rowId, $newQty);
        return back()->with('success', 'Quantity increased.');
    }

    public function decrease_cart_quantity($rowId)
    {
        if (!Cart::instance('cart')->content()->has($rowId)) {
            return back()->withErrors('Product not found in cart.');
        }

        $item = Cart::instance('cart')->get($rowId);
        $newQty = $item->qty - 1;

        if ($newQty < 1) {
            Cart::instance('cart')->remove($rowId);
            return back()->with('success', 'Product removed from cart.');
        }

        Cart::instance('cart')->update($rowId, $newQty);
        return back()->with('success', 'Quantity decreased.');
    }

    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return back()->with('success', 'Item removed from cart');
    }

    public function empty_cart()
    {
        Cart::instance('cart')->destroy();
        return back()->with('success', 'Item removed from cart');
    }

    public function apply_coupon_code(Request $request)
    {
        $validated = $request->validate([
            'coupon_code' => 'required|string|max:50',
        ]);

        $coupon = Coupon::where('code', $validated['coupon_code'])
            ->where('expiry_date', '>=', Carbon::today())
            ->where('cart_value', '<=', Cart::instance('cart')->subtotal())
            ->first();

        if (!$coupon) {
            return back()->with('error', 'Invalid coupon code!');
        }

        Session::put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'cart_value' => $coupon->cart_value,
        ]);

        $this->calculateDiscount();

        return back()->with('status', 'Coupon has been applied!');
    }

    public function calculateDiscount()
    {
        $discount = 0;
        if (Session::has('coupon')) {
            if (Session::get('coupon')['type'] == 'fixed') {
                $discount = Session::get('coupon')['value'];
            } else {
                $discount = (Cart::instance('cart')->subtotal() * Session::get('coupon')['value']) / 100;
            }
        }

        $subtotalAfterDiscount = Cart::instance('cart')->subtotal() - $discount;
        $taxAfterDiscount = ($subtotalAfterDiscount * config('cart.tax')) / 100;
        $totalAfterDiscount = $subtotalAfterDiscount + $taxAfterDiscount;

        Session::put('discounts', [
            'discount' => number_format((float) $discount, 2, '.', ''),
            'subtotal' => number_format((float) $subtotalAfterDiscount, 2, '.', ''),
            'tax' => number_format((float) $taxAfterDiscount, 2, '.', ''),
            'total' => number_format((float) $totalAfterDiscount, 2, '.', ''),
        ]);
    }

    public function remove_coupon_code()
    {
        Session::forget('coupon');
        Session::forget('discounts');
        return back()->with('success', 'Coupon has been removed!');
    }

    public function checkout()
    {
        $user = Auth::user();
        $address = Address::where('user_id', $user->id)
            ->where('is_default', true)
            ->first();

        return view('checkout', compact('address'));
    }

    public function place_an_order(Request $request)
    {
        if (Cart::instance('cart')->count() < 1) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $userId = Auth::id();
        $address = Address::where('user_id', $userId)->where('is_default', true)->first();

        if (!$address) {
            $request->validate([
                'name' => 'required|string|max:100',
                'phone' => 'required|string|max:20',
                'zip' => 'required|string|max:20',
                'state' => 'required|string|max:100',
                'city' => 'required|string|max:100',
                'address' => 'required|string|max:255',
                'locality' => 'required|string|max:255',
                'landmark' => 'required|string|max:255',
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
            $address->user_id = $userId;
            $address->is_default = true;
            $address->save();
        }

        $mode = $request->validate([
            'mode' => 'required|in:card,paypal,cod',
        ])['mode'];

        $this->setAmountforCheckout();
        $cartItems = Cart::instance('cart')->content();

        foreach ($cartItems as $item) {
            $product = Product::find($item->id);

            if (!$product || $product->stock_status !== 'instock') {
                return redirect()->route('cart.index')->with('error', 'One or more products are no longer available.');
            }

            if ($item->qty > (int) $product->quantity) {
                return redirect()->route('cart.index')->with('error', 'One or more cart quantities exceed available stock.');
            }
        }

        $order = DB::transaction(function () use ($userId, $address, $mode, $cartItems) {
            $checkout = Session::get('checkout', []);

            $order = new Orders();
            $order->user_id = $userId;
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

            foreach ($cartItems as $item) {
                $product = Product::lockForUpdate()->findOrFail($item->id);

                if ($item->qty > (int) $product->quantity || $product->stock_status !== 'instock') {
                    abort(422, 'Insufficient stock detected during checkout.');
                }

                $orderItem = new OrderItem();
                $orderItem->product_id = $product->id;
                $orderItem->orders_id = $order->id;
                $orderItem->price = $this->resolveProductPrice($product);
                $orderItem->quantity = $item->qty;
                $orderItem->save();

                $product->quantity = max(0, (int) $product->quantity - (int) $item->qty);
                $product->stock_status = $product->quantity > 0 ? 'instock' : 'outofstock';
                $product->save();
            }

            if ($mode === 'cod') {
                $transaction = new Transaction();
                $transaction->user_id = $userId;
                $transaction->orders_id = $order->id;
                $transaction->mode = $mode;
                $transaction->status = 'pending';
                $transaction->save();
            }

            return $order;
        });

        Cart::instance('cart')->destroy();
        Session::forget('checkout');
        Session::forget('coupon');
        Session::forget('discounts');
        Session::put('orders_id', $order->id);

        return redirect()->route('cart.order.confirmation');
    }

    public function setAmountforCheckout()
    {
        if (!Cart::instance('cart')->count() > 0) {
            Session::forget('checkout');
            return;
        }

        if (Session::has('coupon')) {
            Session::put('checkout', [
                'discount' => Session::get('discounts')['discount'],
                'subtotal' => Session::get('discounts')['subtotal'],
                'tax' => Session::get('discounts')['tax'],
                'total' => Session::get('discounts')['total'],
            ]);
        } else {
            Session::put('checkout', [
                'discount' => 0,
                'subtotal' => Cart::instance('cart')->subtotal(),
                'tax' => Cart::instance('cart')->tax(),
                'total' => Cart::instance('cart')->total(),
            ]);
        }
    }

    public function order_confirmation()
    {
        if (! $order = $this->resolveOrderForConfirmation()) {
            return redirect()->route('cart.index')->with('error', 'Order not found or expired.');
        }

        return view('order-confirmation', compact('order'));
    }

    public function orders()
    {
        $orders = Orders::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders', compact('orders'));
    }

    public function order_details($order_id)
    {
        $order = Orders::find($order_id);

        if (!$order) {
            abort(404);
        }

        $orderItems = OrderItem::where('orders_id', $order_id)->orderBy('id')->paginate(12);
        $transaction = Transaction::where('orders_id', $order_id)->first();

        return view('admin.order-details', compact('order', 'orderItems', 'transaction'));
    }

    protected function resolveProductPrice(Product $product): float
    {
        return (float) ($product->sale_price ?: $product->regular_price);
    }

    protected function findCartItemByProductId(string $instance, int $productId): mixed
    {
        return Cart::instance($instance)->content()->first(function ($item) use ($productId) {
            return (int) $item->id === $productId;
        });
    }

    protected function resolveOrderForConfirmation(?int $orderId = null): ?Orders
    {
        $orderId ??= Session::get('orders_id');

        if (! $orderId) {
            return null;
        }

        return Orders::with('orderItems.product', 'transaction')->find($orderId);
    }

    public function download_order_confirmation_pdf(Request $request)
    {
        $order = $this->resolveOrderForConfirmation($request->query('order_id'));

        if (! $order) {
            return redirect()->route('cart.index')->with('error', 'Order not found.');
        }

        $pdf = Pdf::loadView('pdf.order-confirmation', compact('order'));

        return $pdf->download('order-' . $order->id . '.pdf');
    }
}

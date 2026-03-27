<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class WishlistController extends Controller
{
    public function index()
    {
        $items = Cart::instance('wishlist')->content();
        return view('wishlist', compact('items'));
    }

    public function add_to_wishlist(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:products,id',
        ]);

        $product = Product::findOrFail($validated['id']);

        if ($product->stock_status !== 'instock' || (int) $product->quantity < 1) {
            return back()->with('error', 'This product is currently unavailable.');
        }

        $existingItem = Cart::instance('wishlist')->content()->first(function ($item) use ($product) {
            return (int) $item->id === (int) $product->id;
        });

        if (!$existingItem) {
            Cart::instance('wishlist')->add(
                $product->id,
                $product->name,
                1,
                (float) ($product->sale_price ?: $product->regular_price)
            )->associate(Product::class);
        }

        return back()->with('success', 'Product added to wishlist.');
    }

    public function remove_item($rowId)
    {
        Cart::instance('wishlist')->remove($rowId);
        return back()->with('success', 'Item removed from wishlist');
    }

    public function empty_wishlist()
    {
        Cart::instance('wishlist')->destroy();
        return back()->with('success', 'Wishlist cleared successfully');
    }

    public function move_to_cart($rowId)
    {
        $item = Cart::instance('wishlist')->get($rowId);

        if (!$item) {
            return back()->with('error', 'Product not found in wishlist.');
        }

        $product = Product::find($item->id);

        if (!$product || $product->stock_status !== 'instock' || (int) $product->quantity < 1) {
            Cart::instance('wishlist')->remove($rowId);
            return back()->with('error', 'This product is no longer available.');
        }

        $existingCartItem = Cart::instance('cart')->content()->first(function ($cartItem) use ($product) {
            return (int) $cartItem->id === (int) $product->id;
        });

        $newQty = $existingCartItem ? $existingCartItem->qty + 1 : 1;

        if ($newQty > (int) $product->quantity) {
            return back()->with('error', 'Requested quantity exceeds available stock.');
        }

        Cart::instance('wishlist')->remove($rowId);

        if ($existingCartItem) {
            Cart::instance('cart')->update($existingCartItem->rowId, $newQty);
        } else {
            Cart::instance('cart')->add(
                $product->id,
                $product->name,
                1,
                (float) ($product->sale_price ?: $product->regular_price)
            )->associate(Product::class);
        }

        return back()->with('success', 'Product moved to cart.');
    }
}
@extends('layouts.app')

@section('content')
<main class="pt-90 text-slate-900 dark:text-slate-100">
    <div class="mb-4 pb-4"></div>

    <section class="shop-checkout container md:rounded-2xl md:border md:border-slate-200/70 bg-white/80 md:px-4 md:py-4 md:shadow-sm transition-colors dark:md:border-slate-800 dark:bg-slate-900/80">
        <h2 class="page-title">Wishlist</h2>

        <div class="shopping-cart">
            @if($items->count() > 0)
            <div class="cart-table__wrapper">
                <table class="cart-table overflow-hidden rounded-xl border border-slate-200/70 dark:border-slate-800">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th></th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>
                                <div class="shopping-cart__product-item">
                                    <img loading="lazy" src="{{ asset('uploads/products/thumbnails/' . $item->model->image) }}" width="120" height="120" alt="{{ $item->name }}" />
                                </div>
                            </td>
                            <td>
                                <div class="shopping-cart__product-item__detail">
                                    <h4>{{ $item->name }}</h4>
                                    <ul class="shopping-cart__product-item__options">
                                        <li>Color: Yellow</li>
                                        <li>Size: L</li>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <span class="shopping-cart__product-price">${{ number_format((float) ($item->price ?? 0), 2) }}</span>
                            </td>
                            <td>{{ $item->qty }}</td>
                            <td>
                                <span class="shopping-cart__subtotal">${{ number_format((float) ($item->subtotal() ?? 0), 2) }}</span>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-6">
                                        <form data-recaptcha-ignore="1" method="POST" action="{{ route('wishlist.move.to.cart', $item->rowId) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-warning border-0 shadow-sm">Move to Cart</button>
                                        </form>
                                    </div>
                                    <div class="col-6">
                                        <form data-recaptcha-ignore="1" method="POST" action="{{ route('wishlist.item.remove', ['rowId' => $item->rowId]) }}" id="remove-item-{{ $item->rowId }}">
                                            @csrf
                                            @method('DELETE')
                                            <a href="javascript:void(0)" class="remove-cart" onclick="document.getElementById('remove-item-{{ $item->rowId }}').submit()">
                                                <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" />
                                                    <path d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" />
                                                </svg>
                                            </a>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="cart-table-footer mt-4">
                    <form data-recaptcha-ignore="1" method="POST" action="{{ route('wishlist.empty') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-light border border-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">Clear Wishlist</button>
                    </form>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-md-12 text-center pt-5 pb-5 rounded-xl border border-dashed border-slate-300 bg-white/70 p-8 dark:border-slate-700 dark:bg-slate-900/60">
                    <p>No items found in your wishlist</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-info">Start Shopping</a>
                </div>
            </div>
            @endif
        </div>
    </section>
</main>
@endsection

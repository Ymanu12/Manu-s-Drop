<?php

use App\Http\Controllers\Admin\AccountController as AdminAccountController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SliderController as AdminSliderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/recaptcha/config', function () {
    return response()->json([
        'enabled' => config('recaptcha.enabled') && filled(config('recaptcha.site_key')),
        'siteKey' => config('recaptcha.site_key'),
    ]);
})->name('recaptcha.config');

Route::get('/robots.txt', function () {
    $content = "User-agent: *\nAllow: /\nSitemap: " . url('/sitemap.xml') . "\n";

    return response($content, 200)->header('Content-Type', 'text/plain');
});

Route::get('/sitemap.xml', function () {
    $pages = [
        ['loc' => route('home.index'), 'lastmod' => now()->toAtomString(), 'priority' => '1.0'],
        ['loc' => route('shop.index'), 'lastmod' => now()->toAtomString(), 'priority' => '0.9'],
        ['loc' => route('home.about'), 'lastmod' => now()->toAtomString(), 'priority' => '0.7'],
        ['loc' => route('home.contacts'), 'lastmod' => now()->toAtomString(), 'priority' => '0.7'],
        ['loc' => route('mentions.legales'), 'lastmod' => now()->toAtomString(), 'priority' => '0.3'],
        ['loc' => route('privacy.policy'), 'lastmod' => now()->toAtomString(), 'priority' => '0.3'],
    ];

    $products = Product::select('slug', 'updated_at')->orderByDesc('updated_at')->get();

    return response()
        ->view('sitemap', compact('pages', 'products'))
        ->header('Content-Type', 'application/xml');
});

Route::get('/mentions-legales', function () {
    return view('mentions-legales');
})->name('mentions.legales');

Route::get('/politique-de-confidentialite', function () {
    return view('privacy-policy');
})->name('privacy.policy');

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/contacts', [HomeController::class, 'contacts'])->name('home.contacts');
Route::post('/contact/store', [HomeController::class, 'contact_store'])->middleware('throttle:5,1')->name('home.contact.store');
Route::get('/abouts', [HomeController::class, 'abouts'])->name('home.about');
Route::get('/search', [HomeController::class, 'searchs'])->middleware('throttle:30,1')->name('home.search');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product_slug}', [ShopController::class, 'product_details'])->name('shop.product.detail');

Route::middleware('auth')->group(function () {
    Route::get('/user/index', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/orders', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/user/{order_id}/order_details', [UserController::class, 'order_details'])->name('user.order.details');
    Route::put('/user/order-cancel', [UserController::class, 'order_cancel'])->name('user.order.cancel');

    Route::get('/user/addresses', [UserController::class, 'addresses_index'])->name('user.addresses');
    Route::get('/user/addresses/create', [UserController::class, 'addresses_create'])->name('user.addresses.create');
    Route::post('/user/addresses', [UserController::class, 'addresses_store'])->name('user.addresses.store');
    Route::get('/user/addresses/{address}/edit', [UserController::class, 'addresses_edit'])->name('user.addresses.edit');
    Route::put('/user/addresses/{address}', [UserController::class, 'addresses_update'])->name('user.addresses.update');
    Route::delete('/user/addresses/{address}', [UserController::class, 'addresses_destroy'])->name('user.addresses.destroy');
    Route::post('/user/addresses/{address}/default', [UserController::class, 'addresses_set_default'])->name('user.addresses.set_default');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add_to_cart'])->name('cart.add');
    Route::post('/cart/increase/{rowId}', [CartController::class, 'increase_cart_quantity'])->name('cart.increase');
    Route::post('/cart/decrease/{rowId}', [CartController::class, 'decrease_cart_quantity'])->name('cart.decrease');
    Route::delete('/cart/delete/{rowId}', [CartController::class, 'remove_item'])->name('cart.item.remove');
    Route::delete('/cart/destroy', [CartController::class, 'empty_cart'])->name('cart.empty');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/place-an-order', [CartController::class, 'place_an_order'])->name('cart.place.an.order');
    Route::get('/order-confirmation', [CartController::class, 'order_confirmation'])->name('cart.order.confirmation');
    Route::post('/cart/apply-coupon', [CartController::class, 'apply_coupon_code'])->name('cart.coupon.apply');
    Route::delete('/cart/remove-coupon', [CartController::class, 'remove_coupon_code'])->name('cart.coupon.remove');

    Route::post('/wishlist/add', [WishlistController::class, 'add_to_wishlist'])->name('wishlist.add');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::delete('/wishlist/delete/{rowId}', [WishlistController::class, 'remove_item'])->name('wishlist.item.remove');
    Route::delete('/wishlist/destroy', [WishlistController::class, 'empty_wishlist'])->name('wishlist.empty');
    Route::post('/wishlist/move-to-cart/{rowId}', [WishlistController::class, 'move_to_cart'])->name('wishlist.move.to.cart');

    Route::get('/user/account', [UserController::class, 'account_edit'])->name('user.account.details');
    Route::post('/user/account', [UserController::class, 'account_update'])->name('user.account.update');
});

Route::middleware(['auth', 'auth.admin'])->group(function () {
    Route::get('/admin/index', [AdminDashboardController::class, 'index'])->name('admin.index');
    Route::get('/admin/search', [AdminDashboardController::class, 'search'])->middleware('throttle:60,1')->name('admin.search');

    Route::prefix('admin/brands')->group(function () {
        Route::get('/', [AdminBrandController::class, 'brands'])->name('admin.brands');
        Route::get('/add', [AdminBrandController::class, 'add_brand'])->name('admin.brand.add');
        Route::post('/store', [AdminBrandController::class, 'store_brand'])->name('admin.brands.store');
        Route::get('/edit/{id}', [AdminBrandController::class, 'brand_edit'])->name('admin.brand.edit');
        Route::put('/update/{id}', [AdminBrandController::class, 'update_brand'])->name('admin.brand.update');
        Route::delete('/{id}/delete', [AdminBrandController::class, 'destroy_brand'])->name('admin.brand.delete');
    });

    Route::prefix('admin/categories')->group(function () {
        Route::get('/', [AdminCategoryController::class, 'categories'])->name('admin.categories');
        Route::get('/add', [AdminCategoryController::class, 'add_category'])->name('admin.category.add');
        Route::post('/store', [AdminCategoryController::class, 'store_category'])->name('admin.categories.store');
        Route::get('/{id}/edit', [AdminCategoryController::class, 'category_edit'])->name('admin.category.edit');
        Route::post('/{id}/update', [AdminCategoryController::class, 'update_category'])->name('admin.category.update');
        Route::delete('/{id}', [AdminCategoryController::class, 'destroy_category'])->name('admin.category.delete');
    });

    Route::prefix('admin/product')->group(function () {
        Route::get('/', [AdminProductController::class, 'products'])->name('admin.products');
        Route::get('/add', [AdminProductController::class, 'product_add'])->name('admin.product.add');
        Route::post('/add', [AdminProductController::class, 'product_store'])->name('admin.product.store');
        Route::get('/{id}/edit', [AdminProductController::class, 'product_edit'])->name('admin.product.edit');
        Route::put('/{id}/update', [AdminProductController::class, 'product_update'])->name('admin.product.update');
        Route::delete('/{id}', [AdminProductController::class, 'product_delete'])->name('admin.product.delete');
    });

    Route::prefix('admin/coupons')->group(function () {
        Route::get('/', [AdminCouponController::class, 'coupons'])->name('admin.coupons');
        Route::get('/add', [AdminCouponController::class, 'coupon_add'])->name('admin.coupon.add');
        Route::post('/store', [AdminCouponController::class, 'coupon_store'])->name('admin.coupon.store');
        Route::get('/{id}/edit', [AdminCouponController::class, 'coupon_edit'])->name('admin.coupon.edit');
        Route::put('/{id}', [AdminCouponController::class, 'coupon_update'])->name('admin.coupon.update');
        Route::delete('/{id}/delete', [AdminCouponController::class, 'coupon_destroy'])->name('admin.coupon.destroy');
    });

    Route::prefix('admin/orders')->group(function () {
        Route::get('/', [AdminOrderController::class, 'orders'])->name('admin.orders');
        Route::get('/{order_id}/details', [AdminOrderController::class, 'order_details'])->name('admin.order.details');
        Route::put('/update-order-status', [AdminOrderController::class, 'update_order_status'])->name('update.order.status');
    });

    Route::prefix('admin/slides')->group(function () {
        Route::get('/', [AdminSliderController::class, 'sliders'])->name('admin.sliders');
        Route::get('/add', [AdminSliderController::class, 'add_slider'])->name('admin.slider.add');
        Route::post('/store', [AdminSliderController::class, 'store_slider'])->name('admin.slider.store');
        Route::get('/{id}/edit', [AdminSliderController::class, 'edit_slider'])->name('admin.slider.edit');
        Route::put('/{id}', [AdminSliderController::class, 'update_slider'])->name('admin.slider.update');
        Route::delete('/{id}/delete', [AdminSliderController::class, 'destroy_slider'])->name('admin.slider.destroy');
    });

    Route::prefix('admin/contacts')->group(function () {
        Route::get('/', [AdminContactController::class, 'contacts'])->name('admin.contacts');
        Route::delete('/{id}', [AdminContactController::class, 'contact_delete'])->name('admin.contact.delete');
    });

    Route::get('/admin/settings', [AdminAccountController::class, 'editAccount'])->name('admin.account.edit');
    Route::post('/admin/settings', [AdminAccountController::class, 'updateAccount'])->name('admin.account.update');
});


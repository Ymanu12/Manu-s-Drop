<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController; // Manquait ici
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;

// 📰 Pages légales
Route::get('/mentions-legales', function () {
    return view('mentions-legales');
})->name('mentions.legales');

Route::get('/politique-de-confidentialite', function () {
    return view('privacy-policy');
})->name('privacy.policy');

// 🔐 Authentification
Auth::routes();

// 🔒 Routes protégées par middleware "auth" add_to_cart
Route::middleware('auth')->group(function () {

    // 🏠 Accueil
    Route::get('/', [HomeController::class, 'index'])->name('home.index');
    Route::get('/contacts', [HomeController::class, 'contacts'])->name('home.contacts');
    Route::post('/contact/store', [HomeController::class, 'contact_store'])->name('home.contact.store');
    Route::get('/abouts', [HomeController::class, 'abouts'])->name('home.about');

    Route::get('/search', [HomeController::class, 'searchs'])->name('home.search');

    // 👤 Utilisateur
    Route::get('/user/index', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/orders', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/user/{order_id}/order_details', [UserController::class, 'order_details'])->name('user.order.details');
    Route::put('/user/order-cancel', [UserController::class, 'order_cancel'])->name('user.order.cancel');
    Route::get('/user/addresses', [UserController::class, 'address'])->name('user.addresses');


    // 👤 shop
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/{product_slug}', [ShopController::class, 'product_details'])->name('shop.product.detail');

    // 👤 cart
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
    // 👤 whishlist
    Route::post('/wishlist/add',[WishlistController::class, 'add_to_wishlist'])->name('wishlist.add');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::delete('/wishlist/delete/{rowId}', [wishlistController::class, 'remove_item'])->name('wishlist.item.remove');
    Route::delete('/wishlist/destroy', [WishlistController::class, 'empty_wishlist'])->name('wishlist.empty');
    Route::post('//wishlist/move-to-cart/{rowId}', [WishlistController::class, 'move_to_cart'])->name('wishlist.move.to.cart');

    // 🛠️ Admin Dashboard
    Route::get('/admin/index', [AdminController::class, 'index'])->name('admin.index');

    Route::get('/admin/search', [AdminController::class, 'search'])->name('admin.search');

    // 🏷️ Marques (Brands)
    Route::prefix('admin/brands')->group(function () {
        Route::get('/', [AdminController::class, 'brands'])->name('admin.brands');
        Route::get('/add', [AdminController::class, 'add_brand'])->name('admin.brand.add');
        Route::post('/store', [AdminController::class, 'store_brand'])->name('admin.brands.store');
        Route::get('/edit/{id}', [AdminController::class, 'brand_edit'])->name('admin.brand.edit');
        Route::put('/update/{id}', [AdminController::class, 'update_brand'])->name('admin.brand.update');
        Route::delete('/{id}/delete', [AdminController::class, 'destroy_brand'])->name('admin.brand.delete');
    });

    // 📂 Catégories
    Route::prefix('admin/categories')->group(function () {
        Route::get('/', [AdminController::class, 'categories'])->name('admin.categories');
        Route::get('/add', [AdminController::class, 'add_category'])->name('admin.category.add');
        Route::post('/store', [AdminController::class, 'store_category'])->name('admin.categories.store');
        Route::get('/{id}/edit', [AdminController::class, 'category_edit'])->name('admin.category.edit');
        Route::post('/{id}/update', [AdminController::class, 'update_category'])->name('admin.category.update');
        Route::delete('/{id}', [AdminController::class, 'destroy_category'])->name('admin.category.delete');
    });

    // 🛍 Produits
    Route::prefix('admin/product')->group(function () {
        Route::get('/', [AdminController::class, 'products'])->name('admin.products');
        Route::get('/add', [AdminController::class, 'product_add'])->name('admin.product.add');
        Route::post('/add', [AdminController::class, 'product_store'])->name('admin.product.store');
        Route::get('/{id}/edit', [AdminController::class, 'product_edit'])->name('admin.product.edit');
        Route::put('/{id}/update', [AdminController::class, 'product_update'])->name('admin.product.update');
        Route::delete('/{id}', [AdminController::class, 'product_delete'])->name('admin.product.delete');
    });

    // 🛍 coupons
    Route::prefix('admin/coupons')->group(function () {
        Route::get('/', [AdminController::class, 'coupons'])->name('admin.coupons');
        Route::get('/add', [AdminController::class, 'coupon_add'])->name('admin.coupon.add');
        Route::post('/store', [AdminController::class, 'coupon_store'])->name('admin.coupon.store');
        Route::get('/{id}/edit', [AdminController::class, 'coupon_edit'])->name('admin.coupon.edit');
        Route::put('/{id}', [AdminController::class, 'coupon_update'])->name('admin.coupon.update');
        Route::delete('/{id}/delete', [AdminController::class, 'coupon_destroy'])->name('admin.coupon.destroy');
    });

    Route::prefix('admin/orders')->group(function () {
        Route::get('/', [CartController::class, 'orders'])->name('admin.orders');
        Route::get('/{order_id}/details', [CartController::class, 'order_details'])->name('admin.order.details');
        Route::put('/update-order-status', [AdminController::class, 'update_order_status'])->name('update.order.status');
    });

    // 🛍 coupons
    Route::prefix('admin/slides')->group(function () {
        Route::get('/', [AdminController::class, 'sliders'])->name('admin.sliders');
        Route::get('/add', [AdminController::class, 'add_slider'])->name('admin.slider.add');
        Route::post('/store', [AdminController::class, 'store_slider'])->name('admin.slider.store');
        Route::get('/{id}/edit', [AdminController::class, 'edit_slider'])->name('admin.slider.edit');
        Route::put('/{id}', [AdminController::class, 'update_slider'])->name('admin.slider.update');
        Route::delete('/{id}/delete', [AdminController::class, 'destroy_slider'])->name('admin.slider.destroy');
    });

    // 🛍 Produits
    Route::prefix('admin/contacts')->group(function () {
        Route::get('/', [AdminController::class, 'contacts'])->name('admin.contacts');
        Route::delete('/{id}', [AdminController::class, 'contact_delete'])->name('admin.contact.delete');
    });

    // ⚙️ Paramètres utilisateur (optionnel)
    /*
    Route::get('/settings', [UserController::class, 'edit'])->name('settings');
    Route::post('/settings/update-profile', [UserController::class, 'updateProfile'])->name('settings.updateProfile');
    Route::post('/settings/update-password', [UserController::class, 'updatePassword'])->name('settings.updatePassword');
    Route::post('/settings/update-theme', [UserController::class, 'updateTheme'])->name('settings.updateTheme');
    Route::delete('/delete-account', [UserController::class, 'deleteAccount'])->name('delete.account');
    */
});

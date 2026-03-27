<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Coupon;
use App\Models\Orders;
use App\Models\Product;
use App\Models\Slider;
use App\Policies\BrandPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\ContactPolicy;
use App\Policies\CouponPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SliderPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('access-admin', function ($user) {
            return strtoupper((string) ($user->utype ?? '')) === 'ADM';
        });

        Gate::policy(Brand::class, BrandPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Coupon::class, CouponPolicy::class);
        Gate::policy(Slider::class, SliderPolicy::class);
        Gate::policy(Contact::class, ContactPolicy::class);
        Gate::policy(Orders::class, OrderPolicy::class);

        View::composer('layouts.admin', function ($view) {
            $pendingOrders = Orders::query()
                ->where('status', 'ordered')
                ->latest()
                ->take(4)
                ->get(['id', 'name', 'total', 'created_at']);

            $recentContacts = Contact::query()
                ->latest()
                ->take(3)
                ->get(['id', 'name', 'email', 'created_at']);

            $lowStockProducts = Product::query()
                ->where('quantity', '<=', 5)
                ->orderBy('quantity')
                ->take(3)
                ->get(['id', 'name', 'quantity', 'updated_at']);

            $notifications = collect()
                ->merge($pendingOrders->map(function ($order) {
                    return [
                        'icon' => 'icon-noti-3',
                        'title' => 'New order #' . $order->id,
                        'message' => $order->name . ' placed an order for $' . number_format((float) ($order->total ?? 0), 2),
                        'time' => optional($order->created_at)->diffForHumans(),
                        'url' => route('admin.order.details', ['order_id' => $order->id]),
                        'created_at' => $order->created_at,
                    ];
                }))
                ->merge($recentContacts->map(function ($contact) {
                    return [
                        'icon' => 'icon-noti-2',
                        'title' => 'New contact message',
                        'message' => $contact->name . ' sent a message from ' . $contact->email,
                        'time' => optional($contact->created_at)->diffForHumans(),
                        'url' => route('admin.contacts'),
                        'created_at' => $contact->created_at,
                    ];
                }))
                ->merge($lowStockProducts->map(function ($product) {
                    return [
                        'icon' => 'icon-noti-1',
                        'title' => 'Low stock alert',
                        'message' => $product->name . ' has only ' . (int) $product->quantity . ' item(s) left',
                        'time' => optional($product->updated_at)->diffForHumans(),
                        'url' => route('admin.products'),
                        'created_at' => $product->updated_at,
                    ];
                }))
                ->sortByDesc(fn ($notification) => $notification['created_at'] ?? now())
                ->take(6)
                ->values();

            $view->with('adminHeaderData', [
                'notificationCount' => $notifications->count(),
                'notifications' => $notifications,
            ]);
        });
    }
}

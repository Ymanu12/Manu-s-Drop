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
    }
}

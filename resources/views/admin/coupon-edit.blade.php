@extends('layouts.admin')

@section('content')
<div class="main-content-inner text-slate-900 dark:text-slate-100">
    <div class="main-content-wrap text-slate-900 dark:text-slate-100">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-header__eyebrow">Promotions</div>
                <h2 class="admin-page-header__title">Edit coupon</h2>
                <p class="admin-page-header__meta">Adjust the discount logic while keeping the offer rules easy to audit and maintain.</p>
            </div>
            <ul class="admin-breadcrumbs">
                <li><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li><a href="{{ route('admin.coupons') }}">Coupons</a></li>
                <li>Edit</li>
            </ul>
        </div>

        <form method="POST" action="{{ route('admin.coupon.update', $coupon->id) }}" class="form-new-product form-style-1 admin-form-grid">
            @csrf
            @method('PUT')

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <div class="admin-form-panel__title">Coupon information</div>
                        <p class="admin-form-panel__meta">Update the code and discount type for this campaign without losing checkout clarity.</p>
                    </div>
                    <span class="admin-form-badge">Promotion rule</span>
                </div>

                <x-input-field name="code" label="Coupon code" :required="true" :value="$coupon->code" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="value" label="Value" :required="true" :value="$coupon->value" wrapperClass="form-floating theme-form-field" />

                <fieldset class="theme-form-field category">
                    <div class="body-title">Coupon type</div>
                    <div class="select flex-grow admin-shell-select">
                        <select name="type">
                            <option value="">Select</option>
                            <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Fixed amount</option>
                            <option value="percent" {{ old('type', $coupon->type) == 'percent' ? 'selected' : '' }}>Percentage</option>
                        </select>
                    </div>
                </fieldset>
            </section>

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <div class="admin-form-panel__title">Redemption rules</div>
                        <p class="admin-form-panel__meta">Maintain clear eligibility and expiration conditions for the checkout team and customers.</p>
                    </div>
                    <span class="admin-form-badge">Checkout</span>
                </div>

                <x-input-field name="cart_value" label="Cart value" :required="true" :value="$coupon->cart_value" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="expiry_date" type="date" label="Expiry date" :required="true" :value="\Carbon\Carbon::parse($coupon->expiry_date)->format('Y-m-d')" wrapperClass="form-floating theme-form-field" />

                <div class="admin-form-actions admin-form-actions--split">
                    <div class="admin-form-note">Updating a live coupon changes checkout behavior immediately.</div>
                    <button class="tf-button" type="submit">Update coupon</button>
                </div>
            </section>
        </form>
    </div>
</div>
@endsection
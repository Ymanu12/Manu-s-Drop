@extends('layouts.admin')

@section('content')
<div class="main-content-inner text-slate-900 dark:text-slate-100">
    <div class="main-content-wrap text-slate-900 dark:text-slate-100">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-header__eyebrow">Promotions</div>
                <h2 class="admin-page-header__title">Create coupon</h2>
                <p class="admin-page-header__meta">Configure discount rules clearly so the team can manage campaigns without ambiguity.</p>
            </div>
            <ul class="admin-breadcrumbs">
                <li><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li><a href="{{ route('admin.coupons') }}">Coupons</a></li>
                <li>Create</li>
            </ul>
        </div>

        <form method="POST" action="{{ route('admin.coupon.store') }}" class="form-new-product form-style-1 admin-form-grid">
            @csrf

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <div class="admin-form-panel__title">Coupon information</div>
                        <p class="admin-form-panel__meta">Define the public coupon code and the discount model used at checkout.</p>
                    </div>
                    <span class="admin-form-badge">Promotion rule</span>
                </div>

                <x-input-field name="code" label="Coupon code" :required="true" :value="old('code')" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="value" label="Value" :required="true" :value="old('value')" wrapperClass="form-floating theme-form-field" />

                <fieldset class="theme-form-field category">
                    <div class="body-title">Coupon type</div>
                    <div class="select flex-grow admin-shell-select">
                        <select name="type">
                            <option value="">Select</option>
                            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed amount</option>
                            <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Percentage</option>
                        </select>
                    </div>
                    <p class="admin-form-help">Choose whether the discount is a flat amount or a percentage.</p>
                </fieldset>
            </section>

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <div class="admin-form-panel__title">Redemption rules</div>
                        <p class="admin-form-panel__meta">Control when the coupon can be used and the minimum order value required.</p>
                    </div>
                    <span class="admin-form-badge">Checkout</span>
                </div>

                <x-input-field name="cart_value" label="Cart value" :required="true" :value="old('cart_value')" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="expiry_date" type="date" label="Expiry date" :required="true" :value="old('expiry_date')" wrapperClass="form-floating theme-form-field" />

                <div class="admin-form-actions admin-form-actions--split">
                    <div class="admin-form-note">Keep coupon rules explicit to reduce checkout friction and support requests.</div>
                    <button class="tf-button" type="submit">Save coupon</button>
                </div>
            </section>
        </form>
    </div>
</div>
@endsection
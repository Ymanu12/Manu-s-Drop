@extends('layouts.admin')

@section('content')
<div class="main-content-inner text-slate-900 dark:text-slate-100">
    <div class="main-content-wrap text-slate-900 dark:text-slate-100">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-header__eyebrow">Administration</div>
                <h3 class="admin-page-header__title">Settings</h3>
                <div class="admin-page-header__meta">Update admin identity details and secure access credentials from one clear, wide account settings screen.</div>
            </div>
            <ul class="breadcrumbs admin-breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="{{ route('admin.index') }}"><div class="text-tiny">Dashboard</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Settings</div></li>
            </ul>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger admin-form-alert mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-danger">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success admin-form-alert mb-4">{{ session('success') }}</div>
        @endif

        <div class="my-account__edit-form">
            <form name="account_edit_form" action="{{ route('admin.account.update') }}" method="POST" class="form-new-product form-style-1 needs-validation admin-form-grid" novalidate>
                @csrf

                <section class="admin-form-panel admin-form-stack">
                    <div class="admin-form-panel__header">
                        <div>
                            <h4 class="admin-form-panel__title">Profile details</h4>
                            <div class="admin-form-panel__meta">Keep the admin profile information accurate for contact, notifications, and account recovery.</div>
                        </div>
                        <div class="admin-form-badge">Identity</div>
                    </div>

                    <x-input-field name="name" label="Name" :required="true" :value="auth()->user()->name" wrapperClass="form-floating theme-form-field" />
                    <x-input-field name="mobile" label="Mobile number" :required="true" :value="auth()->user()->mobile" wrapperClass="form-floating theme-form-field" />
                    <x-input-field name="email" type="email" label="Email address" :required="true" :value="auth()->user()->email" wrapperClass="form-floating theme-form-field" />
                </section>

                <section class="admin-form-panel admin-form-stack">
                    <div class="admin-form-panel__header">
                        <div>
                            <h4 class="admin-form-panel__title">Password change</h4>
                            <div class="admin-form-panel__meta">Leave the password fields empty if you only want to update profile information.</div>
                        </div>
                        <div class="admin-form-badge">Security</div>
                    </div>

                    <x-input-field name="old_password" type="password" label="Current password" autocomplete="current-password" wrapperClass="form-floating theme-form-field" />
                    <x-input-field name="new_password" type="password" label="New password" autocomplete="new-password" wrapperClass="form-floating theme-form-field" />
                    <x-input-field name="new_password_confirmation" type="password" label="Confirm new password" autocomplete="new-password" wrapperClass="form-floating theme-form-field" />

                    <div class="admin-form-actions admin-form-actions--split">
                        <div class="admin-form-note">Your changes apply to the currently authenticated administrator account.</div>
                        <button type="submit" class="btn btn-primary tf-button">Save changes</button>
                    </div>
                </section>
            </form>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')

<main class="pt-90 text-slate-900 dark:text-slate-100">
    <div class="mb-4 pb-4"></div>
    <section class="login-register container max-w-2xl rounded-2xl border border-slate-200/70 bg-white/85 px-4 py-5 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-900/85">
        <ul class="nav nav-tabs mb-5" id="login_register" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link nav-link_underscore active" id="register-tab" data-bs-toggle="tab"
                   href="#tab-item-register" role="tab" aria-controls="tab-item-register" aria-selected="true">
                    Register
                </a>
            </li>
        </ul>
        <div class="tab-content pt-2" id="login_register_tab_content">
            <div class="tab-pane fade show active" id="tab-item-register" role="tabpanel" aria-labelledby="register-tab">
                <div class="register-form rounded-xl border border-slate-200/70 bg-slate-50/80 p-4 transition-colors dark:border-slate-800 dark:bg-slate-950/70">
                    <form method="POST" action="{{ route('register') }}" name="register-form" class="needs-validation" novalidate>
                        @csrf

                        <x-input-field
                            name="name"
                            label="Name"
                            :required="true"
                            autocomplete="name"
                            :autofocus="true"
                        />

                        <div class="pb-3"></div>

                        <x-input-field
                            name="email"
                            type="email"
                            label="Email address"
                            :required="true"
                            autocomplete="email"
                        />

                        <div class="pb-3"></div>

                        <x-input-field
                            name="mobile"
                            label="Mobile"
                            :required="true"
                            autocomplete="tel"
                        />

                        <div class="pb-3"></div>

                        <x-input-field
                            name="password"
                            type="password"
                            label="Password"
                            :required="true"
                            autocomplete="new-password"
                        />

                        <x-input-field
                            name="password_confirmation"
                            id="password-confirm"
                            type="password"
                            label="Confirm Password"
                            :required="true"
                            autocomplete="new-password"
                        />

                        <div class="d-flex align-items-center mb-3 pb-2 text-slate-600 dark:text-slate-300">
                            <p class="m-0">
                                Your personal data will be used to support your experience throughout this website,
                                to manage access to your account, and for other purposes described in our privacy policy.
                            </p>
                        </div>

                        <button class="btn btn-primary w-100 text-uppercase" type="submit">Register</button>

                        <div class="customer-option mt-4 text-center text-slate-600 dark:text-slate-300">
                            <span class="text-secondary">Have an account?</span>
                            <a href="{{ route('login') }}" class="btn-text js-show-register">Login to your Account</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@extends('layouts.app')

@section('content')

<main class="pt-90 text-slate-900 dark:text-slate-100">
    <div class="mb-4 pb-4"></div>
    <section class="login-register container max-w-2xl rounded-2xl border border-slate-200/70 bg-white/85 px-4 py-5 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-900/85">
        <ul class="nav nav-tabs mb-5" id="login_register" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link nav-link_underscore active" id="login-tab" data-bs-toggle="tab" href="#tab-item-login"
                   role="tab" aria-controls="tab-item-login" aria-selected="true">Login</a>
            </li>
        </ul>
        <div class="tab-content pt-2" id="login_register_tab_content">
            <div class="tab-pane fade show active" id="tab-item-login" role="tabpanel" aria-labelledby="login-tab">
                <div class="login-form rounded-xl border border-slate-200/70 bg-slate-50/80 p-4 transition-colors dark:border-slate-800 dark:bg-slate-950/70">
                    <form method="POST" action="{{ route('login') }}" name="login-form" class="needs-validation" novalidate>
                        @csrf

                        <x-input-field
                            name="email"
                            type="email"
                            label="Email address"
                            :required="true"
                            autocomplete="email"
                            :autofocus="true"
                        />

                        <div class="pb-3"></div>

                        <x-input-field
                            name="password"
                            type="password"
                            label="Password"
                            :required="true"
                            autocomplete="current-password"
                        />

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                           {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100 text-uppercase" type="submit">Log In</button>

                        <div class="customer-option mt-4 text-center text-slate-600 dark:text-slate-300">
                            <span class="text-secondary">No account yet?</span>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-text js-show-register">Create Account</a> ||||
                            @endif
                            @if (Route::has('password.request'))
                                <a class="btn-text js-show-register" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

@endsection

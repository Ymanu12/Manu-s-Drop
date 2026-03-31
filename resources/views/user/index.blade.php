@extends('layouts.app')

@section('content')
<main class="pt-90 text-slate-900 dark:text-slate-100">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container md:rounded-2xl md:border md:border-slate-200/70 bg-white/80 md:px-4 md:py-4 md:shadow-sm transition-colors dark:md:border-slate-800 dark:bg-slate-900/80">
      <h2 class="page-title">My Account</h2>
      <div class="row">
        <div class="col-lg-3">
          @include('user.account-nav')
        </div>
        <div class="col-lg-9">
          <div class="page-content my-account__dashboard md:rounded-xl md:border md:border-slate-200/70 bg-slate-50/80 md:p-5 transition-colors dark:md:border-slate-800 dark:bg-slate-950/70">
            <p>Hello <strong>User</strong></p>
            <p class="text-slate-600 dark:text-slate-300">From your account dashboard you can view your <a class="unerline-link text-slate-900 dark:text-slate-100" href="account_orders.html">recent
                orders</a>, manage your <a class="unerline-link text-slate-900 dark:text-slate-100" href="account_edit_address.html">shipping
                addresses</a>, and <a class="unerline-link text-slate-900 dark:text-slate-100" href="account_edit.html">edit your password and account
                details.</a></p>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection
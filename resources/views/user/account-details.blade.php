@extends('layouts.app')

@section('content')
<main class="pt-90 text-slate-900 dark:text-slate-100">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container md:rounded-2xl md:border md:border-slate-200/70 bg-white/80 md:px-4 md:py-4 md:shadow-sm transition-colors dark:md:border-slate-800 dark:bg-slate-900/80">
      <h2 class="page-title">Account Details</h2>
      <div class="row">
        <div class="col-lg-3">
          @include('user.account-nav')
        </div>
        <div class="col-lg-9">
          <div class="page-content my-account__edit md:rounded-xl md:border md:border-slate-200/70 bg-slate-50/80 md:p-5 transition-colors dark:md:border-slate-800 dark:bg-slate-950/70">
            <div class="my-account__edit-form">
              <form action="{{ route('user.account.update') }}" method="POST" class="needs-validation" novalidate="">
                @csrf
                @method('PUT')

                <div class="row">
                  <div class="col-md-6">
                    <x-input-field
                      name="name"
                      label="Name"
                      :required="true"
                      :value="auth()->user()->name"
                      wrapperClass="form-floating my-3 theme-form-field"
                    />
                  </div>
                  <div class="col-md-12">
                    <x-input-field
                      name="mobile"
                      label="Mobile Number"
                      :required="true"
                      :value="auth()->user()->mobile"
                      wrapperClass="form-floating my-3 theme-form-field"
                    />
                  </div>
                  <div class="col-md-12">
                    <x-input-field
                      name="email"
                      type="email"
                      label="Email Address"
                      :required="true"
                      :value="auth()->user()->email"
                      wrapperClass="form-floating my-3 theme-form-field"
                    />
                  </div>
                  <div class="col-md-12">
                    <div class="my-3">
                      <h5 class="text-uppercase mb-0">Password Change</h5>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <x-input-field
                      name="old_password"
                      type="password"
                      label="Old password"
                      autocomplete="current-password"
                      wrapperClass="form-floating my-3 theme-form-field"
                    />
                  </div>
                  <div class="col-md-12">
                    <x-input-field
                      name="new_password"
                      type="password"
                      label="New password"
                      autocomplete="new-password"
                      wrapperClass="form-floating my-3 theme-form-field"
                    />
                  </div>
                  <div class="col-md-12">
                    <x-input-field
                      name="new_password_confirmation"
                      type="password"
                      label="Confirm new password"
                      autocomplete="new-password"
                      wrapperClass="form-floating my-3 theme-form-field"
                    />
                  </div>
                  <div class="col-md-12">
                    <div class="my-3">
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
</main>
@endsection

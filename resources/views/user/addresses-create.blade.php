@extends('layouts.app')

@section('content')
<main class="pt-90 text-slate-900 dark:text-slate-100">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container md:rounded-2xl md:border md:border-slate-200/70 bg-white/80 md:px-4 md:py-4 md:shadow-sm transition-colors dark:md:border-slate-800 dark:bg-slate-900/80">
        <h2 class="page-title">Address</h2>
        <div class="row">
            <div class="col-lg-3">
                @include('user.account-nav')
            </div>
            <div class="col-lg-9">
                <div class="page-content my-account__address md:rounded-xl md:border md:border-slate-200/70 bg-slate-50/80 md:p-5 transition-colors dark:md:border-slate-800 dark:bg-slate-950/70">
                    <div class="row">
                        <div class="col-6">
                            <p class="notice text-slate-600 dark:text-slate-300">The following addresses will be used on the checkout page by default.</p>
                        </div>
                        <div class="col-6 text-right">
                            <a href="{{ route('user.addresses') }}" class="btn btn-sm btn-danger">Back</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-5 md:rounded-xl md:border md:border-slate-200/70 bg-white md:shadow-sm transition-colors dark:md:border-slate-800 dark:bg-slate-950">
                                <div class="card-header border-b border-slate-200/70 bg-slate-50 dark:border-slate-800 dark:bg-slate-900">
                                    <h5>Add New Address</h5>
                                </div>
                                <div class="card-body text-slate-700 dark:text-slate-300">
                                    <form action="{{ route('user.addresses.store') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <!-- Full Name -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <input type="text" class="form-control border-slate-200 bg-white text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" name="name" value="{{ old('name') }}">
                                                    <label for="name">Full Name *</label>
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Phone Number -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <input type="text" class="form-control border-slate-200 bg-white text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" name="phone" value="{{ old('phone') }}">
                                                    <label for="phone">Phone Number *</label>
                                                    @error('phone')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Zip -->
                                            <div class="col-md-4">
                                                <div class="form-floating my-3">
                                                    <input type="text" class="form-control border-slate-200 bg-white text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" name="zip" value="{{ old('zip') }}">
                                                    <label for="zip">Pincode *</label>
                                                    @error('zip')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- State -->
                                            <div class="col-md-4">
                                                <div class="form-floating mt-3 mb-3">
                                                    <input type="text" class="form-control border-slate-200 bg-white text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" name="state" value="{{ old('state') }}">
                                                    <label for="state">State *</label>
                                                    @error('state')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- City -->
                                            <div class="col-md-4">
                                                <div class="form-floating my-3">
                                                    <input type="text" class="form-control border-slate-200 bg-white text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" name="city" value="{{ old('city') }}">
                                                    <label for="city">Town / City *</label>
                                                    @error('city')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Country -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <input type="text" class="form-control border-slate-200 bg-white text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" name="country" value="{{ old('country') }}">
                                                    <label for="country">Country *</label>
                                                    @error('country')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Address -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <input type="text" class="form-control border-slate-200 bg-white text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" name="address" value="{{ old('address') }}">
                                                    <label for="address">House No, Building Name *</label>
                                                    @error('address')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Locality -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <input type="text" class="form-control border-slate-200 bg-white text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" name="locality" value="{{ old('locality') }}">
                                                    <label for="locality">Road Name, Area, Colony *</label>
                                                    @error('locality')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Landmark -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <input type="text" class="form-control border-slate-200 bg-white text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" name="landmark" value="{{ old('landmark') }}">
                                                    <label for="landmark">Landmark</label>
                                                    @error('landmark')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Address Type -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <select class="form-control border-slate-200 bg-white text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" name="type">
                                                        <option value="">Select Type</option>
                                                        <option value="home" {{ old('type') == 'home' ? 'selected' : '' }}>Home</option>
                                                        <option value="work" {{ old('type') == 'work' ? 'selected' : '' }}>Work</option>
                                                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                    <label for="type">Address Type</label>
                                                    @error('type')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Is Default -->
                                            <div class="col-md-6">
                                                <div class="form-check my-4">
                                                    <input class="form-check-input" type="checkbox" value="1" id="isdefault" name="is_default" {{ old('is_default') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="isdefault">
                                                        Make as Default Address
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="col-md-12 text-right">
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </div>
                                    </form> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>                    
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

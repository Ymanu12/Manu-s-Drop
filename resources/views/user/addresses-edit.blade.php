@extends('layouts.app')

@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <h2 class="page-title">Address</h2>
        <div class="row">
            <div class="col-lg-3">
                @include('user.account-nav')
            </div>
            <div class="col-lg-9">
                <div class="page-content my-account__address">
                    <div class="row">
                        <div class="col-6">
                            <p class="notice">The following addresses will be used on the checkout page by default.</p>
                        </div>
                        <div class="col-6 text-end"> <!-- correction de text-right -> text-end -->
                            <a href="{{ route('user.addresses') }}" class="btn btn-sm btn-danger">Back</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-5">
                                <div class="card-header">
                                    <h5>Edit Address</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('user.addresses.update', $address->id) }}" method="POST">
                                        @csrf
                                        @method('PUT') <!-- Important : méthode HTTP PUT pour la mise à jour -->

                                        <div class="row">
                                            <!-- Full Name -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $address->name) }}">
                                                    <label for="name">Full Name *</label>
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Phone -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <input type="text" id="phone" class="form-control" name="phone" value="{{ old('phone', $address->phone) }}">
                                                    <label for="phone">Phone Number *</label>
                                                    @error('phone')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Zip -->
                                            <div class="col-md-4">
                                                <div class="form-floating my-3">
                                                    <input type="text" id="zip" class="form-control" name="zip" value="{{ old('zip', $address->zip) }}">
                                                    <label for="zip">Pincode *</label>
                                                    @error('zip')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- State -->
                                            <div class="col-md-4">
                                                <div class="form-floating my-3">
                                                    <input type="text" id="state" class="form-control" name="state" value="{{ old('state', $address->state) }}">
                                                    <label for="state">State *</label>
                                                    @error('state')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- City -->
                                            <div class="col-md-4">
                                                <div class="form-floating my-3">
                                                    <input type="text" id="city" class="form-control" name="city" value="{{ old('city', $address->city) }}">
                                                    <label for="city">Town / City *</label>
                                                    @error('city')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Country -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <input type="text" id="country" class="form-control" name="country" value="{{ old('country', $address->country) }}">
                                                    <label for="country">Country *</label>
                                                    @error('country')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Address -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <input type="text" id="address" class="form-control" name="address" value="{{ old('address', $address->address) }}">
                                                    <label for="address">House No, Building Name *</label>
                                                    @error('address')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Locality -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <input type="text" id="locality" class="form-control" name="locality" value="{{ old('locality', $address->locality) }}">
                                                    <label for="locality">Road Name, Area, Colony *</label>
                                                    @error('locality')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Landmark -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <input type="text" id="landmark" class="form-control" name="landmark" value="{{ old('landmark', $address->landmark) }}">
                                                    <label for="landmark">Landmark</label>
                                                    @error('landmark')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Address Type -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <select class="form-control" id="type" name="type">
                                                        <option value="">Select Type</option>
                                                        <option value="home" {{ old('type', $address->type) == 'home' ? 'selected' : '' }}>Home</option>
                                                        <option value="work" {{ old('type', $address->type) == 'work' ? 'selected' : '' }}>Work</option>
                                                        <option value="other" {{ old('type', $address->type) == 'other' ? 'selected' : '' }}>Other</option>
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
                                                    <input class="form-check-input" type="checkbox" id="isdefault" name="is_default" value="1" {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="isdefault">
                                                        Make as Default Address
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="col-md-12 text-end">
                                                <button type="submit" class="btn btn-success">Update</button>
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

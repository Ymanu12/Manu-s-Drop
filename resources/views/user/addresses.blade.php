@extends('layouts.app')

@section('content')
<main class="pt-90">
  <div class="mb-4 pb-4"></div>
  <section class="my-account container">
    <h2 class="page-title">Addresses</h2>
    <div class="row">
      <div class="col-lg-3">
        @include('user.account-nav')
      </div>
      <div class="col-lg-9">
        <div class="page-content my-account__address">
          <div class="row mb-3">
            <div class="col-6">
              <p class="notice">The following addresses will be used on the checkout page by default.</p>
            </div>
            <div class="col-6 text-end"> <!-- correction -->
              <a href="{{ route('user.addresses.create') }}" class="btn btn-sm btn-info">Add New</a>
            </div>
          </div>

          <div class="my-account__address-list row">
            <h5>Shipping Addresses</h5>

            @forelse ($addresses as $address)
              <div class="my-account__address-item col-md-6 mb-4">
                <div class="card h-100">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                      {{ $address->name }}
                      @if($address->is_default)
                        <i class="fa fa-check-circle text-success" title="Default Address"></i>
                      @endif
                    </h5>
                  </div>
                  <div class="card-body">
                    <div class="my-account__address-item__detail mb-3">
                      <p>{{ $address->address }}</p>
                      <p>{{ $address->city }}, {{ $address->state }} - {{ $address->zip }}</p>
                      <p>{{ $address->country }}</p>
                      <p>Mobile: {{ $address->phone }}</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                      <a href="{{ route('user.addresses.edit', $address->id) }}" class="btn btn-sm btn-primary">Edit</a>

                      <form action="{{ route('user.addresses.destroy', $address->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this address?')" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                      </form>

                      @unless($address->is_default)
                        <form action="{{ route('user.addresses.set_default', $address->id) }}" method="POST" onsubmit="return confirm('Set this address as default?')" style="display:inline;">
                          @csrf
                          <button type="submit" class="btn btn-sm btn-outline-primary">
                            Set as Default
                          </button>
                        </form>
                      @endunless
                    </div>
                  </div>
                </div>
              </div>
            @empty
              <div class="col-12">
                <div class="alert alert-info">
                  No addresses found. Please <a href="{{ route('user.addresses.create') }}">add a new address</a>.
                </div>
              </div>
            @endforelse

          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection

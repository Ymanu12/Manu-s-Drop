@extends('layouts.admin')

@section('content')
<div class="main-content-inner text-slate-900 dark:text-slate-100">
    <div class="main-content-wrap text-slate-900 dark:text-slate-100">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Coupons</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Coupons</div>
                </li>
            </ul>
        </div>

        <div class="wg-box rounded-2xl p-5 transition-colors admin-shell-card">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search admin-shell-search">
                        <fieldset class="name">
                            <input type="text" placeholder="Search here..." class="admin-shell-input px-3 py-2" name="name"
                                tabindex="2" value="" aria-required="true" required="">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <a class="tf-button style-1 w208" href="{{ route('admin.coupon.add') }}"><i
                        class="icon-plus"></i>Add new</a>
            </div>
            <div class="wg-table table-all-user">
                @if(session('success'))
                    <div class="alert alert-success text-center my-3">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="table-responsive admin-shell-table">
                    <table class="table table-striped table-bordered overflow-hidden rounded-xl">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Value</th>
                                <th>Cart Value</th>
                                <th>Expiry Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupons as $coupon)
                            <tr>
                                <td>{{$coupon->id}}</td>
                                <td>{{$coupon->code}}</td>
                                <td>{{ ucfirst($coupon->type) }}</td>
                                <td>{{ number_format((float) ($coupon->value ?? 0), 2) }}</td>
                                <td>${{ number_format((float) ($coupon->cart_value ?? 0), 2) }}</td>
                                <td>{{ $coupon->expiry_date }}</td>
                                <td>
                                    <div class="list-icon-function">
                                        <a href="{{ route('admin.coupon.edit', $coupon->id) }}">
                                            <div class="item edit">
                                                <i class="icon-edit-3"></i>
                                            </div>
                                        </a>
                                        <form action="{{ route('admin.coupon.destroy', $coupon->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="item text-danger delete admin-shell-danger-action">
                                                <i class="icon-trash-2"></i>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{ $coupons->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // Confirmation avec SweetAlert
        $('.delete').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            swal({
                title: "Are you sure?",
                text: "You want to delete this coupon?",
                icon: "warning",
                buttons: ["No", "Yes"],
                dangerMode: true
            }).then(function (willDelete) {
                if (willDelete) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

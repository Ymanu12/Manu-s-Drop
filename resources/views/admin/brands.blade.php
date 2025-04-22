@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">

            <!-- Header & Breadcrumbs -->
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Brands</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Brands</div>
                    </li>
                </ul>
            </div>

            <!-- Main Box -->
            <div class="wg-box">

                <!-- Filter + Add Button -->
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search" method="GET" action="{{ route('admin.brands') }}">
                            <fieldset class="name">
                                <input type="text" name="name" placeholder="Search here..." value="{{ request('name') }}" required>
                            </fieldset>
                            <div class="button-submit">
                                <button type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('admin.brand.add') }}">
                        <i class="icon-plus"></i> Add new
                    </a>
                </div>

                <!-- Message -->
                @if(Session::has('status'))
                    <p class="alert alert-success mt-3">{{ Session::get('status') }}</p>
                @endif

                <!-- Table -->
                <div class="wg-table table-all-user mt-4">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Products</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($brands as $brand)
                                    <tr>
                                        <td>{{ $brand->id }}</td>
                                        <td class="pname">
                                            <div class="image">
                                                @if($brand->image && Storage::disk('public')->exists($brand->image))
                                                    <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}" width="80">
                                                @else
                                                    <img src="{{ asset($brand->image) }}" alt="{{ $brand->name }}" width="80">
                                                @endif
                                            </div>
                                            <div class="name">
                                                <span class="body-title-2">{{ $brand->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $brand->slug }}</td>
                                        <td><a href="#" target="_blank">0</a></td>
                                        <td>
                                            <div class="list-icon-function">
                                                <a href="{{ route('admin.brand.edit', $brand->id) }}">
                                                    <div class="item edit">
                                                        <i class="icon-edit-3"></i>
                                                    </div>
                                                </a>
                                                <form action="{{ route('admin.brand.delete', $brand->id) }}" method="POST" class="d-inline-block delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="item text-danger delete" style="border: none; background: none;">
                                                        <i class="icon-trash-2"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No brands found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="divider"></div>
                    <div class="flex items-center justify-between flexwrap gap10 wgp-pagination">
                        {{ $brands->links('pagination::bootstrap-5') }}
                    </div>
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
                text: "You want to delete this brand?",
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

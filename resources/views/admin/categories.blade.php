@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">

            <!-- Header & Breadcrumbs -->
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Categories</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Categories</div>
                    </li>
                </ul>
            </div>

            <!-- Main Box -->
            <div class="wg-box">

                <!-- Filter + Add Button -->
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search" method="GET" action="">
                            <fieldset class="name">
                                <input type="text" name="name" placeholder="Search here..." value="{{ request('name') }}" required>
                            </fieldset>
                            <div class="button-submit">
                                <button type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('admin.category.add') }}">
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
                                @forelse($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td class="pname">
                                            <div class="image">
                                                @if($category->image && file_exists(public_path($category->image)))
                                                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" width="80">
                                                @else
                                                    <img src="{{ asset('images/default.jpg') }}" alt="Default" width="80">
                                                @endif
                                            </div>
                                            <div class="name">
                                                <span class="body-title-2">{{ $category->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $category->slug }}</td>
                                        <td><a href="#" target="_blank">0</a></td>
                                        <td>
                                            <div class="list-icon-function">
                                                <a href="{{ route('admin.category.edit', $category->id) }}">
                                                    <div class="item edit">
                                                        <i class="icon-edit-3"></i>
                                                    </div>
                                                </a>
                                                <form action="{{ route('admin.category.delete', $category->id) }}" method="POST" class="d-inline-block delete-form">
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
                                        <td colspan="5" class="text-center">No categories found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="divider"></div>
                    <div class="flex items-center justify-between flexwrap gap10 wgp-pagination">
                        {{ $categories->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('.delete').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            swal({
                title: "Are you sure?",
                text: "You want to delete this category?",
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

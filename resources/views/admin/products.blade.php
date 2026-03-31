@extends('layouts.admin') 

@section('content')
    <div class="main-content-inner text-slate-900 dark:text-slate-100">
        <div class="main-content-wrap text-slate-900 dark:text-slate-100">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>All Products</h3>
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
                        <div class="text-tiny">All Products</div>
                    </li>
                </ul>
            </div>

            <!-- Message -->
            @if(Session::has('status'))
                <p class="alert alert-success mt-3">{{ Session::get('status') }}</p>
            @endif

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
                    <a class="tf-button style-1 w208" href="{{route('admin.product.add')}}"><i
                            class="icon-plus"></i>Add new</a>
                </div>
                <div class="table-responsive admin-shell-table">
                    <table class="table table-striped table-bordered overflow-hidden rounded-xl">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>SalePrice</th>
                                <th>SKU</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Featured</th>
                                <th>Stock</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td>{{$product->id}}</td>
                                <td class="pname">
                                    <div class="image">
                                        <img src="{{asset('uploads/products')}}/{{$product->image}}" alt="{{$product->name}}" class="image">
                                    </div>
                                    <div class="name">
                                        <a href="#" class="body-title-2">{{$product->name}}</a>
                                        <div class="text-tiny mt-3">{{$product->slug}}</div>
                                    </div>
                                </td>
                                <td>{{$product->regular_price}}</td>
                                <td>{{$product->sale_price}}</td>
                                <td>{{$product->SKU}}</td>
                                <td>{{$product->category->name}}</td>
                                <td>{{$product->brand->name}}</td>
                                <td>{{$product->featured == 0 ? "No":"Yes"}}</td>
                                <td>{{$product->stock_status}}</td>
                                <td>{{$product->quantity}}</td>
                                <td>
                                    <div class="list-icon-function">
                                        <a href="#" target="_blank">
                                            <div class="item eye">
                                                <i class="icon-eye"></i>
                                            </div>
                                        </a>
                                        <a href="{{route('admin.product.edit', ['id'=>$product->id])}}">
                                            <div class="item edit">
                                                <i class="icon-edit-3"></i>
                                            </div>
                                        </a>
                                        <form action="{{route('admin.product.delete', $product->id)}}" method="POST">
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

                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $products->links('pagination::bootstrap-5') }}
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
                text: "You want to delete this product?",
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

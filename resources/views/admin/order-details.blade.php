@extends('layouts.admin')

@section('content')
<style>
    .table-transaction>tbody>tr:nth-of-type(odd) {
        --bs-table-accent-bg: #fff !important;
    }
</style>
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Order Details</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Order Items</div></li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <h5>Ordered Details</h5>
                </div>
                <a class="tf-button style-1 w208" href="{{route('admin.orders')}}">Back</a>
            </div>
            <div class="table-responsive">
                @if(Session::has('status'))
                    <p class="alert alert-success mt-3">{{ Session::get('status') }}</p>
                @endif
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Order no</th>
                            <th>{{$order->id}}</th>
                            <th>Mobile</th>
                            <th>{{$order->phone}}</th>
                            <th>Zip Code</th>
                            <th>{{$order->zip}}</th>
                        </tr>
                        <tr>
                            <th>Order Date</th>
                            <th>{{$order->created_at}}</th>
                            <th>Delivered Date</th>
                            <th>{{$order->delivered_date}}</th>
                            <th>Canceled Date</th>
                            <th>{{$order->canceled_date}}</th>
                        </tr>
                        <tr>
                            <th>Order Status</th>
                            <th colspan="5">
                                @if($order->status == 'delivered')
                                    <span class="badge bg-success">Delivered</span>
                                @elseif($order->status == 'canceled')
                                    <span class="badge bg-danger">Canceled</span>
                                @else
                                    <span class="badge bg-warning">Ordered</span>
                                @endif
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div><br><br><br><br><br><br>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <h5>Ordered Items</h5>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">SKU</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Brand</th>
                            <th class="text-center">Options</th>
                            <th class="text-center">Return Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($orderItems->isEmpty())
                            <tr>
                                <td colspan="9" class="text-center text-danger">Aucun article commandé.</td>
                            </tr>
                        @else
                            @foreach($orderItems as $item)
                                <tr>
                                    <td class="name">
                                        <div class="image">
                                            <img src="{{ asset('uploads/products/thumbnails/'.optional($item->product)->image) }}" alt="{{ optional($item->product)->name }}" class="image">
                                        </div>
                                        <div class="name">
                                            <a href="{{ route('shop.product.detail', ['product_slug' => optional($item->product)->slug]) }}" target="_blank" class="body-title-2">
                                                {{ optional($item->product)->name }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="text-center">${{ $item->price }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-center">{{ optional($item->product)->SKU ?? 'N/A' }}</td>
                                    <td class="text-center">{{ optional(optional($item->product)->category)->name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ optional(optional($item->product)->brand)->name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $item->option }}</td>
                                    <td class="text-center">{{ $item->status == 0 ? "No" : "Yes" }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.order.details', ['order_id' => $order->id]) }}">
                                            <div class="list-icon-function view-icon">
                                                <div class="item eye">
                                                    <i class="icon-eye"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                @if(method_exists($orderItems, 'links'))
                    {{ $orderItems->links('pagination::bootstrap-5') }}
                @endif
            </div>
        </div>

        <div class="wg-box mt-5">
            <h5>Shipping Address</h5>
            <div class="my-account__address-item col-md-6">
                <div class="my-account__address-item__detail">
                    <p>{{ $order->name }}</p>
                    <p>{{ $order->address }}</p>
                    <p>{{ $order->locality }}, DEF</p>
                    <p>{{ $order->city }}, {{ $order->country }}</p>
                    <p>{{ $order->landmark }}</p>
                    <p>{{ $order->zip }}</p>
                    <br>
                    <p>Mobile : {{ $order->phone }}</p>
                </div>
            </div>
        </div>

        <div class="wg-box mt-5">
            <h5>Update Order Status</h5>
            <form action="{{ route('update.order.status') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="order_id" value="{{ $order->id }}" />
                <div class="row">
                    <div class="col-md-3">
                        <div class="select">
                            <select id="order_status" name="order_status">
                                <option value="ordered" {{ $order->status == 'ordered' ? 'selected' : '' }}>ordered</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>delivered</option>
                                <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>canceled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary tf-button w208">Update Status</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
  <style>
    .pt-90 {
      padding-top: 90px !important;
    }

    .pr-6px {
      padding-right: 6px;
      text-transform: uppercase;
    }

    .my-account .page-title {
      font-size: 1.5rem;
      font-weight: 700;
      text-transform: uppercase;
      margin-bottom: 40px;
      border-bottom: 1px solid;
      padding-bottom: 13px;
    }

    .my-account .wg-box {
      display: -webkit-box;
      display: -moz-box;
      display: -ms-flexbox;
      display: -webkit-flex;
      display: flex;
      padding: 24px;
      flex-direction: column;
      gap: 24px;
      border-radius: 12px;
      background: var(--White);
      box-shadow: 0px 4px 24px 2px rgba(20, 25, 38, 0.05);
    }

    .bg-success {
      background-color: #40c710 !important;
    }

    .bg-danger {
      background-color: #f44032 !important;
    }

    .bg-warning {
      background-color: #f5d700 !important;
      color: #000;
    }

    .table-transaction>tbody>tr:nth-of-type(odd) {
      --bs-table-accent-bg: #fff !important;

    }

    .table-transaction th,
    .table-transaction td {
      padding: 0.625rem 1.5rem .25rem !important;
      color: #000 !important;
    }

    .table> :not(caption)>tr>th {
      padding: 0.625rem 1.5rem .25rem !important;
      background-color: #6a6e51 !important;
    }

    .table-bordered>:not(caption)>*>* {
      border-width: inherit;
      line-height: 32px;
      font-size: 14px;
      border: 1px solid #e1e1e1;
      vertical-align: middle;
    }

    .table-striped .image {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 50px;
      height: 50px;
      flex-shrink: 0;
      border-radius: 10px;
      overflow: hidden;
    }

    .table-striped td:nth-child(1) {
      min-width: 250px;
      padding-bottom: 7px;
    }

    .pname {
      display: flex;
      gap: 13px;
    }

    .table-bordered> :not(caption)>tr>th,
    .table-bordered> :not(caption)>tr>td {
      border-width: 1px 1px;
      border-color: #6a6e51;
    }
  </style>
<main class="pt-90" style="padding-top: 0px;">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <h2 class="page-title">Orders</h2>
        <div class="row">
            <div class="col-lg-2">
                @include('user.account-nav')           
            </div>

            <div class="col-lg-10">
            <div class="wg-box">
                    <div class="flex items-center justify-between gap10 flex-wrap">
                        
                        <div class="row">
                            <div class="col-6">
                                <div class="wg-filter flex-grow">
                                    <h5>Ordered Details</h5>
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <a class="btn btn-sm btn-danger" href="{{route('user.orders')}}">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
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
                </div><br><br>

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
                                                <a href="{{ route('user.order.details', ['order_id' => $order->id]) }}">
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

                <div class="wg-box mt-5 table-responsive">
                    <h5>Transactions</h5>
                    <table class="table table-striped table-bordered table-transaction">
                        <tbody>
                            <tr>
                                <th>Subtotal</th>
                                <td>${{ $order->subtotal }}</td>
                                <th>Tax</th>
                                <td>${{ $order->tax }}</td>
                                <th>Discount</th>
                                <td>${{ $order->discount }}</td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <td>${{ $order->total }}</td>
                                <th>Payment Mode</th>
                                <td>{{ $transaction ? $transaction->mode : 'N/A' }}</td>
                                <th>Status</th>
                                <td>
                                    @if($transaction)
                                        @if($transaction->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($transaction->status == 'declined')
                                            <span class="badge bg-danger">Declined</span>
                                        @elseif($transaction->status == 'refused')
                                            <span class="badge bg-secondary">Refused</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">No transaction</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Order Date</th>
                                <td>{{ $order->created_at }}</td>
                                <th>Delivered Date</th>
                                <td>{{ $order->delivered_date }}</td>
                                <th>Canceled Date</th>
                                <td>{{ $order->canceled_date }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="wg-box mt-5 text-right">
                    <form action="{{route('user.order.cancel')}}" method="post">
                        @csrf 
                        @method('PUT')
                        <input type="hidden" name="order_id" value="$order->id">
                        <button type="button" class="btn btn-danger cancel-order">Cancel Order</button>
                    </form>
                </div>
            </div>
            
        </div>
    </section>
</main>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('.cancel-order').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            swal({
                title: "Are you sure?",
                text: "You want to cancel this order?",
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


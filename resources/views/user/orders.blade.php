@extends('layouts.app')

@section('content')
<style>
    .table> :not(caption)>tr>th {
      padding: 0.625rem 1.5rem .625rem !important;
      background-color: var(--theme-surface-strong) !important;
    }

    .table>tr>td {
      padding: 0.625rem 1.5rem .625rem !important;
    }

    .table-bordered> :not(caption)>tr>th,
    .table-bordered> :not(caption)>tr>td {
      border-width: 1px 1px;
      border-color: var(--theme-border);
    }

    .table> :not(caption)>tr>td {
      padding: .8rem 1rem !important;
    }
    .bg-success {
      background-color: rgba(34, 197, 94, .18) !important; color: rgb(21, 128, 61) !important; border: 1px solid rgba(34, 197, 94, .28);
    }

    .bg-danger {
      background-color: rgba(239, 68, 68, .16) !important; color: rgb(185, 28, 28) !important; border: 1px solid rgba(239, 68, 68, .28);
    }

    .bg-warning {
      background-color: rgba(245, 158, 11, .18) !important;
      color: rgb(146, 64, 14) !important;
      border: 1px solid rgba(245, 158, 11, .28);
    }

    .dark .bg-success {
      color: rgb(134, 239, 172) !important;
    }

    .dark .bg-danger {
      color: rgb(252, 165, 165) !important;
    }

    .dark .bg-warning {
      color: rgb(253, 230, 138) !important;
    }

    .table thead th,
    .table tbody td,
    .table tbody td a,
    .wg-box h5,
    .wg-box .body-title-2 {
      color: var(--theme-text) !important;
    }

    .dark .table thead th {
      background-color: var(--theme-surface-strong) !important;
      color: var(--theme-text) !important;
      border-color: var(--theme-border) !important;
      box-shadow: inset 0 0 0 9999px var(--theme-surface-strong) !important;
    }

    .dark .table tbody tr,
    .dark .table-striped > tbody > tr:nth-of-type(odd),
    .dark .table-striped > tbody > tr:nth-of-type(even) {
      background: var(--theme-surface) !important;
      color: var(--theme-text) !important;
    }

    .dark .table tbody td,
    .dark .table-striped > tbody > tr > * {
      background-color: var(--theme-surface) !important;
      color: var(--theme-text) !important;
      border-color: var(--theme-border) !important;
      box-shadow: inset 0 0 0 9999px var(--theme-surface) !important;
    }

    .dark .table tbody td a,
    .dark .table tbody td .fa,
    .dark .table tbody td .item.eye {
      color: var(--theme-text) !important;
    }

    .table tbody tr {
      background: var(--theme-surface);
    }

    .dark .table-striped > tbody > tr:nth-of-type(odd),
    .dark .table-striped > tbody > tr:nth-of-type(even) {
      color: var(--theme-text);
    }
  </style>
<main class="pt-90 text-slate-900 dark:text-slate-100" style="padding-top: 0px;">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container md:rounded-2xl md:border md:border-slate-200/70 bg-white/80 md:px-4 md:py-4 md:shadow-sm transition-colors dark:md:border-slate-800 dark:bg-slate-900/80">
        <h2 class="page-title">Orders</h2>
        <div class="row">
            <div class="col-lg-2">
                @include('user.account-nav')           
            </div>

            <div class="col-lg-10">
                <div class="wg-table table-all-user">
                    <div class="table-responsive md:rounded-xl md:border md:border-slate-200/70 bg-white/70 md:shadow-sm dark:md:border-slate-800 dark:bg-slate-950/60">
                        <table class="table table-striped table-bordered overflow-hidden rounded-xl">
                            <thead>
                                <tr>
                                    <th style="width: 80px">OrderNo</th>
                                    <th>Name</th>
                                    <th class="text-center">Phone</th>
                                    <th class="text-center">Subtotal</th>
                                    <th class="text-center">Tax</th>
                                    <th class="text-center">Total</th>
                                    
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Order Date</th>
                                    <th class="text-center">Items</th>
                                    <th class="text-center">Delivered On</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="text-center">{{$order->id}}</td>  
                                        <td class="text-center">{{$order->name}}</td>
                                        <td class="text-center">{{$order->phone}}</td>
                                        <td class="text-center">${{$order->subtotal}}</td>
                                        <td class="text-center">${{$order->tax}}</td>
                                        <td class="text-center">${{$order->total}}</td>
                                        <td class="text-center">
                                            @if($order->status == 'delivered')
                                                <span class="badge bg-success">Delivered</span>
                                            @elseif($order->status == 'canceled')
                                                <span class="badge bg-danger">Canceled</span>
                                            @else
                                                <span class="badge bg-warning">Ordered</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{$order->created_at}}</td>
                                        <td class="text-center">{{$order->orderItems->count()}}</td>
                                        <td class="text-center">{{$order->delivered_date}}</td>
                                        <td class="text-center">
                                            <a href="{{route('user.order.details',['order_id'=>$order->id])}}">
                                            <div class="list-icon-function view-icon">
                                                <div class="item eye">
                                                    <i class="fa fa-eye"></i>
                                                </div>                                        
                                            </div>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach                               
                            </tbody>
                        </table>                
                    </div>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">                
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
            
        </div>
    </section>
</main>
@endsection

@extends('layouts.admin')

@push('styles')
<style>
    .admin-kpi-grid,
    .admin-panel-grid {
        display: grid;
        gap: 20px;
    }

    .admin-kpi-grid {
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        margin-bottom: 20px;
    }

    .admin-panel-grid {
        grid-template-columns: 1.4fr .8fr;
        margin-bottom: 30px;
    }

    .admin-kpi-card {
        background: linear-gradient(135deg, #ffffff 0%, #f7f9fc 100%);
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 22px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
    }

    .admin-kpi-label {
        color: #6b7280;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-bottom: 8px;
    }

    .admin-kpi-value {
        color: #111827;
        font-size: 28px;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 6px;
    }

    .admin-kpi-note {
        color: #4b5563;
        font-size: 13px;
    }

    .admin-section-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
    }

    .admin-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .admin-list-item {
        border: 1px solid #eef2f7;
        border-radius: 14px;
        padding: 14px 16px;
        background: #fff;
    }

    .admin-list-item strong {
        color: #111827;
    }

    .admin-list-meta {
        color: #6b7280;
        font-size: 12px;
        margin-top: 4px;
    }

    .admin-badge-soft {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 600;
        background: #eef2ff;
        color: #3730a3;
    }

    .admin-empty {
        color: #6b7280;
        padding: 12px 0;
    }

    @media (max-width: 991px) {
        .admin-panel-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="admin-kpi-grid">
            <div class="admin-kpi-card">
                <div class="admin-kpi-label">Today Revenue</div>
                <div class="admin-kpi-value">${{ number_format((float) ($todayRevenue ?? 0), 2) }}</div>
                <div class="admin-kpi-note">{{ $todayOrdersCount ?? 0 }} order(s) placed today</div>
            </div>
            <div class="admin-kpi-card">
                <div class="admin-kpi-label">Orders To Process</div>
                <div class="admin-kpi-value">{{ $pendingOrdersCount ?? 0 }}</div>
                <div class="admin-kpi-note">Orders currently waiting for fulfillment</div>
            </div>
            <div class="admin-kpi-card">
                <div class="admin-kpi-label">This Week Revenue</div>
                <div class="admin-kpi-value">${{ number_format((float) ($weeklyRevenue ?? 0), 2) }}</div>
                <div class="admin-kpi-note">Average order value: ${{ number_format((float) ($averageOrderValue ?? 0), 2) }}</div>
            </div>
            <div class="admin-kpi-card">
                <div class="admin-kpi-label">Customer Signals</div>
                <div class="admin-kpi-value">{{ ($newCustomersThisMonth ?? 0) + ($newMessagesCount ?? 0) }}</div>
                <div class="admin-kpi-note">{{ $newCustomersThisMonth ?? 0 }} new customer(s) this month and {{ $newMessagesCount ?? 0 }} recent message(s)</div>
            </div>
        </div>

        <div class="admin-panel-grid">
            <div class="wg-box">
                <div class="admin-section-title">
                    <div>
                        <h5>Operations Snapshot</h5>
                        <div class="text-tiny">Real-time overview of what needs attention first</div>
                    </div>
                </div>
                <div class="admin-kpi-grid" style="margin-bottom:0;">
                    <div class="admin-kpi-card">
                        <div class="admin-kpi-label">Pending Orders</div>
                        <div class="admin-kpi-value">{{ $dashboard->TotalOrdered ?? 0 }}</div>
                        <div class="admin-kpi-note">Pending value: ${{ number_format((float) ($dashboard->TotalOrderedAmount ?? 0), 2) }}</div>
                    </div>
                    <div class="admin-kpi-card">
                        <div class="admin-kpi-label">Delivered Today</div>
                        <div class="admin-kpi-value">{{ $todayDeliveredCount ?? 0 }}</div>
                        <div class="admin-kpi-note">Delivered orders updated today</div>
                    </div>
                    <div class="admin-kpi-card">
                        <div class="admin-kpi-label">Low Stock Items</div>
                        <div class="admin-kpi-value">{{ $lowStockProducts->count() }}</div>
                        <div class="admin-kpi-note">Products with 5 units or fewer left</div>
                    </div>
                    <div class="admin-kpi-card">
                        <div class="admin-kpi-label">Canceled Orders</div>
                        <div class="admin-kpi-value">{{ $dashboard->TotalCanceled ?? 0 }}</div>
                        <div class="admin-kpi-note">Canceled value: ${{ number_format((float) ($dashboard->TotalCanceledAmount ?? 0), 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="wg-box">
                <div class="admin-section-title">
                    <div>
                        <h5>Needs Attention</h5>
                        <div class="text-tiny">Fast triage for stock and customer messages</div>
                    </div>
                </div>
                <div class="admin-list">
                    @forelse($lowStockProducts as $product)
                        <div class="admin-list-item">
                            <strong>{{ $product->name }}</strong>
                            <div class="admin-list-meta">Only {{ (int) $product->quantity }} item(s) left</div>
                            <div class="admin-list-meta">{{ ucfirst((string) ($product->stock_status ?? 'instock')) }} - updated {{ optional($product->updated_at)->diffForHumans() }}</div>
                        </div>
                    @empty
                        <div class="admin-empty">No low-stock products right now.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="tf-section-2 mb-30">
            <div class="flex gap20 flex-wrap-mobile">
                <div class="w-half">
                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-shopping-bag"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Total Orders</div>
                                    <h4>{{ $dashboard->Total ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-dollar-sign"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Total Amount</div>
                                    <h4>${{ number_format((float) ($dashboard->TotalAmount ?? 0), 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-shopping-bag"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Pending Orders</div>
                                    <h4>{{ $dashboard->TotalOrdered ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wg-chart-default">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-dollar-sign"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Pending Orders Amount</div>
                                    <h4>${{ number_format((float) ($dashboard->TotalOrderedAmount ?? 0), 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-half">
                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-shopping-bag"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Delivered Orders</div>
                                    <h4>{{ $dashboard->TotalDelivered ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-dollar-sign"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Delivered Orders Amount</div>
                                    <h4>${{ number_format((float) ($dashboard->TotalDeliveredAmount ?? 0), 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-shopping-bag"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Canceled Orders</div>
                                    <h4>{{ $dashboard->TotalCanceled ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wg-chart-default">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-dollar-sign"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Canceled Orders Amount</div>
                                    <h4>${{ number_format((float) ($dashboard->TotalCanceledAmount ?? 0), 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between">
                    <h5>Monthly Revenue</h5>
                </div>

                <div class="flex flex-wrap gap40">
                    <div>
                        <div class="mb-2">
                            <div class="block-legend">
                                <div class="dot t1"></div>
                                <div class="text-tiny">Total</div>
                            </div>
                        </div>
                        <div class="flex items-center gap10">
                            <h4>${{ number_format((float) ($TotalAmount ?? 0), 0, '.', ',') }}</h4>
                        </div>
                    </div>

                    <div>
                        <div class="mb-2">
                            <div class="block-legend">
                                <div class="dot t2"></div>
                                <div class="text-tiny">Pending</div>
                            </div>
                        </div>
                        <div class="flex items-center gap10">
                            <h4>${{ number_format((float) ($TotalOrderedAmount ?? 0), 0, '.', ',') }}</h4>
                        </div>
                    </div>

                    <div>
                        <div class="mb-2">
                            <div class="block-legend">
                                <div class="dot t3"></div>
                                <div class="text-tiny">Delivered</div>
                            </div>
                        </div>
                        <div class="flex items-center gap10">
                            <h4>${{ number_format((float) ($TotalDeliveredAmount ?? 0), 0, '.', ',') }}</h4>
                        </div>
                    </div>

                    <div>
                        <div class="mb-2">
                            <div class="block-legend">
                                <div class="dot t4"></div>
                                <div class="text-tiny">Canceled</div>
                            </div>
                        </div>
                        <div class="flex items-center gap10">
                            <h4>${{ number_format((float) ($TotalCanceledAmount ?? 0), 0, '.', ',') }}</h4>
                        </div>
                    </div>
                </div>

                <div id="line-chart-8"></div>
            </div>
        </div>

        <div class="tf-section mb-30">
            <div class="admin-panel-grid">
                <div class="wg-box">
                    <div class="admin-section-title">
                        <div>
                            <h5>Recent Customer Messages</h5>
                            <div class="text-tiny">Latest contact requests sent from the storefront</div>
                        </div>
                        <a href="{{ route('admin.contacts') }}" class="admin-badge-soft">Open inbox</a>
                    </div>
                    <div class="admin-list">
                        @forelse($recentMessages as $message)
                            <div class="admin-list-item">
                                <strong>{{ $message->name }}</strong>
                                <div class="admin-list-meta">{{ $message->email }} - {{ $message->phone }}</div>
                                <div class="admin-list-meta">{{ \Illuminate\Support\Str::limit($message->comment, 120) }}</div>
                                <div class="admin-list-meta">{{ optional($message->created_at)->diffForHumans() }}</div>
                            </div>
                        @empty
                            <div class="admin-empty">No customer messages yet.</div>
                        @endforelse
                    </div>
                </div>

                <div class="wg-box">
                    <div class="admin-section-title">
                        <div>
                            <h5>Admin Activity</h5>
                            <div class="text-tiny">Recent actions performed in the back office</div>
                        </div>
                    </div>
                    <div class="admin-list">
                        @forelse($recentActivities as $activity)
                            <div class="admin-list-item">
                                <strong>{{ ucwords(str_replace('.', ' ', (string) $activity->action)) }}</strong>
                                <div class="admin-list-meta">{{ $activity->user->name ?? 'System' }} - {{ $activity->route ?? 'No route' }}</div>
                                <div class="admin-list-meta">Target: {{ class_basename((string) ($activity->target_type ?? 'n/a')) }}{{ $activity->target_id ? ' #' . $activity->target_id : '' }}</div>
                                <div class="admin-list-meta">{{ optional($activity->created_at)->diffForHumans() }}</div>
                            </div>
                        @empty
                            <div class="admin-empty">No admin activity logged yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between">
                    <h5>Recent orders</h5>
                    <div class="dropdown default">
                        <a class="btn btn-secondary dropdown-toggle" href="{{ route('admin.orders') }}">
                            <span class="view-all">View all</span>
                        </a>
                    </div>
                </div>
                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:70px">OrderNo</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Phone</th>
                                    <th class="text-center">Subtotal</th>
                                    <th class="text-center">Tax</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Order Date</th>
                                    <th class="text-center">Total Items</th>
                                    <th class="text-center">Delivered On</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="text-center">{{ $order->id }}</td>
                                        <td class="text-center">{{ $order->name }}</td>
                                        <td class="text-center">{{ $order->phone }}</td>
                                        <td class="text-center">${{ number_format((float) ($order->subtotal ?? 0), 2) }}</td>
                                        <td class="text-center">${{ number_format((float) ($order->tax ?? 0), 2) }}</td>
                                        <td class="text-center">${{ number_format((float) ($order->total ?? 0), 2) }}</td>
                                        <td class="text-center">
                                            @if($order->status == 'delivered')
                                                <span class="badge bg-success">Delivered</span>
                                            @elseif($order->status == 'canceled')
                                                <span class="badge bg-danger">Canceled</span>
                                            @else
                                                <span class="badge bg-warning">Ordered</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ optional($order->created_at)->format('Y-m-d H:i') }}</td>
                                        <td class="text-center">{{ $order->orderItems->count() }}</td>
                                        <td class="text-center">{{ $order->delivered_date ?? '-' }}</td>
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function ($) {
        var tfLineChart = (function () {
            var chartBar = function () {
                var options = {
                    series: [{
                        name: 'Total',
                        data: {!! json_encode($AmountSeries) !!}
                    }, {
                        name: 'Pending',
                        data: {!! json_encode($OrderedSeries) !!}
                    }, {
                        name: 'Delivered',
                        data: {!! json_encode($DeliveredSeries) !!}
                    }, {
                        name: 'Canceled',
                        data: {!! json_encode($CanceledSeries) !!}
                    }],
                    chart: {
                        type: 'bar',
                        height: 325,
                        toolbar: {
                            show: false,
                        },
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '10px',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    legend: {
                        show: false,
                    },
                    colors: ['#2377FC', '#FFA500', '#078407', '#FF0000'],
                    stroke: {
                        show: false,
                    },
                    xaxis: {
                        labels: {
                            style: {
                                colors: '#212529',
                            },
                        },
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    },
                    yaxis: {
                        show: false,
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return "$ " + val + "";
                            }
                        }
                    }
                };

                chart = new ApexCharts(
                    document.querySelector("#line-chart-8"),
                    options
                );

                if ($("#line-chart-8").length > 0) {
                    chart.render();
                }
            };

            return {
                init: function () {},
                load: function () {
                    chartBar();
                },
                resize: function () {},
            };
        })();

        jQuery(window).on("load", function () {
            tfLineChart.load();
        });
    })(jQuery);
</script>
@endpush

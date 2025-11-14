@extends('backend.master')

@section('title', __('app.dashboard'))

@section('content')
<section class="content">
    @can('dashboard_view')
    <div class="container-fluid">
        {{-- ================================================== --}}
        {{-- ⬇️ هذا هو الجزء الذي كان مفقوداً وتمت إعادته ⬇️ --}}
        {{-- ================================================== --}}
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('app.sale_subtotal') }}</span>
                        <span class="info-box-number">
                            {{ currency()->symbol ?? '' }} {{ number_format($sub_total, 2, '.', ',') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('app.sale_discount') }}</span>
                        <span class="info-box-number">{{ currency()->symbol ?? '' }} {{ number_format($discount, 2, '.', ',') }}</span>
                    </div>
                </div>
            </div>

            <div class="clearfix hidden-md-up"></div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('app.sale') }}</span>
                        <span class="info-box-number">{{ currency()->symbol ?? '' }} {{ number_format($total, 2, '.', ',') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('app.sale_due') }}</span>
                        <span class="info-box-number">{{ currency()->symbol ?? '' }} {{ number_format($due, 2, '.', ',') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $total_customer }}</h3>
                        <p>{{ __('app.customers') }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{ route('backend.admin.customers.index') }}" class="small-box-footer">
                        {{ __('app.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $total_product }}</h3>
                        <p>{{ __('app.products') }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{ route('backend.admin.products.index') }}" class="small-box-footer">
                        {{ __('app.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $total_order }}</h3>
                        <p>{{ __('app.sale') }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{ route('backend.admin.orders.index') }}" class="small-box-footer">
                        {{ __('app.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $total_sale_item }}</h3>
                        <p>{{ __('app.sale_item') }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{ route('backend.admin.orders.index') }}" class="small-box-footer">
                        {{ __('app.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
        {{-- ================================================== --}}
        {{-- ⬆️ نهاية الجزء الذي كان مفقوداً ⬆️ --}}
        {{-- ================================================== --}}

        {{-- هذا الجزء الخاص بالمخططات البيانية --}}
        <div class="row">
            <div class="col-md-6"> {{-- تم التعديل إلى col-md-6 ليتناسب مع الشاشات المختلفة --}}
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('app.daily_total_sales') }} <small>{{ $dateRange }}</small></h5>
                        <div class="input-group w-auto">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" id="reservation" style="width: 180px;">
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="dailySaleLineChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6"> {{-- تم التعديل إلى col-md-6 ليتناسب مع الشاشات المختلفة --}}
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('app.monthly_total_sales') }} <small>{{ $currentYear }}</small></h5>
                    </div>
                    <div class="card-body">
                        <canvas id="barChartYear"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
</section>
@endsection

@push('script')
{{-- تأكد من أن القالب الخاص بك يقوم بتحميل هذه المكتبات --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script>
$(function( ) {
    // --- تهيئة منتقي التواريخ (Date Range Picker) ---
    $('#reservation').daterangepicker({
        opens: 'left'
    }, function(start, end, label) {
        const daterange = start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD');
        window.location.href = "{{ route('backend.admin.dashboard') }}?daterange=" + daterange;
    });

    // --- تهيئة بيانات المخططات من الـ Controller ---
    const dailyLabels = @json($dates);
    const dailyData = @json($totalAmounts);
    const monthlyLabels = @json($months);
    const monthlyData = @json($totalAmountMonth);

    // --- مخطط المبيعات اليومية ---
    const dailyCtx = document.getElementById('dailySaleLineChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'إجمالي المبيعات',
                data: dailyData,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });

    // --- مخطط المبيعات الشهرية ---
    const monthlyCtx = document.getElementById('barChartYear').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'إجمالي المبيعات',
                data: monthlyData,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
});
</script>
@endpush

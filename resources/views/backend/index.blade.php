@extends('backend.master')

@section('title', __('app.dashboard'))

@section('content')
<section class="content">
    @can('dashboard_view')
    <div class="container-fluid">

        {{-- قسم الفلاتر الجديد --}}
        <div class="card">
            <div class="card-body">
                <form action="{{ route('backend.admin.dashboard') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label for="filter">عرض إحصائيات:</label>
                            <select name="filter" id="filter" class="form-control">
                                <option value="today" {{ $filterType == 'today' ? 'selected' : '' }}>اليوم</option>
                                <option value="last_5_days" {{ $filterType == 'last_5_days' ? 'selected' : '' }}>آخر 5 أيام</option>
                                <option value="last_7_days" {{ $filterType == 'last_7_days' ? 'selected' : '' }}>آخر 7 أيام</option>
                                <option value="last_month" {{ $filterType == 'last_month' ? 'selected' : '' }}>آخر شهر</option>
                                <option value="all_time" {{ $filterType == 'all_time' ? 'selected' : '' }}>كل الوقت</option>
                                <option value="custom" {{ $filterType == 'custom' ? 'selected' : '' }}>نطاق مخصص</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="custom-date-range" style="{{ $filterType == 'custom' ? '' : 'display:none;' }}">
                            <div class="row">
                                <div class="col">
                                    <label for="start_date">من تاريخ:</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
                                </div>
                                <div class="col">
                                    <label for="end_date">إلى تاريخ:</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary mt-3 mt-md-0">تطبيق</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- المربعات العلوية للإحصائيات --}}
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

        {{-- المربعات الصغيرة للإحصائيات --}}
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $total_customer }}</h3>
                        <p>{{ __('app.customers') }}</p>
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
                    <a href="{{ route('backend.admin.orders.index') }}" class="small-box-footer">
                        {{ __('app.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- المخططات البيانية --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('app.daily_total_sales') }} <small>({{ $dateRange }})</small></h5>
                    </div>
                    <div class="card-body">
                        <canvas id="dailySaleLineChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('app.monthly_total_sales') }} <small>({{ $currentYear }})</small></h5>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(function( ) {
    // --- كود التحكم في إظهار وإخفاء حقول التاريخ ---
    $('#filter').on('change', function() {
        if (this.value === 'custom') {
            $('#custom-date-range').show();
        } else {
            $('#custom-date-range').hide();
        }
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

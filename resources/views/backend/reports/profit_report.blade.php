@extends('backend.master')

@section('title', 'تقرير الأرباح')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">تقرير الأرباح</h3>
    </div>
    <div class="card-body">
        {{-- قسم الفلاتر --}}
        <form action="{{ route('backend.admin.profit.report') }}" method="GET" class="mb-4">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label for="filter">تصفية حسب:</label>
                    <select name="filter" id="filter" class="form-control">
                        <option value="last_5_days" {{ $filterType == 'last_5_days' ? 'selected' : '' }}>آخر 5 أيام</option>
                        <option value="last_7_days" {{ $filterType == 'last_7_days' ? 'selected' : '' }}>آخر 7 أيام</option>
                        <option value="last_month" {{ $filterType == 'last_month' ? 'selected' : '' }}>آخر شهر</option>
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
                    <button type="submit" class="btn btn-primary">تطبيق</button>
                </div>
            </div>
        </form>

        {{-- قسم الإجماليات --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ number_format($totalSold, 2) }}</h3>
                        <p>إجمالي المبيعات</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ number_format($totalPurchaseCost, 2) }}</h3>
                        <p>إجمالي تكلفة الشراء</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ number_format($totalProfit, 2) }}</h3>
                        <p>صافي الربح</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- جدول التفاصيل --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المنتج</th>
                        <th>الكمية المباعة</th>
                        <th>سعر الشراء للقطعة</th>
                        <th>سعر البيع للقطعة</th>
                        <th>إجمالي الربح</th>
                        <th>تاريخ البيع</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detailedProfits as $item)
                        @php
                            $profitPerItem = ($item->price - $item->purchase_price) * $item->quantity;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->product->name ?? 'منتج محذوف' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->purchase_price, 2) }}</td>
                            <td>{{ number_format($item->price, 2) }}</td>
                            <td>{{ number_format($profitPerItem, 2) }}</td>
                            <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">لا توجد بيانات لعرضها في هذا النطاق الزمني.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- روابط الترقيم --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $detailedProfits->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    // كود لإظهار وإخفاء حقول التاريخ المخصص
    document.getElementById('filter').addEventListener('change', function() {
        if (this.value === 'custom') {
            document.getElementById('custom-date-range').style.display = 'block';
        } else {
            document.getElementById('custom-date-range').style.display = 'none';
        }
    });
</script>
@endpush

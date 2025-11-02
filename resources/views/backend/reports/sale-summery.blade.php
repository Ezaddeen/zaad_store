@extends('backend.master')

{{-- ⬅️ تعريب العنوان: 'Sale Report' -> 'تقرير المبيعات' (الاسم العام للصفحة) --}}
@section('title', __('reports.sale_report'))

@section('content')
<div class="card">
  <div class="mt-n5 mb-3 d-flex justify-content-end">
    <div class="form-group">
      <div class="input-group">
        {{-- ⬅️ تعريب زر فلترة التاريخ --}}
        <button type="button" class="btn btn-default float-right" id="daterange-btn">
          <i class="far fa-calendar-alt"></i> {{ __('general.filter_by_date') }}
          <i class="fas fa-caret-down"></i>
        </button>
      </div>
    </div>
  </div>
  <div class="card-body p-2 p-md-4 pt-0">
    <div class="row g-4">
      <div class="col-md-12">
        <div class="card-body p-0">
          <section class="invoice">
            <div class="row invoice-info">
              <div class="col-sm-4">
              </div>
              <div class="col-sm-4">
                <address>
                  {{-- ⬅️ تعريب عنوان الملخص: 'Sale Summery' -> 'ملخص المبيعات' --}}
                  <strong>{{ __('reports.sale_summary') }} ({{$start_date}} - {{$end_date}})</strong><br>
                </address>
              </div>
              <div class="col-sm-2">
              </div>
              </div>
            <div class="row justify-content-center">
              <div class="col-10">
                <div class="table-responsive">
                  <table class="table">
                    <tr>
                      {{-- ⬅️ تعريب: 'Subtotal' -> 'المجموع الفرعي' --}}
                      <th style="width:50%">{{ __('pos.sub_total') }}:</th>
                      <td class="text-right">{{currency()->symbol??''}} {{number_format($sub_total,2)}}</td>
                    </tr>
                    <tr>
                      {{-- ⬅️ تعريب: 'Total Discount' -> 'إجمالي الخصم' --}}
                      <th>{{ __('reports.total_discount') }}:</th>
                      <td class="text-right">{{currency()->symbol??''}} {{number_format($discount,2)}}</td>
                    </tr>
                    <tr>
                      {{-- ⬅️ تعريب: 'Total Sold' -> 'إجمالي المبيعات' --}}
                      <th>{{ __('reports.total_sold') }}:</th>
                      <td class="text-right">{{currency()->symbol??''}} {{number_format($total,2)}}</td>
                    </tr>
                    <tr>
                      {{-- ⬅️ تعريب: 'Customer Paid' -> 'المدفوع من العملاء' --}}
                      <th>{{ __('reports.customer_paid') }}:</th>
                      <td class="text-right">{{currency()->symbol??''}} {{number_format($paid,2)}}</td>
                    </tr>
                    <tr>
                      {{-- ⬅️ تعريب: 'Customer Due' -> 'المستحق على العملاء' --}}
                      <th>{{ __('reports.customer_due') }}:</th>
                      <td class="text-right">{{currency()->symbol??''}} {{number_format($due,2)}}</td>
                    </tr>
                  </table>
                </div>
              </div>
              </div>
            <div class="row no-print">
              <div class="col-12">
                {{-- ⬅️ تعريب زر الطباعة: 'Print' --}}
                <button type="button" onclick="window.print()" class="btn btn-success float-right"><i class="fas fa-print"></i> {{ __('reports.print') }}
                </button>
              </div>
            </div>
            </section>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('style')
<style>
  .invoice {
    border: none !important;
  }
</style>
@endpush
@push('script')
<script>
  $(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const startDate = urlParams.get('start_date') || moment().subtract(29, 'days').format('YYYY-MM-DD'); // Default to last 30 days if not present
    const endDate = urlParams.get('end_date') || moment().format('YYYY-MM-DD'); // Default to today if not present

    // تعريب خيارات daterangepicker
    $('#daterange-btn').daterangepicker({
        ranges: {
          '{{ __('reports.today') }}': [moment(), moment()],
          '{{ __('reports.yesterday') }}': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          '{{ __('reports.last_7_days') }}': [moment().subtract(6, 'days'), moment()],
          '{{ __('reports.last_30_days') }}': [moment().subtract(29, 'days'), moment()],
          '{{ __('reports.this_month') }}': [moment().startOf('month'), moment().endOf('month')],
          '{{ __('reports.last_month') }}': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment(startDate, "YYYY-MM-DD"),
        endDate: moment(endDate, "YYYY-MM-DD")
      },
      function(start, end) {
        // ... (توجيه مع تمرير التاريخ)
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        window.location.href = '{{ route("backend.admin.sale.summery") }}?start_date=' + start.format('YYYY-MM-DD') + '&end_date=' + end.format('YYYY-MM-DD');
      }
    )
  })
</script>
@endpush


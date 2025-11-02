@extends('backend.master')

{{-- ⬅️ تعريب العنوان: 'Sale Report' -> 'تقرير المبيعات' --}}
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
                  {{-- ⬅️ تعريب عنوان التقرير وفترة التاريخ --}}
                  <strong>{{ __('reports.sale_report') }} ({{$start_date}} - {{$end_date}})</strong><br>
                </address>
              </div>
              <div class="col-sm-2">
              </div>
              </div>
            <div class="row justify-content-center">
              <div class="col-12">
                <table id="datatables" class="table table-hover">
                  <thead>
                    <tr>
                      {{-- ⬅️ تعريب رؤوس الجدول --}}
                      <th data-orderable="false">{{ __('general.sn') }}</th>
                      <th>{{ __('reports.sale_id') }}</th>
                      <th>{{ __('reports.customer') }}</th>
                      <th>{{ __('common.date') }}</th>
                      <th>{{ __('pos.item') }}</th>
                      <th>{{ __('pos.subtotal') }} {{currency()->symbol??''}}</th>
                      <th>{{ __('pos.discount') }} {{currency()->symbol??''}}</th>
                      <th>{{ __('pos.total') }} {{currency()->symbol??''}}</th>
                      <th>{{ __('reports.paid') }} {{currency()->symbol??''}}</th>
                      <th>{{ __('reports.due') }} {{currency()->symbol??''}}</th>
                      <th>{{ __('common.status') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($orders as $index => $order)
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td>#{{$order->id}}</td>
                      <td>{{ $order->customer->name ?? __('reports.walk_in_customer') }}</td>
                      <td>{{ $order->created_at->format('d-m-Y') }}</td>
                      <td>{{$order->total_item}}</td>
                      <td>{{number_format($order->sub_total,2,'.',',')}}</td>
                      <td>{{number_format($order->discount,2,'.',',')}}</td>
                      <td>{{number_format($order->total,2,'.',',')}}</td>
                      <td>{{number_format($order->paid,2,'.',',')}}</td>
                      <td>{{number_format($order->due,2,'.',',')}}</td>
                      <td>
                        @if ($order->status)
                        {{-- ⬅️ تعريب 'Paid' --}}
                        {{ __('reports.paid_status') }}
                        @else
                        {{-- ⬅️ تعريب 'Due' --}}
                        {{ __('reports.due_status') }}
                        @endif
                      </td>
                    </tr>
                    @empty
                    <tr>
                      {{-- ⬅️ تعريب 'No sells found.' --}}
                      <td colspan="11" class="text-center">{{ __('reports.no_sales_found') }}</td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              </div>
            <div class="row no-print">
              <div class="col-12">
                {{-- ⬅️ تعريب زر 'Print' --}}
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
    // ... (منطق daterangepicker بالإنجليزية، يمكن تعريبه من خلال خيارات daterangepicker)
    // نترك هذا الجزء كما هو ونركز على تعريب النصوص الثابتة.

    // Extract start and end dates from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const startDate = urlParams.get('start_date') || moment().subtract(29, 'days').format('YYYY-MM-DD'); // Default to last 30 days if not present
    const endDate = urlParams.get('end_date') || moment().format('YYYY-MM-DD'); // Default to today if not present

    // Initialize the date range picker
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
        // Update the button text with the selected range
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

        // Redirect with selected start and end dates
        window.location.href = '{{ route("backend.admin.sale.report") }}?start_date=' + start.format('YYYY-MM-DD') + '&end_date=' + end.format('YYYY-MM-DD');
      }
    );

    // Set the initial display text for the date range button
    $('#daterange-btn span').html(moment(startDate, "YYYY-MM-DD").format('MMMM D, YYYY') + ' - ' + moment(endDate, "YYYY-MM-DD").format('MMMM D, YYYY'));
  });
</script>
@endpush
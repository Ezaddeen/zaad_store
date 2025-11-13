@extends('backend.master')

@section('title', __('orders.sale'))

@section('content')
<div class="card">
  <div class="card-body p-2 p-md-4 pt-0">
    <div class="row g-4">
      <div class="col-md-12">
        <div class="card-body table-responsive p-0" id="table_data">
          <table id="datatables" class="table table-hover">
            <thead>
              <tr>
                <th data-orderable="false">#</th>
                <th>{{ __('orders.sale_id') }}</th>
                <th>{{ __('orders.customer') }}</th> 
                <th>{{ __('orders.item') }}</th>
                <th>{{ __('orders.sub_total') }} {{currency()->symbol??''}}</th>
                <th>{{ __('orders.discount') }} {{currency()->symbol??''}}</th>
                <th>{{ __('common.total') }} {{currency()->symbol??''}}</th>
                <th>{{ __('orders.paid') }} {{currency()->symbol??''}}</th>
                <th>{{ __('common.due') }} {{currency()->symbol??''}}</th>
                <th>{{ __('common.status') }}</th>
                <th data-orderable="false">{{ __('common.action') }}</th>
              </tr>
            </thead>
            {{-- الجسم فارغ لأنه يتم ملؤه عبر AJAX --}}
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('script')

<script type="text/javascript">
  $(function() {
    let table = $('#datatables').DataTable({
      processing: true,
      serverSide: true,
      ordering: true,
      order: [
        [1, 'desc']
      ],
      ajax: {
        url: "{{ route('backend.admin.orders.index') }}"
      },

      // ==================================================
      // ⬇️            هذا هو الكود الجديد للتعريب            ⬇️
      // ==================================================
      language: {
          "decimal":        "",
          "emptyTable":     "لا توجد بيانات في الجدول",
          "info":           "عرض _START_ إلى _END_ من _TOTAL_ مدخلات",
          "infoEmpty":      "عرض 0 إلى 0 من 0 مدخلات",
          "infoFiltered":   "(تمت تصفيتها من _MAX_ إجمالي المدخلات)",
          "lengthMenu":     "عرض _MENU_ مدخلات",
          "loadingRecords": "جاري التحميل...",
          "processing":     "جاري المعالجة...",
          "search":         "بحث:",
          "zeroRecords":    "لم يتم العثور على سجلات مطابقة",
          "paginate": {
              "first":      "الأول",
              "last":       "الأخير",
              "next":       "التالي",
              "previous":   "السابق"
          }
      },
      // ==================================================
      // ⬅️                نهاية الكود الجديد                ⬅️
      // ==================================================

      columns: [{
          data: 'DT_RowIndex',
          name: 'DT_RowIndex'
        },
        {
          data: 'saleId',
          name: 'saleId'
        },
        {
          data: 'customer',
          name: 'customer'
        },
        {
          data: 'item',
          name: 'item'
        },
        {
          data: 'sub_total',
          name: 'sub_total'
        },
        {
          data: 'discount',
          name: 'discount'
        },
        {
          data: 'total',
          name: 'total'
        },
        {
          data: 'paid',
          name: 'paid'
        },
        {
          data: 'due',
          name: 'due'
        },
        {
          data: 'status',
          name: 'status'
        },
        {
          data: 'action',
          name: 'action'
        },
      ]
    });
  });
</script>
@endpush

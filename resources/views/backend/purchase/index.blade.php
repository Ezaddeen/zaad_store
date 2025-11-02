@extends('backend.master')

@section('title', __('purchases.purchases_list')) {{-- تم تعريب 'Purchase' إلى 'قائمة المشتريات' --}}

@section('content')
<div class="card">

  @can('purchase_create')
  <div class="mt-n5 mb-3 d-flex justify-content-end">
    <a href="{{ route('backend.admin.purchase.create') }}" class="btn bg-gradient-primary">
      <i class="fas fa-plus-circle"></i>
      {{ __('purchases.add_new') }} {{-- تم تعريب 'Add New' --}}
    </a>
  </div>
  @endcan
  <div class="card-body p-2 p-md-4 pt-0">
    <div class="row g-4">
      <div class="col-md-12">
        <div class="card-body table-responsive p-0" id="table_data">
          <table id="datatables" class="table table-hover">
            <thead>
              <tr>
                <th data-orderable="false">#</th>
                <th>{{ __('purchases.supplier') }}</th> {{-- تم تعريب 'Supplier' --}}
                <th>{{ __('common.id') }}</th> {{-- تم تعريب 'ID' --}}
                <th>{{ __('purchases.grand_total') }} {{currency()->symbol??''}}</th> {{-- تم تعريب 'Total' إلى 'المجموع الكلي' (الأكثر دقة) --}}
                <th>{{ __('common.date') }}</th> {{-- تم تعريب 'Date' --}}
                <th data-orderable="false">
                  {{ __('common.action') }} {{-- تم تعريب 'Action' --}}
                </th>
              </tr>
            </thead>
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
      ajax: {
        url: "{{ route('backend.admin.purchase.index') }}"
      },

      columns: [{
          data: 'DT_RowIndex',
          name: 'DT_RowIndex'
        },
        {
          data: 'supplier',
          name: 'supplier'
        },
        {
          data: 'id',
          name: 'id'
        },
        {
          data: 'total',
          name: 'total',
        },
        {
          data: 'created_at',
          name: 'created_at'
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


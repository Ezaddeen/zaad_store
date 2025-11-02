@extends('backend.master')

{{-- ⬅️ تعريب العنوان: 'Products' -> 'المنتجات' --}}
@section('title', __('products.products'))

@section('content')
<div class="card">

  @can('product_create')
  <div class="mt-n5 mb-3 d-flex justify-content-end">
    {{-- ⬅️ تعريب الزر: 'Add New' -> 'إضافة جديد' --}}
    <a href="{{ route('backend.admin.products.create') }}" class="btn bg-gradient-primary">
      <i class="fas fa-plus-circle"></i>
      {{ __('general.add_new') }}
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
                {{-- ⬅️ تعريب رؤوس الجدول --}}
                <th data-orderable="false">{{ __('general.sn') }}</th>
                <th></th> {{-- عمود الصورة --}}
                <th>{{ __('common.name') }}</th>
                <th>{{ __('common.price') }}{{currency()->symbol??''}}</th>
                <th>{{ __('products.stock') }}</th>
                <th>{{ __('common.created_at') }}</th>
                <th>{{ __('common.status') }}</th>
                <th data-orderable="false">{{ __('common.action') }}</th>
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
        url: "{{ route('backend.admin.products.index') }}"
      },

      columns: [{
          data: 'DT_RowIndex',
          name: 'DT_RowIndex'
        },
        {
          data: 'image',
          name: 'image'
        },
        {
          data: 'name',
          name: 'name'
        },
        {
          data: 'price',
          name: 'price'
        },
        {
          data: 'quantity',
          name: 'quantity'
        },
        {
          data: 'created_at',
          name: 'created_at'
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
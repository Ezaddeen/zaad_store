@extends('backend.master')

@section('title', __('brands.brands_list')) {{-- تم تعريب 'Brands' --}}

@section('content')
<div class="card">

  @can('brand_create')
  <div class="mt-n5 mb-3 d-flex justify-content-end">
    <a href="{{ route('backend.admin.brands.create') }}" class="btn bg-gradient-primary">
      <i class="fas fa-plus-circle"></i>
      {{ __('brands.add_new') }} {{-- تم تعريب 'Add New' --}}
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
                <th></th>
                <th>{{ __('brands.name') }}</th> {{-- تم تعريب 'Name' --}}
                <th>{{ __('common.status') }}</th> {{-- تم تعريب 'Status' --}}
                <th data-orderable="false">{{ __('common.action') }}</th> {{-- تم تعريب 'Action' --}}
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
      order: [
        [1, 'asc']
      ],
      ajax: {
        url: "{{ route('backend.admin.brands.index') }}"
      },
      // لا يمكن تعريب رؤوس الأعمدة هنا لأنها يتم جلبها من الكود (Blade)
      // النصوص التي تحتاج إلى تعريب إضافي في DataTable (مثل "Search:", "Showing 1 to 10 of..."):
      // تتطلب إضافة خاصية `language` في إعدادات DataTable.
      // مثال:
      // language: {
      //   "search": "{{ __('common.search') }}:",
      //   "info": "{{ __('common.showing') }} _START_ {{ __('common.to') }} _END_ {{ __('common.of') }} _TOTAL_ {{ __('common.entries') }}"
      // },

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
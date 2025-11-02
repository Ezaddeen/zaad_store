@extends('backend.master')

{{-- ⬅️ تعريب العنوان: 'Currency' -> 'العملات' --}}
@section('title', __('currencies.title'))

@section('content')
<div class="card">

  @can('currency_create')
  <div class="mt-n5 mb-3 d-flex justify-content-end">
    <a href="{{ route('backend.admin.currencies.create') }}" class="btn bg-gradient-primary">
      <i class="fas fa-plus-circle"></i>
      {{-- ⬅️ تعريب: Add New -> إضافة جديد --}}
      {{ __('common.add_new') }}
    </a>
  </div>
  @endcan
  <div class="card-body p-2 p-md-4 pt-0">
    <div class="row g-4">
      <div class="col-md-12">
        <div class="card-body p-0" id="table_data">
          <table id="datatables" class="table table-hover">
            <thead>
              <tr>
                {{-- ⬅️ تعريب: # -> التسلسل --}}
                <th data-orderable="false">{{ __('common.sn') }}</th>
                {{-- ⬅️ تعريب: Name -> الاسم --}}
                <th>{{ __('common.name') }}</th>
                {{-- ⬅️ تعريب: Code -> الرمز المختصر --}}
                <th>{{ __('common.code') }}</th>
                {{-- ⬅️ تعريب: Symbol -> الرمز --}}
                <th>{{ __('common.symbol') }}</th>
                {{-- ⬅️ تعريب: Action -> الإجراء --}}
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
      order: [
        [1, 'asc']
      ],
      ajax: {
        url: "{{ route('backend.admin.currencies.index') }}"
      },

      columns: [{
          data: 'DT_RowIndex',
          name: 'DT_RowIndex'
        },
        {
          data: 'name',
          name: 'name'
        },
        {
          data: 'code',
          name: 'code'
        },
        {
          data: 'symbol',
          name: 'symbol'
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


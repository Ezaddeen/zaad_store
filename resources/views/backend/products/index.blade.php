@extends('backend.master')

@section('title', __('products.products'))

{{-- لا حاجة لأي ستايل مخصص هنا --}}

@section('content')

<div class="card">

  <div class="card-header">
    <div class="d-flex justify-content-end align-items-center">

        <div id="bulk-actions-container" style="display:none;">
            <button id="select-all-btn" class="btn btn-sm btn-info mr-2">تحديد الكل</button>
            <button id="deselect-all-btn" class="btn btn-sm btn-secondary mr-2">إلغاء تحديد الكل</button>
            <button id="cancel-bulk-mode" class="btn btn-sm btn-dark mr-2">إلغاء</button>
        </div>

        @can('product_delete')
        <button id="toggle-delete-mode" class="btn bg-gradient-danger mr-2">
            <i class="fas fa-trash"></i> <span class="button-text">حذف</span>
        </button>
        @endcan

        @can('product_create')
        <a href="{{ route('backend.admin.products.create') }}" class="btn bg-gradient-primary">
          <i class="fas fa-plus-circle"></i>
          {{ __('general.add_new') }}
        </a>
        @endcan
    </div>
  </div>
  
  <div class="card-body p-2 p-md-4 pt-0">
    <div class="row g-4">
      <div class="col-md-12">
        {{-- ================================================== --}}
        {{-- ⬇️ هذا هو التعديل الوحيد والمهم ⬇️ --}}
        {{-- ================================================== --}}
        <div class="card-body p-0" id="table_data">

          <table id="datatables" class="table table-hover">
            <thead>
              <tr>
                <th class="bulk-checkbox-column"></th>
                <th data-orderable="false">{{ __('general.sn') }}</th>
                <th></th>
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
{{-- كود JavaScript يبقى كما هو في نسختك الممتازة --}}
<script type="text/javascript">

$(function() {

    let isDeleteModeActive = false;

    let table = $('#datatables').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        ajax: { url: "{{ route('backend.admin.products.index') }}" },

        columns: [
            { 
                data: 'checkbox',
                name: 'checkbox',
                orderable: false,
                searchable: false,
                className: 'bulk-checkbox-column'
            },
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'image', name: 'image' },
            { data: 'name', name: 'name' },
            { data: 'price', name: 'price' },
            { data: 'quantity', name: 'quantity' },
            { data: 'created_at', name: 'created_at' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action' },
        ],

        columnDefs: [
            { targets: 0, visible: false }
        ],

        drawCallback: function(settings) {
            table.column(0).visible(isDeleteModeActive, false);
        }
    });

    function enterDeleteMode() {
        isDeleteModeActive = true;
        $('#toggle-delete-mode').find('.button-text').text('حذف المحدد');
        $('#toggle-delete-mode').removeClass('bg-gradient-danger').addClass('btn-warning');
        $('#bulk-actions-container').css('display', 'flex');
        table.column(0).visible(true, false);
        $(window).trigger('resize');
    }

    function exitDeleteMode() {
        isDeleteModeActive = false;
        $('#toggle-delete-mode').find('.button-text').text('حذف');
        $('#toggle-delete-mode').removeClass('btn-warning').addClass('bg-gradient-danger');
        $('#bulk-actions-container').css('display', 'none');
        table.column(0).visible(false, false);
        $('.product-checkbox').prop('checked', false);
        $(window).trigger('resize');
    }

    $('#toggle-delete-mode').on('click', function () {
        if (isDeleteModeActive) {
            let selectedIds = [];
            $('.product-checkbox:checked').each(function () { selectedIds.push($(this).val()); });
            if (selectedIds.length === 0) {
                alert('يرجى تحديد منتج واحد على الأقل للحذف.');
                return;
            }
            if (confirm(`هل أنت متأكد من حذف ${selectedIds.length} منتج/منتجات؟`)) {
                $.post("{{ route('backend.admin.products.bulk-delete') }}", {
                    ids: selectedIds, _token: '{{ csrf_token() }}'
                }, function(response) {
                    alert(response.message);
                    exitDeleteMode();
                    table.ajax.reload(null, false);
                }).fail(function() {
                    alert('حدث خطأ أثناء محاولة الحذف.');
                });
            }
        } else {
            enterDeleteMode();
        }
    });

    $('#cancel-bulk-mode').on('click', exitDeleteMode);
    $('#select-all-btn').on('click', () => $('.product-checkbox').prop('checked', true));
    $('#deselect-all-btn').on('click', () => $('.product-checkbox').prop('checked', false));

});
</script>
@endpush

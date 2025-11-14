@extends('backend.master')

@section('title', __('orders.sale'))

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-end align-items-center">
            <div id="bulk-actions-container" style="display:none;">
                <button id="select-all-btn" class="btn btn-sm btn-info mr-2">تحديد الكل</button>
                <button id="deselect-all-btn" class="btn btn-sm btn-secondary mr-2">إلغاء تحديد الكل</button>
                <button id="cancel-bulk-mode" class="btn btn-sm btn-dark mr-2">إلغاء</button>
            </div>

            @can('sale_delete')
            <button id="toggle-delete-mode" class="btn bg-gradient-danger">
                <i class="fas fa-trash"></i> <span class="button-text">حذف</span>
            </button>
            @endcan
        </div>
    </div>

    <div class="card-body p-2 p-md-4 pt-0">
        <div class="row g-4">
            <div class="col-md-12">
                <div class="card-body table-responsive p-0" id="table_data">
                    <table id="datatables" class="table table-hover">
                        <thead>
                            <tr>
                                {{-- ⬇️ تم إعادة عمود '#' الأصلي ⬇️ --}}
                                <th class="bulk-checkbox-column"></th>
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
    let isDeleteModeActive = false;
    let table = $('#datatables').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        order: [[2, 'desc']],
        ajax: { url: "{{ route('backend.admin.orders.index') }}" },
        language: {
            "decimal": "", "emptyTable": "لا توجد بيانات في الجدول", "info": "عرض _START_ إلى _END_ من _TOTAL_ مدخلات",
            "infoEmpty": "عرض 0 إلى 0 من 0 مدخلات", "infoFiltered": "(تمت تصفيتها من _MAX_ إجمالي المدخلات)",
            "lengthMenu": "عرض _MENU_ مدخلات", "loadingRecords": "جاري التحميل...", "processing": "جاري المعالجة...",
            "search": "بحث:", "zeroRecords": "لم يتم العثور على سجلات مطابقة",
            "paginate": { "first": "الأول", "last": "الأخير", "next": "التالي", "previous": "السابق" }
        },
        // ⬇️ تم إعادة عمود 'DT_RowIndex' الأصلي ⬇️
        columns: [
            { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'bulk-checkbox-column' },
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'saleId', name: 'saleId' },
            { data: 'customer', name: 'customer' },
            { data: 'item', name: 'item' },
            { data: 'sub_total', name: 'sub_total' },
            { data: 'discount', name: 'discount' },
            { data: 'total', name: 'total' },
            { data: 'paid', name: 'paid' },
            { data: 'due', name: 'due' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action' }
        ],
        columnDefs: [{ targets: 0, visible: false }],
        drawCallback: function(settings) {
            table.column(0).visible(isDeleteModeActive, false);
        }
    });

    // ==================================================
    // ⬇️ هذا هو التعديل الحاسم والنهائي ⬇️
    // ==================================================
    function enterDeleteMode() {
        isDeleteModeActive = true;
        $('#toggle-delete-mode').find('.button-text').text('حذف المحدد');
        $('#toggle-delete-mode').removeClass('bg-gradient-danger').addClass('btn-warning');
        $('#bulk-actions-container').css('display', 'flex');
        table.column(0).visible(true, false);
        $(window).trigger('resize'); // ⬅️ إجبار الجدول على إعادة الحساب
    }

    function exitDeleteMode() {
        isDeleteModeActive = false;
        $('#toggle-delete-mode').find('.button-text').text('حذف');
        $('#toggle-delete-mode').removeClass('btn-warning').addClass('bg-gradient-danger');
        $('#bulk-actions-container').css('display', 'none');
        table.column(0).visible(false, false);
        $('.order-checkbox').prop('checked', false);
        $(window).trigger('resize'); // ⬅️ إجبار الجدول على إعادة الحساب
    }

    $('#toggle-delete-mode').on('click', function() {
        if (isDeleteModeActive) {
            let selectedIds = [];
            $('.order-checkbox:checked').each(function() { selectedIds.push($(this).val()); });
            if (selectedIds.length === 0) {
                alert('يرجى تحديد فاتورة واحدة على الأقل للحذف.');
                return;
            }
            if (confirm(`هل أنت متأكد من حذف ${selectedIds.length} فاتورة؟ سيتم إعادة كل المنتجات المباعة إلى المخزون.`)) {
                $.post("{{ route('backend.admin.orders.bulk-delete') }}", {
                    ids: selectedIds,
                    _token: '{{ csrf_token() }}'
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
    $('#select-all-btn').on('click', () => $('.order-checkbox').prop('checked', true));
    $('#deselect-all-btn').on('click', () => $('.order-checkbox').prop('checked', false));
});
</script>
@endpush

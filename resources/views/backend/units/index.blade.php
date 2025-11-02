@extends('backend.master')

{{-- استخدام مفتاح الترجمة: 'unit.units' --}}
@section('title', __('unit.units'))

@section('content')
<div class="card">
    @can('unit_create')
    <div class="mt-n5 mb-3 d-flex justify-content-end">
        <a href="{{ route('backend.admin.units.create') }}" class="btn bg-gradient-primary">
            <i class="fas fa-plus-circle"></i>
            {{-- زر الإضافة: 'general.add_new' --}}
            @lang('general.add_new')
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
                                {{-- التسلسل: 'general.sn' --}}
                                <th data-orderable="false">@lang('general.sn')</th>
                                {{-- العنوان: 'common.title' --}}
                                <th>@lang('common.title')</th>
                                {{-- الرمز المختصر: 'common.code' --}}
                                <th>@lang('common.code')</th>
                                {{-- الإجراء: 'common.action' --}}
                                <th data-orderable="false">@lang('common.action')</th>
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
                url: "{{ route('backend.admin.units.index') }}"
            },

            // إضافة تعريب واجهة DataTables (مهم جداً لتعريب "Search" و "Showing X of Y entries")
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json"
            },

            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'short_name',
                    name: 'short_name'
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
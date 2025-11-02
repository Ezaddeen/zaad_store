@extends('backend.master')

{{-- استخدام مفتاح الترجمة لعنوان الصفحة (نفترض common.suppliers) --}}
@section('title', __('common.suppliers'))

@section('content')
<div class="card">

    {{-- زر الإضافة: يظهر فقط إذا كان المستخدم يملك صلاحية 'supplier_create' --}}
    @can('supplier_create')
    <div class="mt-n5 mb-3 d-flex justify-content-end">
        <a href="{{ route('backend.admin.suppliers.create') }}" class="btn bg-gradient-primary">
            <i class="fas fa-plus-circle"></i>
            {{-- استخدام مفتاح 'common.add_new' لـ "إضافة جديد" --}}
            @lang('common.add_new')
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
                                {{-- استخدام 'general.sn' أو 'general.sno' --}}
                                <th data-orderable="false">@lang('general.sn')</th> 
                                {{-- استخدام 'common.name' --}}
                                <th>@lang('common.name')</th>
                                {{-- استخدام 'common.phone' --}}
                                <th>@lang('common.phone')</th> 
                                {{-- استخدام 'common.address' --}}
                                <th>@lang('common.address')</th> 
                                {{-- استخدام 'common.created_at' --}}
                                <th>@lang('common.created_at')</th> 
                                <th data-orderable="false">
                                    {{-- استخدام 'common.action' --}}
                                    @lang('common.action')
                                </th>
                            </tr>
                        </thead>
                        {{-- جسم الجدول سيتم ملؤه بواسطة DataTables AJAX --}}
                        <tbody>
                        </tbody>
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
                // الترتيب الافتراضي حسب العمود الثاني (الاسم) تصاعديًا
                [1, 'asc'] 
            ],
            ajax: {
                url: "{{ route('backend.admin.suppliers.index') }}"
            },

            // يتم تمرير أسماء الأعمدة إلى DataTables
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'address',
                    name: 'address'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ],
            
            // إضافة خاصية لـ DataTables لتعريب الواجهة بالكامل (اختياري لكنه احترافي)
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
            }
        });
    });
</script>
@endpush
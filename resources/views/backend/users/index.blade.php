@extends('backend.master')

{{-- العنوان: استخدام 'user.management' --}}
@section('title', __('user.management'))

@section('content')
<div class="card">
    @can('user_create')
    <div class="mt-n5 mb-3 d-flex justify-content-end">
        <a href="{{ route('backend.admin.user.create') }}" class="btn bg-gradient-primary">
            <i class="fas fa-plus-circle"></i>
            {{-- زر الإضافة: استخدام 'general.add_new' --}}
            @lang('general.add_new')
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
                                {{-- عمود الصورة المصغرة (Thumb) --}}
                                <th data-orderable="false">@lang('user.thumb')</th> 
                                
                                {{-- الاسم: استخدام 'common.name' (افتراضًا أنه العمود 1 في الـ DataTables) --}}
                                <th>@lang('common.name')</th>
                                
                                {{-- البريد الإلكتروني: استخدام 'general.email' --}}
                                <th>@lang('general.email')</th>
                                
                                {{-- الدور: استخدام 'user.role' --}}
                                <th>@lang('user.role')</th>
                                
                                {{-- تاريخ الإنشاء: استخدام 'common.created_at' --}}
                                <th>@lang('common.created_at')</th>
                                
                                {{-- الحالة: استخدام 'common.status' --}}
                                <th>@lang('common.status')</th>
                                
                                {{-- الإجراء: استخدام 'common.action' --}}
                                <th data-orderable="false">
                                    @lang('common.action')
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
            order: [
                [1, 'asc'] // الترتيب حسب العمود 1 (الاسم)
            ],
            ajax: {
                url: "{{ route('backend.admin.users') }}"
            },

            // إضافة تعريب واجهة DataTables
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json" 
            },

            columns: [
                {
                    // يجب أن يكون هذا هو عمود التسلسل # (DT_RowIndex) أو الصورة المصغرة حسب رؤوس الأعمدة.
                    // في الـ HTML كان (#) هو العمود الأول، لكن في JS العمود الأول هو 'thumb'.
                    // نعتمد على ترتيب الـ HTML الصحيح، ونتوقع أن 'thumb' هو العمود الأول:
                    data: 'thumb',
                    name: 'thumb',
                    orderable: false, // الصورة لا تُرتّب عادةً
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'roles', // يرجى ملاحظة أن هذا الاسم يختلف عن اسم رأس العمود 'Role'
                    name: 'roles'
                },
                {
                    data: 'created', // يرجى ملاحظة أن هذا الاسم يختلف عن اسم رأس العمود 'Created'
                    name: 'created'
                },
                {
                    data: 'suspend', // يرجى ملاحظة أن هذا الاسم يختلف عن اسم رأس العمود 'Status'
                    name: 'status' // تم تصحيح اسم العمود هنا ليطابق رؤوس الـ JS
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                },
            ]
        });
    });
</script>
@endpush
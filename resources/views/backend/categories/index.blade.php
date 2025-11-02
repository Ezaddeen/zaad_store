@extends('backend.master')

{{-- العنوان: 'التصنيفات' --}}
@section('title', __('categories.title')) 

@section('content')
<div class="card">

    @can('category_create')
    <div class="mt-n5 mb-3 d-flex justify-content-end">
        <a href="{{ route('backend.admin.categories.create') }}" class="btn bg-gradient-primary">
            <i class="fas fa-plus-circle"></i>
            {{-- زر الإضافة: 'إضافة جديد' --}}
            <span>{{ __('general.add_new') }}</span> 
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
                                {{-- التسلسل: تم استكمال التعريب باستخدام 'general.sn' --}}
                                <th data-orderable="false">@lang('general.sn')</th>
                                
                                {{-- عمود الصورة: تم استكمال التعريب باستخدام 'categories.image' --}}
                                <th>@lang('categories.image')</th> 
                                
                                {{-- الاسم: (صحيح) --}}
                                <th>{{ __('common.name') }}</th> 
                                
                                {{-- الحالة: (صحيح) --}}
                                <th>{{ __('common.status') }}</th> 
                                
                                {{-- الإجراء: (صحيح) --}}
                                <th data-orderable="false">{{ __('common.action') }}</th> 
                            </tr>
                        </thead>
                        <tbody>
                            {{-- يتم ملء الجدول بواسطة DataTables / AJAX --}}
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
                [1, 'asc']
            ],
            ajax: {
                url: "{{ route('backend.admin.categories.index') }}"
            },

            // إضافة تعريب واجهة DataTables (ضروري)
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json" 
            },

            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'image',
                    name: 'image',
                    orderable: false, // الصورة عادةً لا يتم ترتيبها
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
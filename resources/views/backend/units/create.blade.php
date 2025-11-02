@extends('backend.master')

{{-- استخدام مفتاح الترجمة الخاص بالعنوان --}}
@section('title', __('unit.create_unit'))

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('backend.admin.units.store') }}" method="post" class="accountForm"
            enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    
                    {{-- حقل العنوان (Title) --}}
                    <div class="mb-3 col-md-6">
                        {{-- استخدام 'common.title' للنص الظاهر (Label) --}}
                        <label for="title" class="form-label">
                            @lang('common.title')
                            <span class="text-danger">*</span>
                        </label>
                        {{-- استخدام 'unit.enter_title' للنص الإرشادي (Placeholder) --}}
                        <input type="text" class="form-control" placeholder="@lang('unit.enter_title')" name="title"
                            value="{{ old('title') }}" required>
                    </div>

                    {{-- حقل الاسم المختصر (Short Name) --}}
                    <div class="mb-3 col-md-6">
                        {{-- استخدام 'common.code' للرمز المختصر (Label) --}}
                        <label for="short_name" class="form-label">
                            @lang('common.code')
                            <span class="text-danger">*</span>
                        </label>
                        {{-- استخدام 'unit.enter_short_name' للنص الإرشادي (Placeholder) --}}
                        <input type="text" class="form-control" placeholder="@lang('unit.enter_short_name')" name="short_name"
                            value="{{ old('short_name') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        {{-- استخدام 'common.create' لزر الإنشاء --}}
                        <button type="submit" class="btn bg-gradient-primary">@lang('common.create')</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('style')
{{-- يمكن إضافة أي ملفات CSS خاصة هنا --}}
@endpush
@push('script')
<script src="{{ asset('js/image-field.js') }}"></script>
{{-- يمكن إضافة أي سكريبت آخر هنا --}}
@endpush
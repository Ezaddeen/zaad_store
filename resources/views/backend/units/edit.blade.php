@extends('backend.master')

{{-- استخدام مفتاح الترجمة الخاص بتعديل الوحدة --}}
@section('title', __('unit.update_unit'))

@section('content')
<div class="card">
    <div class="card-body">
        {{-- المسار: يستخدم PUT للتحديث ويمرر ID الوحدة --}}
        <form action="{{ route('backend.admin.units.update',$unit->id) }}" method="post" class="accountForm"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
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
                            value="{{ old('title',$unit->title) }}" required>
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
                            value="{{ old('short_name',$unit->short_name) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        {{-- استخدام 'common.update' لزر التحديث --}}
                        <button type="submit" class="btn bg-gradient-primary">@lang('common.update')</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('script')
<script src="{{ asset('js/image-field.js') }}"></script>
@endpush
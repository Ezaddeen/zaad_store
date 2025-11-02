@extends('backend.master')

{{-- استخدام دالة الترجمة لعنوان الصفحة --}}
@section('title', __('supplier.create_supplier'))

@section('content')
{{-- يمكن إضافة dir="rtl" هنا إذا لم يكن في الـ master layout --}}
<div class="card"> 
    <div class="card-body">
        <form action="{{ route('backend.admin.suppliers.store') }}" method="post" class="accountForm"
            enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">

                    {{-- حقل الاسم --}}
                    <div class="mb-3 col-md-6">
                        {{-- استخدام دالة الترجمة للنص Label --}}
                        <label for="name" class="form-label">
                            @lang('supplier.name')
                            <span class="text-danger">*</span>
                        </label>
                        {{-- استخدام دالة الترجمة للنص Placeholder --}}
                        <input type="text" class="form-control" placeholder="@lang('supplier.enter_name')" name="name"
                            value="{{ old('name') }}" required>
                    </div>

                    {{-- حقل الهاتف --}}
                    <div class="mb-3 col-md-6">
                        {{-- استخدام دالة الترجمة للنص Label --}}
                        <label for="phone" class="form-label">
                            @lang('supplier.phone')
                            <span class="text-danger">*</span>
                        </label>
                        {{-- استخدام دالة الترجمة للنص Placeholder --}}
                        <input type="text" class="form-control" placeholder="@lang('supplier.enter_phone')" name="phone"
                            value="{{ old('phone') }}" required>
                    </div>

                    {{-- حقل العنوان --}}
                    <div class="mb-3 col-md-6">
                        {{-- استخدام دالة الترجمة للنص Label --}}
                        <label for="address" class="form-label">
                            @lang('supplier.address')
                        </label>
                        {{-- استخدام دالة الترجمة للنص Placeholder --}}
                        <input type="text" class="form-control" placeholder="@lang('supplier.enter_address')" name="address"
                            value="{{ old('address') }}">
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        {{-- استخدام دالة الترجمة لزر الإرسال --}}
                        <button type="submit" class="btn btn-md bg-gradient-primary">@lang('supplier.create')</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('script')
<script>
</script>
@endpush
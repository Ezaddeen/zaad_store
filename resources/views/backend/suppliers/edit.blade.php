@extends('backend.master')

{{-- استخدام مفتاح الترجمة: 'common.update_supplier' --}}
@section('title', __('common.update_supplier')) 

@section('content')
<div class="card">
    <div class="card-body">
        {{-- المسار: يُمرر ID المورد ($supplier->id) لعملية التحديث --}}
        <form action="{{ route('backend.admin.suppliers.update',$supplier->id) }}" method="post" class="accountForm"
            enctype="multipart/form-data">
            {{-- توجيه لتحديد أن نوع الطلب هو PUT/PATCH (مطلوب لعملية التحديث في Laravel) --}}
            @method('PUT')
            @csrf
            <div class="card-body">
                <div class="row">
                    
                    {{-- حقل الاسم --}}
                    <div class="mb-3 col-md-6">
                        {{-- استخدام 'common.name' --}}
                        <label for="name" class="form-label">
                            @lang('common.name')
                            <span class="text-danger">*</span>
                        </label>
                        {{-- تم تصحيح placeholder لاستخدام مفتاح ترجمة (افتراضيًا 'general.enter_name') --}}
                        <input type="text" class="form-control" placeholder="@lang('general.enter_name')" name="name"
                            value="{{ old('name', $supplier->name) }}" required>
                    </div>

                    {{-- حقل الهاتف --}}
                    <div class="mb-3 col-md-6">
                        {{-- استخدام 'common.phone' --}}
                        <label for="phone" class="form-label">
                            @lang('common.phone')
                            <span class="text-danger">*</span>
                        </label>
                        {{-- تم استخدام 'Enter phone' كـ placeholder مؤقت إذا لم يكن هناك مفتاح محدد --}}
                        <input type="text" class="form-control" placeholder="أدخل رقم الهاتف" name="phone"
                            value="{{ old('phone', $supplier->phone) }}" required>
                    </div>

                    {{-- حقل العنوان --}}
                    <div class="mb-3 col-md-6">
                        {{-- استخدام 'common.address' --}}
                        <label for="address" class="form-label">
                            @lang('common.address')
                        </label>
                        {{-- تم استخدام 'Enter Address' كـ placeholder مؤقت --}}
                        <input type="text" class="form-control" placeholder="أدخل العنوان" name="address"
                            value="{{ old('address', $supplier->address) }}">
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
<script>
</script>
@endpush
@extends('backend.master')

{{-- ⬅️ تعريب العنوان: 'Update Customer' -> 'تحديث العميل' --}}
@section('title', __('customers.update_customer')) 

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('backend.admin.customers.update',$customer->id) }}" method="post" class="accountForm"
      enctype="multipart/form-data">
      @method('PUT')
      @csrf
      <div class="card-body">
        <div class="row">
          
          {{-- ⬅️ حقل الاسم: يستخدم مفتاح 'name' من common.php --}}
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              {{ __('common.name') }}
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" 
              placeholder="{{ __('customers.placeholder_name') }}" name="name"
              {{-- يحافظ على قيمة العميل الحالية --}}
              value="{{ old('name', $customer->name) }}" required>
          </div>
          
          {{-- ⬅️ حقل الهاتف: يستخدم مفتاح 'phone' من customers.php --}}
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              {{ __('customers.phone') }}
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" 
              placeholder="{{ __('customers.placeholder_phone') }}" name="phone"
              value="{{ old('phone', $customer->phone) }}" required>
          </div>
          
          {{-- ⬅️ حقل العنوان: يستخدم مفتاح 'address' من customers.php --}}
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              {{ __('customers.address') }}
            </label>
            <input type="text" class="form-control" 
              placeholder="{{ __('customers.placeholder_address') }}" name="address"
              value="{{ old('address', $customer->address) }}">
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            {{-- ⬅️ زر الإرسال: يستخدم مفتاح 'update' من common.php --}}
            <button type="submit" class="btn bg-gradient-primary">{{ __('common.update') }}</button>
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
@extends('backend.master')

@section('title', __('customers.update_customer')) {{-- تم تعريب العنوان ليتناسب مع الوظيفة الفعلية --}}

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('backend.admin.customers.update',$customer->id) }}" method="post" class="accountForm"
      enctype="multipart/form-data">
      @method('PUT')
      @csrf
      <div class="card-body row">
        <div class="mb-3 col-md-6">
          <label for="title" class="form-label">
            {{ __('common.name') }} {{-- تم تعريب 'Name' --}}
            <span class="text-danger">*</span>
          </label>
          <input type="text" class="form-control" placeholder="{{ __('customers.enter_name') }}" name="name" {{-- تم تعريب 'Enter title' --}}
            value="{{ $customer->name }}" required>
        </div>
        <div class="mb-3 col-md-6">
          <label for="title" class="form-label">
            {{ __('customers.phone') }} {{-- تم تعريب 'Phone' --}}
            <span class="text-danger">*</span>
          </label>
          <input type="text" class="form-control" placeholder="{{ __('customers.enter_phone') }}" name="phone" {{-- تم تعريب 'Enter phone' --}}
            value="{{ $customer->phone }}" required>
        </div>
        <div class="mb-3 col-md-6">
          <label for="title" class="form-label">
            {{ __('customers.address') }} {{-- تم تعريب 'Address' --}}
          </label>
          <input type="text" class="form-control" placeholder="{{ __('customers.enter_address') }}" name="address" {{-- تم تعريب 'Enter Address' --}}
            value="{{ $customer->address }}">
        </div>
      </div>
      <button type="submit" class="btn btn-block bg-gradient-primary">{{ __('common.update') }}</button> {{-- تم تعريب 'Update' --}}
    </form>
  </div>
</div>
@endsection
@push('script')
<script>
</script>
@endpush


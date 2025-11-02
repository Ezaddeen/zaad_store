@extends('backend.master')

{{-- ⬅️ تعريب العنوان: 'Create Currency' -> 'إنشاء عملة' --}}
@section('title', __('currencies.create_currency'))

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('backend.admin.currencies.store') }}" method="post" class="accountForm"
      enctype="multipart/form-data">
      @csrf
      <div class="card-body">
        <div class="row">
          <div class="mb-3 col-md-6">
            <label for="name" class="form-label">
              {{-- ⬅️ تعريب: Name -> الاسم --}}
              {{ __('common.name') }}
              <span class="text-danger">*</span>
            </label>
            {{-- ⬅️ تعريب placeholder: Enter name -> أدخل الاسم --}}
            <input type="text" class="form-control" placeholder="{{ __('general.enter_name') }}" name="name"
              value="{{ old('name') }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="code" class="form-label">
              {{-- ⬅️ تعريب: Code -> الرمز المختصر --}}
              {{ __('common.code') }}
              <span class="text-danger">*</span>
            </label>
            {{-- ⬅️ تعريب placeholder: Enter Short cod -> أدخل الرمز المختصر --}}
            <input type="text" class="form-control" placeholder="{{ __('currencies.enter_short_code') }}" name="code"
              value="{{ old('code') }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="symbol" class="form-label">
              {{-- ⬅️ تعريب: Symbol -> الرمز --}}
              {{ __('common.symbol') }}
              <span class="text-danger">*</span>
            </label>
            {{-- ⬅️ تعريب placeholder: Enter symbol -> أدخل الرمز --}}
            <input type="text" class="form-control" placeholder="{{ __('general.enter_symbol') }}" name="symbol"
              value="{{ old('symbol') }}" required>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            {{-- ⬅️ تعريب زر: Create -> إنشاء --}}
            <button type="submit" class="btn bg-gradient-primary">{{ __('general.create') }}</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('style')


@endpush
@push('script')
<script src="{{ asset('js/image-field.js') }}"></script>
@endpush
@extends('backend.master')

{{-- ⬅️ تعريب العنوان: 'Import Product' -> 'استيراد المنتجات' --}}
@section('title', __('products.import_product'))

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('backend.admin.products.import') }}" method="post" class="accountForm"
      enctype="multipart/form-data">
      @csrf
      <div class="card-body">
        <div class="row">
          <div class="mb-3 col-md-6">
            <div class="form-group">
              {{-- ⬅️ تعريب 'File input' --}}
              <label for="exampleInputFile">{{ __('general.file_input') }}</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" name="file" id="exampleInputFile" required>
                  {{-- ⬅️ تعريب 'Choose file' --}}
                  <label class="custom-file-label" for="exampleInputFile">{{ __('general.choose_file') }}</label>
                </div>
                <div class="input-group-append">
                  {{-- ⬅️ تعريب أيقونة ورابط 'Demo' --}}
                  <a class="input-group-text" href="{{ route('backend.admin.products.import',['download-demo' => true]) }}">
                    <i class="fas fa-download"></i> {{ __('general.download') }} {{ __('general.demo') }}
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="mb-3 col-md-6">
            {{-- ⬅️ زر الإرسال: 'Save' -> 'حفظ' --}}
            <button type="submit" class="btn btn-block bg-gradient-primary">{{ __('common.save') }}</button>
            </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('style')
<style>
  .select2-container--default .select2-selection--single {
    height: calc(1.5em + 0.75rem + 2px) !important;
  }
</style>

@endpush
@push('script')
<script src="{{ asset('js/image-field.js') }}"></script>
@endpush
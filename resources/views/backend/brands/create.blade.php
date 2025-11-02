@extends('backend.master')

@section('title', __('brands.create_brand')) {{-- من brands.php --}}

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('backend.admin.brands.store') }}" method="post" class="accountForm"
      enctype="multipart/form-data">
      @csrf
      <div class="card-body">
        <div class="row">
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              {{ __('brands.name') }} {{-- من brands.php --}}
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" placeholder="{{ __('brands.enter_name') }}" name="name" {{-- من brands.php --}}
              value="{{ old('name') }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="thumbnailInput" class="form-label">
              {{ __('brands.image') }} {{-- من brands.php --}}
            </label>
            <div class="image-upload-container" id="imageUploadContainer">
              <input type="file" class="form-control" name="brand_image" id="thumbnailInput" accept="image/*" style="display: none;">
              <div class="thumb-preview" id="thumbPreviewContainer">
                <img src="{{ asset('backend/assets/images/blank.png') }}" alt="{{ __('brands.thumbnail_preview') }}" {{-- من brands.php --}}
                  class="img-thumbnail d-none" id="thumbnailPreview">
                <div class="upload-text">
                  <i class="fas fa-plus-circle"></i>
                  <span>{{ __('brands.upload_image') }}</span> {{-- من brands.php --}}
                </div>
              </div>
            </div>
          </div>

          <div class="mb-3 col-md-12">
            <label for="description" class="form-label">
              {{ __('brands.description') }} {{-- من brands.php --}}
            </label>
            <textarea class="form-control" placeholder="{{ __('brands.enter_description') }}" name="description">{{ old('description') }}</textarea> {{-- من brands.php --}}
          </div>
          <div class="mb-3 col-md-12">
            <div class="form-switch px-4">
              <input type="hidden" name="status" value="0">
              <input class="form-check-input" type="checkbox" name="status" id="active"
                value="1" checked>
              <label class="form-check-label" for="active">
                {{ __('common.active') }} {{-- من common.php --}}
              </label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <button type="submit" class="btn bg-gradient-primary">{{ __('common.create') }}</button> {{-- من common.php --}}
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
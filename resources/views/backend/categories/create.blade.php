@extends('backend.master')

{{-- ⬅️ استخدام مفتاح ترجمة (إذا لم يكن في common، نفترض مفتاح تصنيفات) --}}
@section('title', __('categories.create_category')) 

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('backend.admin.categories.store') }}" method="post" class="accountForm"
      enctype="multipart/form-data">
      @csrf
      <div class="card-body">
        <div class="row">
          
          {{-- ⬅️ حقل الاسم: يستخدم مفتاح 'name' من common.php --}}
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              {{ __('common.name') }} 
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" placeholder="{{ __('categories.placeholder_name') }}" name="name"
              value="{{ old('name') }}" required>
          </div>
          
          {{-- ⬅️ حقل الصورة (نفترض أنه مفتاح خاص بالتصنيفات) --}}
          <div class="mb-3 col-md-6">
            <label for="thumbnailInput" class="form-label">
              {{ __('categories.image') }} 
            </label>
            <div class="image-upload-container" id="imageUploadContainer">
              <input type="file" class="form-control" name="category_image" id="thumbnailInput" accept="image/*" style="display: none;">
              <div class="thumb-preview" id="thumbPreviewContainer">
                <img src="{{ asset('backend/assets/images/blank.png') }}" alt="{{ __('categories.preview_image') }}"
                  class="img-thumbnail d-none" id="thumbnailPreview">
                <div class="upload-text">
                  <i class="fas fa-plus-circle"></i>
                  <span>{{ __('categories.upload_image') }}</span> 
                </div>
              </div>
            </div>
          </div>

          {{-- ⬅️ حقل الوصف (نفترض أنه مفتاح خاص بالتصنيفات) --}}
          <div class="mb-3 col-md-12">
            <label for="description" class="form-label">
              {{ __('categories.description') }} 
            </label>
            <textarea class="form-control" placeholder="{{ __('categories.placeholder_description') }}" name="description">{{ old('description') }}</textarea>
          </div>
          
          {{-- ⬅️ حقل حالة التفعيل: يستخدم مفتاح 'active' من common.php --}}
          <div class="mb-3 col-md-12">
            <div class="form-check form-switch form-check-reverse">
              <input type="hidden" name="status" value="0">
              <input class="form-check-input" type="checkbox" name="status" id="active"
                value="1" checked>
              <label class="form-check-label" for="active">
                --{{ __('common.active') }} 
              </label>
            </div>
          </div>
          
        </div>
        <div class="row">
          <div class="col-md-6">
            {{-- ⬅️ زر الإرسال: يستخدم مفتاح 'create' من common.php --}}
            <button type="submit" class="btn bg-gradient-primary">{{ __('common.create') }}</button>
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
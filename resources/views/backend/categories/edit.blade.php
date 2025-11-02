@extends('backend.master')

{{-- ⬅️ تعريب العنوان: "Update Category" -> "تحديث التصنيف" --}}
{{-- نفترض وجود مفتاح 'update_category' في ملف categories.php --}}
@section('title', __('categories.update_category')) 

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('backend.admin.categories.update',$category->id) }}" method="post" class="accountForm"
      enctype="multipart/form-data">
      @csrf
      @method('PUT') {{-- مهم لعملية التحديث --}}
      <div class="card-body">
        <div class="row">
          
          {{-- ⬅️ حقل الاسم: يستخدم مفتاح 'name' من common.php --}}
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              {{ __('common.name') }}
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" 
              placeholder="{{ __('categories.placeholder_name') }}" {{-- نفترض مفتاح خاص بالتصنيفات --}}
              name="name" value="{{ old('name', $category->name) }}" required>
          </div>
          
          {{-- ⬅️ حقل الصورة --}}
          <div class="mb-3 col-md-6">
            <label for="thumbnailInput" class="form-label">
              {{ __('categories.image') }} {{-- نفترض مفتاح 'image' في ملف categories.php --}}
            </label>
            <div class="image-upload-container" id="imageUploadContainer">
              <input type="file" class="form-control" name="category_image" id="thumbnailInput" accept="image/*" style="display: none;">
              <div class="thumb-preview" id="thumbPreviewContainer">
                <img src="{{ asset('storage/' . $category->image) }}" 
                  alt="{{ __('categories.preview_image') }}" {{-- نفترض مفتاح 'preview_image' --}}
                  class="img-thumbnail" id="thumbnailPreview" onerror="this.onerror=null; this.src='{{ asset('assets/images/no-image.png') }}'">
                <div class="upload-text d-none">
                  <i class="fas fa-plus-circle"></i>
                  <span>{{ __('categories.upload_image') }}</span> {{-- نفترض مفتاح 'upload_image' --}}
                </div>
              </div>
            </div>
          </div>

          {{-- ⬅️ حقل الوصف --}}
          <div class="mb-3 col-md-12">
            <label for="description" class="form-label">
              {{ __('categories.description') }} {{-- نفترض مفتاح 'description' في ملف categories.php --}}
            </label>
            <textarea class="form-control" 
              placeholder="{{ __('categories.placeholder_description') }}" {{-- نفترض مفتاح خاص بالتصنيفات --}}
              name="description">{{ old('description',$category->description) }}</textarea>
          </div>
          
          {{-- ⬅️ حقل حالة التفعيل: يستخدم مفتاح 'active' من common.php (مع تصحيح تنسيق RTL) --}}
          <div class="mb-3 col-md-12">
            <div class="form-check form-switch form-check-reverse">
              <input type="hidden" name="status" value="0">
              <input class="form-check-input" type="checkbox" name="status" id="active"
                value="1" @if($category->status==1) checked @endif>
              <label class="form-check-label" for="active">
                {{ __('common.active') }} {{-- مفتاح 'مفعل' --}}
              </label>
            </div>
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
<script src="{{ asset('js/image-field.js') }}"></script>
@endpush
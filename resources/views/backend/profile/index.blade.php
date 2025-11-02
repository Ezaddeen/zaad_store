@extends('backend.master')

{{-- ⬅️ تعريب العنوان: 'Profile' -> 'الملف الشخصي' --}}
@section('title', __('profile.profile'))

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('backend.admin.profile.update') }}" method="post" class="accountForm" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">
                {{-- ⬅️ الاسم الكامل --}}
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="fullName" class="form-label">{{ __('common.full_name') }}</label>
                        <input type="text" class="form-control" id="fullName" placeholder="{{ __('profile.placeholder_name') }}"
                            name="name" value="{{ $user->name }}">
                    </div>
                </div>
                {{-- ⬅️ البريد الإلكتروني --}}
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="email" class="form-label">{{ __('general.email') }}</label>
                        <input type="text" class="form-control" id="email" placeholder="{{ __('general.email') }}" name="email"
                            value="{{ $user->email }}">
                    </div>
                </div>
                {{-- ⬅️ صورة الملف الشخصي --}}
                <div class="col-12">
                    <div class="form-group">
                        <label for="thumbnail">{{ __('profile.profile_image') }}</label>
                        <div class="image-upload-container" id="imageUploadContainer">
                            <input type="file" class="form-control" name="profile_image" id="thumbnailInput" accept="image/*" style="display: none;">
                            <div class="thumb-preview" id="thumbPreviewContainer">
                                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ __('general.image_preview') }}"
                                    class="img-thumbnail" id="thumbnailPreview" onerror="this.onerror=null; this.src='{{ asset('assets/images/no-image.png') }}'">
                                <div class="upload-text d-none">
                                    <i class="fas fa-plus-circle"></i>
                                    <span>{{ __('general.upload_image') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- ⬅️ قسم تغيير كلمة المرور --}}
            <h4 class="font-weight-bold">{{ __('profile.password_change') }}</h4>
            <div class="row g-4">
                {{-- ⬅️ كلمة المرور الحالية --}}
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="password" class="form-label">{{ __('profile.current_password') }}</label>
                        <input type="password" class="form-control" id="password" placeholder="{{ __('profile.placeholder_current_password') }}"
                            name="current_password" autocomplete="new-password">
                    </div>
                </div>
                {{-- ⬅️ كلمة المرور الجديدة --}}
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="new_password" class="form-label">{{ __('profile.new_password') }}</label>
                        <input type="password" class="form-control" id="new_password" placeholder="{{ __('profile.placeholder_new_password') }}"
                            name="new_password">
                    </div>
                </div>
                {{-- ⬅️ تأكيد كلمة المرور --}}
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="confirmPassword" class="form-label">{{ __('profile.confirm_password') }}</label>
                        <input type="password" class="form-control" id="confirmPassword" placeholder="{{ __('profile.placeholder_confirm_password') }}"
                            name="new_password_confirmation">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {{-- ⬅️ زر الإرسال: 'Update' -> 'تحديث' --}}
                        <button type="submit" class="btn btn-block bg-gradient-primary">{{ __('common.update') }}</button>
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
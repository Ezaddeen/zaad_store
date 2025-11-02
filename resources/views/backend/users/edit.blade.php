@extends('backend.master')

@section('title', __('user.create_user')) {{-- العنوان: إنشاء مستخدم جديد --}}

@section('content')
    <div class="card">
        <div class="card-body">
            {{-- ترك المسار كما هو بناءً على طلبك --}}
            <form action="{{ route('backend.admin.user.create') }}" method="post" class="accountForm"
                enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    
                    {{-- الاسم الكامل --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="fullName" class="form-label">@lang('common.full_name')</label> {{-- الاسم الكامل --}}
                            <input type="text" class="form-control" id="fullName" placeholder="@lang('general.enter_name')"
                                name="name" value="{{ old('name') }}" required> {{-- أدخل الاسم --}}
                        </div>
                    </div>

                    {{-- بريد الدخول الإلكتروني --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="email" class="form-label">@lang('user.login_email')</label> {{-- بريد الدخول الإلكتروني --}}
                            <input type="text" class="form-control" id="email" placeholder="@lang('general.email')" name="email"
                                value="{{ old('email') }}" required> {{-- البريد الإلكتروني --}}
                        </div>
                    </div>

                    {{-- الدور والصلاحيات --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            {{-- لاحظ أن الـ 'for' ما زال يشير إلى 'confirmPassword' في النسخة الأصلية، لكننا نستخدم 'role' كاسم حقل --}}
                            <label for="confirmPassword" class="form-label">@lang('user.role_permissions')</label> {{-- الدور والصلاحيات --}}
                            <select class="custom-select" name="role" required>
                                <option value="">@lang('user.select_role')</option> {{-- -- اختر دورًا -- --}}
                                @foreach ($roles as $role)
                                    <option {{ old('role') == $role->id ? 'selected' : '' }} value="{{ $role->id }}">
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- كلمة مرور الدخول --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="password" class="form-label">@lang('user.login_password')</label> {{-- كلمة مرور الدخول --}}
                            <input type="password" class="form-control" id="password" placeholder="@lang('user.enter_password')"
                                name="password" value="{{ old('password') }}" required> {{-- أدخل كلمة المرور --}}
                        </div>
                    </div>

                    {{-- صورة الملف الشخصي --}}
                    <div class="col-12">
                        <div class="form-group">
                            <label for="thumbnail">@lang('user.profile_image')</label> {{-- صورة الملف الشخصي --}}
                            <input type="file" class="form-control" name="profile_image"
                                onchange="previewThumbnail(this)">
                            <img class="img-fluid thumbnail-preview" src="{{ nullImg() }}" alt="preview-image">
                        </div>
                    </div>

                </div>
                <button type="submit" class="btn btn-block bg-gradient-primary">@lang('common.create')</button> {{-- إنشاء --}}
            </form>
        </div>
    </div>
@endsection
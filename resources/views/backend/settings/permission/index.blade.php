@extends('backend.master')

{{-- ⬅️ تعريب العنوان: 'Permissions' -> 'الصلاحيات' --}}
@section('title', __('permissions.title'))

@section('content')
<div class="card">

    @can('role_view')
    <div class="mt-n5 mb-3 d-flex justify-content-end">
        <a href="{{ route('backend.admin.roles') }}" class="btn bg-gradient-primary">
            <i class="fas fa-ruler-vertical"></i>
            {{-- ⬅️ تعريب زر: Roles -> الأدوار --}}
            {{ __('permissions.roles') }}
        </a>
    </div>
    @endcan
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <fieldset>
                    <form action="{{ route('backend.admin.permissions.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-floating">
                                    {{-- ⬅️ تعريب placeholder: Enter permission name -> أدخل اسم الصلاحية --}}
                                    <input type="text" class="form-control" id="floatingInput"
                                        placeholder="{{ __('permissions.enter_name_placeholder') }}" name="name"
                                        value="{{ old('name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-control" id="floatingSelectGrid" name="type" required>
                                        {{-- ⬅️ تعريب: -- Select a type -- -> -- اختر نوعا -- --}}
                                        <option value="">{{ __('permissions.select_type') }}</option>
                                        {{-- ⬅️ تعريب: Normal -> عادي --}}
                                        <option value="1">{{ __('permissions.normal') }}</option>
                                        {{-- ⬅️ تعريب: Resource -> مصدر --}}
                                        <option value="2">{{ __('permissions.resource') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                {{-- ⬅️ تعريب زر: Submit -> إرسال --}}
                                <button type="submit" class="btn bg-gradient-primary">
                                    {{ __('general.submit') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </fieldset>
                <hr>
            </div>
            <div class="col-md-12 table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            {{-- ⬅️ تعريب: Name -> الاسم --}}
                            <th>{{ __('common.name') }}</th>
                            {{-- ⬅️ تعريب: Slug -> المفتاح --}}
                            <th>{{ __('permissions.slug') }}</th>

                            {{-- ⬅️ تعريب: Actions -> الإجراءات --}}
                            <th class="text-center">{{ __('common.action') }}</th>
                            </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $data)
                        <tr>
                            <td>{{ snakeToTitle($data->name) }}</td>
                            <td>{{ $data->name }}</td>

                            <td>
                                <div class="text-center">
                                    {{-- ⬅️ تعريب title: Edit permission -> تعديل الصلاحية --}}
                                    <button title="{{ __('common.edit') }} {{ __('permissions.permission') }}" type="button" class="btn bg-gradient-primary btn-xs"
                                        data-toggle="modal" data-target="#editpermission-{{ $data->id }}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    {{-- ⬅️ تعريب title: Delete permission -> حذف الصلاحية --}}
                                    {{-- ⬅️ تعريب confirm: Are you sure ? -> هل أنت متأكد؟ --}}
                                    <a title="{{ __('common.delete') }} {{ __('permissions.permission') }}"
                                        href="{{ route('backend.admin.permissions.delete', $data->id) }}"
                                        type="button" class="btn btn-danger btn-xs"
                                        onclick="return confirm('{{ __('general.confirm_delete') }}')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                                {{-- ⬅️ بداية المودال --}}
                                <div class="modal fade" id="editpermission-{{ $data->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        {!! Form::open(['method' => 'put', 'route' => ['backend.admin.permissions.update', $data->id]]) !!}
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title fs-5" id="exampleModalLabel">
                                                    <i class="fas fa-pencil-alt"></i>
                                                    {{-- ⬅️ تعريب: Edit permission -> تعديل الصلاحية --}}
                                                    {{ __('common.edit') }} {{ __('permissions.permission') }}
                                                </h5>
                                                {{-- ⬅️ تعريب: Close --}}
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="{{ __('general.close') }}">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    {{-- ⬅️ تعريب: Name: -> الاسم: --}}
                                                    <label class="control-label">{{ __('common.name') }}:</label>
                                                    {{-- ⬅️ تعريب placeholder: permission Name -> اسم الصلاحية --}}
                                                    {!! Form::text('name', $data->name, ['class' => 'form-control', 'placeholder' => __('permissions.permission_name_placeholder')]) !!}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                {{-- ⬅️ تعريب زر: Close -> إغلاق --}}
                                                <button type="button" class="btn bg-gradient-secondary"
                                                    data-dismiss="modal">
                                                    {{ __('general.close') }}
                                                </button>
                                                {{-- ⬅️ تعريب زر: Save changes -> حفظ التغييرات --}}
                                                <button type="submit" class="btn bg-gradient-primary">
                                                    {{ __('general.save_changes') }}
                                                </button>
                                            </div>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                                {{-- ⬅️ نهاية المودال --}}
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
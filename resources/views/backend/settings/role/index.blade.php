@extends('backend.master')

{{-- ⬅️ تعريب العنوان: 'Roles' -> 'الأدوار' --}}
@section('title', __('roles.title'))

@section('content')
<div class="card">
    <div class="mt-n5 mb-3 d-flex justify-content-end">
        @can('role_create')
        <button class="btn bg-gradient-primary" data-toggle="modal" data-target="#roleModal">
            <i class="fas fa-plus-circle"></i>
            {{-- ⬅️ تعريب زر: Add New -> إضافة جديد --}}
            {{ __('common.add_new') }}
        </button>
        @endcan
        <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                {!! Form::open(['url' => route('backend.admin.roles.create'), 'method' => 'post']) !!}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="fas fa-plus-circle"></i>
                            {{-- ⬅️ تعريب: Add new role -> إضافة دور جديد --}}
                            {{ __('roles.add_new_role') }}
                        </h5>
                        {{-- ⬅️ تعريب: Close --}}
                        <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('general.close') }}">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            {{-- ⬅️ تعريب label: Name -> الاسم --}}
                            {!! Form::label('name', __('common.name')) !!}
                            {{-- ⬅️ تعريب placeholder: Role Name -> اسم الدور --}}
                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('roles.role_name_placeholder')]) !!}
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- ⬅️ تعريب زر: Close -> إغلاق --}}
                        <button type="button" class="btn bg-gradient-secondary" data-dismiss="modal">{{ __('general.close') }}</button>
                        {{-- ⬅️ تعريب زر: Submit -> إرسال --}}
                        <button class="btn bg-gradient-primary">{{ __('general.submit') }}</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            {{-- ⬅️ تعريب: Name -> الاسم --}}
                            <th>{{ __('common.name') }}</th>
                            {{-- ⬅️ تعريب: Actions -> الإجراءات --}}
                            <th class="text-center">{{ __('common.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                        <tr>
                            <td> {{ $role->name }} </td>
                            <td>
                                <div class="text-center">
                                    {{-- ⬅️ تعريب title: Permission Setup -> إعداد الصلاحيات --}}
                                    <a title="{{ __('roles.permission_setup') }}"
                                        href="{{ route('backend.admin.roles.show', $role->id) }}" type="button"
                                        class="btn btn-dark btn-xs">
                                        <i class="fas fa-cog"></i>
                                    </a>
                                    @if ($role->id != 1)
                                    {{-- ⬅️ تعريب title: Edit Role -> تعديل الدور --}}
                                    <button title="{{ __('common.edit') }} {{ __('roles.role') }}" type="button" class="btn bg-gradient-primary btn-xs"
                                        data-toggle="modal" data-target="#editRole-{{ $role->id }}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    {{-- ⬅️ تعريب title: Delete Role -> حذف الدور --}}
                                    {{-- ⬅️ تعريب confirm: Are you sure ? -> هل أنت متأكد؟ --}}
                                    <a title="{{ __('common.delete') }} {{ __('roles.role') }}"
                                        href="{{ route('backend.admin.roles.delete', $role->id) }}"
                                        type="button" class="btn btn-danger btn-xs"
                                        onclick="return confirm('{{ __('general.confirm_delete') }}')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                    @endif
                                </div>

                                <div class="modal fade" id="editRole-{{ $role->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        {!! Form::open(['method' => 'put', 'route' => ['backend.admin.roles.update', $role->id]]) !!}
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title fs-5" id="exampleModalLabel">
                                                    <i class="fas fa-pencil-alt"></i>
                                                    {{-- ⬅️ تعريب: Edit Role -> تعديل الدور --}}
                                                    {{ __('common.edit') }} {{ __('roles.role') }}
                                                </h5>
                                                {{-- ⬅️ تعريب: Close --}}
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="{{ __('general.close') }}">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    {{-- ⬅️ تعريب label: Name: -> الاسم: --}}
                                                    <label class="control-label">{{ __('common.name') }}:</label>
                                                    {{-- ⬅️ تعريب placeholder: Role Name -> اسم الدور --}}
                                                    {!! Form::text('name', $role->name, ['class' => 'form-control', 'placeholder' => __('roles.role_name_placeholder')]) !!}
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
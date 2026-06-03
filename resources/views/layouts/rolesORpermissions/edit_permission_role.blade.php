@extends('applayouts.app')

@section('contents')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            Assign Permissions to: <span class="text-primary">{{ $active_role->name }}</span>
                        </h5>
                        <a href="{{ route('roles.permissions.view') }}" class="btn btn-sm btn-outline-primary">Back to List</a>
                    </div>

                @if (session('status'))
                    <div class="alert alert-success m-3 mb-0" role="alert">
                        <small>{{ session('status') }}</small>
                    </div>
                @endif

                    <form action="{{ route('role.permission.update',$active_role->id) }}" method="POST">
                        @csrf



                        <div class="card-body">
                            <div class="row">
                                @foreach ($all_permissions as $permission)
                                    <div class="col-md-3 mb-3">

                                        <div class="p-3 border rounded shadow-sm bg-light h-60 permission-box">
                                            <div class="form-check custom-checkbox">
                                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                                    value="{{ $permission->name }}" id="perm-{{ $permission->id }}"
                                                    {{ $active_role->hasPermissionTo($permission->name) ? 'checked' : '' }}>

                                                <label class="form-check-label fw-semibold text-dark text-capitalize"
                                                    for="perm-{{ $permission->id }}">
                                                    {{ str_replace('-', ' ', $permission->name) }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="card-footer bg-white text-end py-3">
                            <button type="reset" class="btn btn-light px-4 me-2">Reset Changes</button>
                            <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                Update {{ $active_role->name }} Permissions
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

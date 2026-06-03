@extends('applayouts.app')

@section('contents')
   @if (session('status'))
                        <div class="alert alert-success m-3 mb-0" role="alert">
                            <small>{{ session('status') }}</small>
                        </div>
                    @endif
    <div class="container py-5">
        <div class="row justify-content-center g-4">


            <div class="col-md-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-plus-circle me-2"></i>Assign Permissions</h6>
                    </div>



                    <form action="{{ route('role.permission.assign') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Select Role</label>
                                <select name="role_id" class="form-select form-select-sm">
                                    @foreach ($all_roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Permissions List (Scrollable if many) --}}
                            <label class="form-label small fw-bold text-primary">Check Permissions</label>
                            <div class="border rounded p-3 bg-light" style="max-height: 250px; overflow-y: auto;">
                                @foreach ($all_permissions as $permission)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="permissions[]"
                                            value="{{ $permission->name }}" id="perm-{{ $loop->index }}">
                                        <label class="form-check-label small text-capitalize"
                                            for="perm-{{ $loop->index }}">
                                            {{ str_replace('-', ' ', $permission->name) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0 pb-3">
                            <button type="submit" class="btn btn-success btn-sm w-100 shadow-sm">Save Permissions</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 2. Management Table Section (Dummy Data) --}}
            <div class="col-md-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-list me-2"></i>Role Permissions Overview</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-3 small text-muted">ROLE</th>
                                        <th class="small text-muted">PERMISSIONS</th>
                                        <th class="text-end pe-3 small text-muted">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($permissions as $role)
                                        <tr>
                                            <td class="ps-3"><strong>{{ $role->name }}</strong></td>
                                            <td>
                                                @foreach ($role->permissions as $perm)
                                                    <span
                                                        class="badge bg-dark-subtle text-dark border fw-normal">{{ $perm->name }}</span>
                                                @endforeach
                                            </td>
                                            <td class="text-end pe-3">
                                                <a href="{{ route('update.rolepermissions', $role->id) }}"
                                                    class="btn btn-sm btn-outline-primary border-0"><i
                                                        class="fa-solid fa-pen-to-square"></i></a>
                                                <form action="{{ route('role.permission.delete', $role->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0"
                                                        onclick="return confirm('Are you sure?')">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>



                                </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

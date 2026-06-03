@extends('applayouts.app')

@section('contents')
    <style>
        .access-card {
            border: none;
            border-radius: 20px;
            transition: transform 0.2s;
        }

        .access-card:hover {
            transform: translateY(-5px);
        }

        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .table-custom thead {
            background-color: #f8f9fa;
        }

        .table-custom th {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6c757d;
            border: none;
            padding: 15px;
        }

        .table-custom td {
            padding: 15px;
            vertical-align: middle;
        }

        .btn-rounded {
            border-radius: 10px;
            font-weight: 600;
        }
    </style>

    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="fw-bold">Security & Access</h3>
                <p class="text-muted">Create new roles and define system permissions below.</p>
            </div>
        </div>
        @if (@session('status'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-circle-check fs-4 me-3"></i>
                    <div>
                        <strong>Success!</strong> {{ session('status') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            {{-- Section 1: Role Management --}}
            <div class="col-lg-6">
                <div class="card access-card shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <div class="icon-circle bg-primary text-white shadow-sm">
                                    <i class="fa-solid fa-users-gear fs-4"></i>
                                </div>
                                <h5 class="fw-bold">User Roles</h5>
                                <p class="small text-muted">Example: Admin, Teacher, Student</p>
                            </div>
                            <button class="btn btn-primary btn-rounded px-4" data-bs-toggle="modal"
                                data-bs-target="#addRoleModal">
                                <i class="fa-solid fa-plus me-2"></i>Add Role
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-custom mb-0">
                                <thead>
                                    <tr>
                                        <th>Role Name</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td class="fw-bold">{{ $role->name }}</td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end align-items-center gap-2">
                                                    @can('edit-roles')
                                                         <a href="{{ route('role.update.view', $role->id) }}"
                                                        class="btn btn-light btn-sm rounded-pill px-3 border shadow-sm">
                                                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                                    </a>



                                                    <form action="{{ route('role.delete', $role->id) }}" method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger p-0"
                                                            title="Delete">
                                                            <i class="fa-solid fa-trash-can fs-5"></i>
                                                        </button>
                                                    </form>
                                                     @endcan
                                                </div>
                                            </td>
                                        </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 2: Permission Library --}}
            <div class="col-lg-6">
                <div class="card access-card shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <div class="icon-circle bg-dark text-white shadow-sm">
                                    <i class="fa-solid fa-shield-halved fs-4"></i>
                                </div>
                                <h5 class="fw-bold">Permissions</h5>
                                <p class="small text-muted">Example: edit-course, delete-user</p>
                            </div>
                            <button class="btn btn-dark btn-rounded px-4" data-bs-toggle="modal"
                                data-bs-target="#addPermissionModal">
                                <i class="fa-solid fa-key me-2"></i>Add Permission
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-custom mb-0">
                                <thead>
                                    <tr>
                                        <th>Permission</th>

                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions as $permission)
                                        <tr>
                                            <td><span class="fw-bold">{{ $permission->name }}</span></td>

                                            <td class="text-end">
                                                  @can('edit-roles')
                                                <div class="d-flex justify-content-end align-items-center gap-2">

                                                <a href="{{ route('permission.update.view', $permission->id) }}"
                                                    class="btn btn-light btn-sm rounded-pill px-3 border shadow-sm">
                                                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                                </a>
                                              <form action="{{ route('permission.delete', $permission->id) }}" method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger p-0"
                                                            title="Delete">
                                                            <i class="fa-solid fa-trash-can fs-5"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $permissions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Add Role --}}
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <form action="{{ Request::is('admin/*') ? route('roles.store') : route('teacher.roles.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <h5 class="fw-bold mb-4">Create New Role</h5>
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-2">Role Name</label>

                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control bg-light border-0 py-3 @error('name') is-invalid @enderror"
                                placeholder="Enter role name...">


                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-3 rounded-3 fw-bold">Save Role</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">

                <form action="{{ route('permissions.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <h5 class="fw-bold mb-4">Create New Permission</h5>
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-2">Permission Name (Slug)</label>
                            <input type="text" name="name" class="form-control bg-light border-0 py-3"
                                placeholder="e.g. edit-course" required>
                            <small class="text-muted extra-small"> @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark py-3 rounded-3 fw-bold">Save Permission</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Check if there are any validation errors for 'name'
            @if ($errors->has('name'))
                var myModal = new bootstrap.Modal(document.getElementById('addRoleModal'));
                myModal.show();
            @endif
        });
    </script>
@endsection

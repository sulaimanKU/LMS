@extends('applayouts.app')

@section('contents')
    <style>
        /* 1. Header & Modal Fixes */
        /* Prevents the modal from hiding your navbar or making it jump */
        .modal {
            z-index: 1050 !important;
        }

        .modal-backdrop {
            z-index: 1040 !important;
        }

        /* Adds spacing so a fixed header doesn't cover the content */
        .content-wrapper {
            padding-top: 30px;
        }

        /* 2. Table & Action Button Responsiveness */
        .action-container {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        @media (max-width: 768px) {
            .action-container {
                justify-content: center;
            }

            .btn-responsive span {
                display: none;
            }

            /* Hide text on mobile */
            .col-email {
                display: none;
            }

            /* Hide email column on mobile */
        }

        /* 3. Permission Switch Styling */
        .permission-card {
            transition: all 0.2s;
            border: 1px solid #eee;
        }

        .permission-card:hover {
            border-color: #0dcaf0;
            background-color: #f8fdff;
        }

        .form-check-input:checked {
            background-color: #0dcaf0;
            border-color: #0dcaf0;
        }
    </style>

    <div class="container content-wrapper">
        {{-- Success Alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-user-shield me-2 text-primary"></i>User Access Control</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>User Details</th>
                                <th class="col-email">Email</th>
                                <th>Role</th>
                                <th class="text-end" style="min-width: 160px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <div class="d-md-none small text-muted">{{ $user->email }}</div>
                                    </td>
                                    <td class="col-email">{{ $user->email }}</td>
                                    <td>
                                        @foreach ($user->getRoleNames() as $role)
                                            <span
                                                class="badge rounded-pill bg-success text-capitalize">{{ $role }}</span>
                                        @endforeach
                                        @if ($user->roles->isEmpty())
                                            <span class="badge bg-secondary">No Role</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="action-container">
                                            <button class="btn btn-sm btn-primary btn-responsive" data-bs-toggle="modal"
                                                data-bs-target="#role{{ $user->id }}">
                                                <i class="fa-solid fa-user-tag"></i> <span>Role</span>
                                            </button>

                                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                                data-bs-target="#del{{ $user->id }}">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="role{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Change Role: {{ $user->name }}</h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('user.role.assign', $user->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body p-4">
                                                    <select name="role_name" class="form-select border-primary" required>
                                                        @foreach ($all_roles as $role)
                                                            <option value="{{ $role->name }}"
                                                                {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                                {{ ucfirst($role->name) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="submit" class="btn btn-primary w-100">Update
                                                        Role</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>



                                <div class="modal fade" id="del{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-body text-center p-4">
                                                <i class="fa-solid fa-triangle-exclamation text-danger fa-3x mb-3"></i>
                                                <p>Delete account for <strong>{{ $user->name }}</strong>?</p>
                                                <div class="d-flex gap-2 mt-3">
                                                    <button type="button" class="btn btn-light w-100"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('user.delete', $user->id) }}" method="POST"
                                                        class="w-100">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-danger w-100">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    function filterPerms(input) {
        let filter = input.value.toLowerCase();
        let container = input.closest('.modal-body');
        let items = container.querySelectorAll('.perm-item');

        items.forEach(item => {
            let label = item.querySelector('label').innerText.toLowerCase();
            item.style.display = label.includes(filter) ? "block" : "none";
        });
    }
</script>

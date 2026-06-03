@extends('applayouts.app')

@section('contents')
<style>
    #settings-container {
        padding: 15px;
        margin-top: 20px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }

    .settings-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e3e6f0;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.05);
        margin-bottom: 20px;
    }

    .card-header-pro {
        padding: 15px 20px;
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        border-radius: 12px 12px 0 0;
    }

    .form-label-pro {
        font-size: 11px;
        font-weight: 800;
        color: #4e73df;
        text-transform: uppercase;
        margin-bottom: 6px;
        letter-spacing: 0.5px;
    }

    .input-pro {
        border: 1px solid #d1d3e2;
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 14px;
        background-color: #ffffff;
    }

    .input-pro:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1);
    }

    .settings-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding: 10px 0 50px 0;
    }

    @media (max-width: 576px) {
        .settings-footer { flex-direction: column; }
        .settings-footer .btn { width: 100%; }
    }
</style>

<div id="settings-container">

    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1">Account Settings</h4>
        <p class="text-muted small">Managing profile for: <strong>{{ $user->name }}</strong></p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 small">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Profile Information --}}
        <div class="settings-card">
            <div class="card-header-pro">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-person-circle me-2"></i>Admin Profile</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label-pro">Full Name</label>
                        <input type="text" name="name" class="form-control input-pro" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-pro">Email Address</label>
                        <input type="email" name="email" class="form-control input-pro" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label-pro">Role Access</label>
                        <input type="text" class="form-control input-pro bg-light" value="{{ strtoupper($user->role ?? 'Administrator') }}" readonly>
                        <small class="text-muted" style="font-size: 10px;">Your role permissions are managed by the system owner.</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Security --}}
        <div class="settings-card">
            <div class="card-header-pro">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-shield-lock me-2"></i>Security Update</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label-pro">New Password</label>
                        <input type="password" name="password" class="form-control input-pro" placeholder="Leave blank to keep current password">
                    </div>
                    <div class="col-12">
                        <label class="form-label-pro">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control input-pro" placeholder="Repeat new password">
                    </div>
                </div>
            </div>
        </div>

        <div class="settings-footer">
            <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                Save All Changes
            </button>
        </div>
    </form>
</div>
@endsection

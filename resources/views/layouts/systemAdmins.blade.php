@extends('applayouts.app')
@section('contents')

<style>
.sa-page { padding:1.5rem; background:#F8FAFF; min-height:100%; }

/* Header */
.sa-header { display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:.75rem; margin-bottom:1.5rem; }
.sa-title  { font-size:1.2rem; font-weight:800; color:#1E293B; margin:0; }
.sa-sub    { font-size:.8rem; color:#94A3B8; margin:.1rem 0 0; }

/* Stats */
.sa-stats { display:flex; gap:.75rem; flex-wrap:wrap; margin-bottom:1.25rem; }
.sa-stat {
    background:#fff; border:1.5px solid #F1F5F9; border-radius:14px;
    padding:.85rem 1.1rem; display:flex; align-items:center; gap:.8rem;
    box-shadow:0 1px 4px rgba(0,0,0,.04); min-width:160px;
}
.sa-stat-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.95rem; flex-shrink:0; }
.sa-stat-num  { font-size:1.3rem; font-weight:800; color:#1E293B; margin:0; line-height:1; }
.sa-stat-lbl  { font-size:.68rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#94A3B8; margin:.15rem 0 0; }

/* Admin cards grid */
.sa-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(340px,1fr)); gap:1rem; }

/* Card */
.sa-card {
    background:#fff; border:1.5px solid #F1F5F9; border-radius:14px;
    box-shadow:0 1px 6px rgba(0,0,0,.05); overflow:hidden;
    transition:box-shadow .2s, transform .2s;
}
.sa-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.09); transform:translateY(-1px); }
.sa-card-me { border-color:#C7D2FE; background:linear-gradient(135deg,#FAFBFF,#F5F3FF); }

/* Card top */
.sa-card-top {
    display:flex; align-items:center; gap:.85rem;
    padding:1.1rem 1.2rem; border-bottom:1px solid #F1F5F9;
}
.sa-avatar {
    width:46px; height:46px; border-radius:13px; flex-shrink:0;
    background:linear-gradient(135deg,#4F46E5,#7C3AED);
    color:#fff; font-size:1rem; font-weight:800;
    display:flex; align-items:center; justify-content:center;
}
.sa-avatar-me { background:linear-gradient(135deg,#0891B2,#0E7490); }
.sa-card-info { flex:1; min-width:0; }
.sa-card-name  { font-size:.92rem; font-weight:800; color:#1E293B; margin:0; }
.sa-card-email { font-size:.74rem; color:#64748B; margin:.1rem 0 0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.sa-you-badge  { font-size:.65rem; font-weight:800; padding:.15rem .55rem; border-radius:50px; background:#EEF2FF; color:#4338CA; margin-top:.2rem; display:inline-block; }
.sa-shield { color:#4F46E5; font-size:1.1rem; flex-shrink:0; }

/* Card foot */
.sa-card-foot {
    display:flex; align-items:center; justify-content:space-between;
    padding:.8rem 1.2rem; gap:.5rem; flex-wrap:wrap;
}
.sa-foot-meta { font-size:.72rem; color:#94A3B8; }
.sa-foot-meta strong { color:#64748B; }

/* Buttons */
.sa-btn-add {
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.5rem 1.1rem; border:none; border-radius:10px;
    background:linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff;
    font-size:.82rem; font-weight:700; cursor:pointer;
    box-shadow:0 3px 10px rgba(79,70,229,.25); transition:.15s;
    text-decoration:none;
}
.sa-btn-add:hover { transform:translateY(-1px); color:#fff; }
.sa-btn-remove {
    display:inline-flex; align-items:center; gap:.35rem;
    padding:.35rem .75rem; border:1.5px solid #FECACA; border-radius:8px;
    background:#FEF2F2; color:#DC2626; font-size:.75rem; font-weight:600;
    cursor:pointer; transition:.15s; text-decoration:none;
}
.sa-btn-remove:hover { background:#FEE2E2; border-color:#FCA5A5; }
.sa-btn-self { color:#CBD5E1; border-color:#F1F5F9; background:#F8FAFF; cursor:not-allowed; }

/* Empty */
.sa-empty { grid-column:1/-1; text-align:center; padding:4rem 1rem; color:#CBD5E1; }
.sa-empty i { font-size:2.5rem; display:block; margin-bottom:.75rem; }
.sa-empty p { margin:0; font-size:.9rem; }

/* Modal */
.sa-modal { border:none; border-radius:16px; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,.12); }
.sa-modal-hdr {
    background:linear-gradient(135deg,#4F46E5,#7C3AED);
    padding:1.25rem 1.5rem 1rem; display:flex; align-items:flex-start; justify-content:space-between;
}
.sa-modal-title { font-size:.975rem; font-weight:700; color:#fff; margin:0; }
.sa-modal-sub   { font-size:.78rem; color:rgba(255,255,255,.75); margin:.2rem 0 0; }
.sa-modal-close {
    background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25);
    color:#fff; width:30px; height:30px; border-radius:8px;
    display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:.8rem;
}
.sa-modal-body   { padding:1.25rem 1.5rem; display:flex; flex-direction:column; gap:.8rem; }
.sa-modal-footer { padding:1rem 1.5rem 1.3rem; display:flex; justify-content:flex-end; gap:.6rem; border-top:1px solid #F1F5F9; }
.sa-field { display:flex; flex-direction:column; gap:.3rem; }
.sa-label { font-size:.72rem; font-weight:800; text-transform:uppercase; letter-spacing:.5px; color:#64748B; }
.sa-req   { color:#EF4444; }
.sa-input {
    border:1.5px solid #E2E8F0; border-radius:9px; padding:.55rem .8rem;
    font-size:.875rem; color:#1E293B; background:#fff; outline:none;
    transition:border-color .15s; width:100%;
}
.sa-input:focus { border-color:#7C3AED; box-shadow:0 0 0 3px rgba(124,58,237,.08); }
.sa-hint { font-size:.7rem; color:#94A3B8; margin:.15rem 0 0; }
.sa-btn-cancel {
    padding:.5rem 1.1rem; border:1.5px solid #E2E8F0; border-radius:9px;
    background:#fff; color:#64748B; font-size:.855rem; font-weight:600; cursor:pointer;
}
.sa-btn-submit {
    padding:.5rem 1.25rem; border:none; border-radius:9px;
    background:linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff;
    font-size:.855rem; font-weight:600; cursor:pointer;
    display:inline-flex; align-items:center; gap:.4rem;
    box-shadow:0 4px 12px rgba(79,70,229,.25);
}

/* Warning box */
.sa-warning {
    background:#FFFBEB; border:1px solid #FCD34D; border-radius:10px;
    padding:.65rem 1rem; font-size:.78rem; color:#92400E;
    display:flex; align-items:flex-start; gap:.5rem;
}
</style>

<div class="sa-page">

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            <ul class="mb-0 ps-3 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="sa-header">
        <div>
            <h5 class="sa-title"><i class="fa-solid fa-user-shield me-2 text-primary"></i>System Administrators</h5>
            <p class="sa-sub">Manage admin accounts — only admins can access this section</p>
        </div>
        <button class="sa-btn-add" data-bs-toggle="modal" data-bs-target="#addAdminModal">
            <i class="fa-solid fa-plus"></i> Add Admin
        </button>
    </div>

    {{-- Stats --}}
    <div class="sa-stats">
        <div class="sa-stat">
            <div class="sa-stat-icon" style="background:#EEF2FF;color:#4F46E5;"><i class="fa-solid fa-user-shield"></i></div>
            <div>
                <p class="sa-stat-num">{{ $admins->count() }}</p>
                <p class="sa-stat-lbl">Total Admins</p>
            </div>
        </div>
        <div class="sa-stat">
            <div class="sa-stat-icon" style="background:#D1FAE5;color:#059669;"><i class="fa-solid fa-circle-check"></i></div>
            <div>
                <p class="sa-stat-num">{{ $admins->count() }}</p>
                <p class="sa-stat-lbl">Active</p>
            </div>
        </div>
    </div>

    {{-- Warning --}}
    <div class="sa-warning mb-4">
        <i class="fa-solid fa-triangle-exclamation mt-1"></i>
        <span>Admin accounts have <strong>full system access</strong>. Only add trusted users here. Removing an admin only revokes their role — their account is kept.</span>
    </div>

    {{-- Grid --}}
    <div class="sa-grid">
        @forelse($admins as $admin)
        @php $isMe = $admin->id === auth()->id(); @endphp
        <div class="sa-card {{ $isMe ? 'sa-card-me' : '' }}">

            {{-- Top --}}
            <div class="sa-card-top">
                <div class="sa-avatar {{ $isMe ? 'sa-avatar-me' : '' }}">
                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                </div>
                <div class="sa-card-info">
                    <p class="sa-card-name">{{ $admin->name }}</p>
                    <p class="sa-card-email" title="{{ $admin->email }}">{{ $admin->email }}</p>
                    @if($isMe)
                        <span class="sa-you-badge"><i class="fa-solid fa-circle-user me-1"></i>You</span>
                    @endif
                </div>
                <i class="fa-solid fa-shield-halved sa-shield" title="Admin"></i>
            </div>

            {{-- Foot --}}
            <div class="sa-card-foot">
                <span class="sa-foot-meta">
                    <i class="fa-solid fa-calendar me-1"></i>
                    Added <strong>{{ $admin->created_at->format('d M Y') }}</strong>
                </span>

                @if($isMe)
                    <span class="sa-btn-remove sa-btn-self" title="Cannot remove your own account">
                        <i class="fa-solid fa-lock"></i> Your Account
                    </span>
                @else
                    <form action="{{ route('admin.system.admins.delete', $admin->id) }}" method="POST"
                          onsubmit="return confirm('Remove admin access from {{ addslashes($admin->name) }}?\n\nTheir account will remain but without admin role.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="sa-btn-remove">
                            <i class="fa-solid fa-user-minus"></i> Remove Admin
                        </button>
                    </form>
                @endif
            </div>

        </div>
        @empty
        <div class="sa-empty">
            <i class="fa-solid fa-user-shield"></i>
            <p>No admin accounts found.</p>
        </div>
        @endforelse
    </div>

</div>

{{-- ── ADD ADMIN MODAL ── --}}
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content sa-modal">

            <div class="sa-modal-hdr">
                <div>
                    <h5 class="sa-modal-title"><i class="fa-solid fa-user-shield me-2"></i>Add System Admin</h5>
                    <p class="sa-modal-sub">New admin will have full access to the admin panel</p>
                </div>
                <button type="button" class="sa-modal-close" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.system.admins.store') }}" method="POST">
                @csrf
                <div class="sa-modal-body">

                    <div class="sa-field">
                        <label class="sa-label">Full Name <span class="sa-req">*</span></label>
                        <input type="text" name="name" class="sa-input" placeholder="e.g. Ali Hassan" required value="{{ old('name') }}">
                    </div>

                    <div class="sa-field">
                        <label class="sa-label">Email Address <span class="sa-req">*</span></label>
                        <input type="email" name="email" class="sa-input" placeholder="admin@example.com" required value="{{ old('email') }}">
                    </div>

                    <div class="sa-field">
                        <label class="sa-label">Password <span class="sa-req">*</span></label>
                        <input type="password" name="password" class="sa-input" placeholder="Min 8 characters" required minlength="8">
                    </div>

                    <div class="sa-field">
                        <label class="sa-label">Confirm Password <span class="sa-req">*</span></label>
                        <input type="password" name="password_confirmation" class="sa-input" placeholder="Re-enter password" required minlength="8">
                    </div>

                    <div class="sa-warning">
                        <i class="fa-solid fa-triangle-exclamation mt-1"></i>
                        <span>The new admin will be able to <strong>manage all data</strong> including teachers, students, courses, and reports. Share this account only with trusted staff.</span>
                    </div>

                </div>

                <div class="sa-modal-footer">
                    <button type="button" class="sa-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="sa-btn-submit">
                        <i class="fa-solid fa-user-plus"></i> Create Admin Account
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- Re-open modal with errors --}}
@if($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {
    new bootstrap.Modal(document.getElementById('addAdminModal')).show();
});
</script>
@endif

@endsection

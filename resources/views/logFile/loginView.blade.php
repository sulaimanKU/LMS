<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — MyLMS</title>
    <link rel="icon" href="{{ asset('images/logo/logo1.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; font-family: 'Inter', sans-serif; }

    /* ── Layout ── */
    .lp-wrap {
        display: flex;
        min-height: 100vh;
    }

    /* ── Left brand panel ── */
    .lp-brand {
        flex: 0 0 46%;
        background: linear-gradient(145deg, #312E81 0%, #4F46E5 45%, #7C3AED 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        padding: 3.5rem 3.5rem;
        position: relative;
        overflow: hidden;
    }

    /* decorative circles */
    .lp-brand::before {
        content: '';
        position: absolute;
        width: 420px; height: 420px;
        border-radius: 50%;
        background: rgba(255,255,255,.06);
        top: -100px; right: -120px;
    }
    .lp-brand::after {
        content: '';
        position: absolute;
        width: 280px; height: 280px;
        border-radius: 50%;
        background: rgba(255,255,255,.05);
        bottom: -60px; left: -80px;
    }

    .lp-logo {
        display: flex;
        align-items: center;
        gap: .65rem;
        margin-bottom: 3.5rem;
        position: relative; z-index: 1;
    }
    .lp-logo-icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        background: rgba(255,255,255,.18);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; color: #fff;
        backdrop-filter: blur(6px);
    }
    .lp-logo-text {
        font-size: 1.25rem; font-weight: 800; color: #fff; letter-spacing: -.3px;
    }

    .lp-brand-headline {
        font-size: 2.3rem; font-weight: 900; color: #fff;
        line-height: 1.2; letter-spacing: -.5px;
        margin-bottom: 1rem;
        position: relative; z-index: 1;
    }
    .lp-brand-headline span {
        color: #C4B5FD;
    }

    .lp-brand-sub {
        font-size: .95rem; color: rgba(255,255,255,.72);
        line-height: 1.65; max-width: 320px;
        margin-bottom: 2.5rem;
        position: relative; z-index: 1;
    }

    .lp-features {
        display: flex;
        flex-direction: column;
        gap: .85rem;
        position: relative; z-index: 1;
    }
    .lp-feat {
        display: flex; align-items: center; gap: .75rem;
    }
    .lp-feat-icon {
        width: 34px; height: 34px; border-radius: 9px;
        background: rgba(255,255,255,.14);
        display: flex; align-items: center; justify-content: center;
        font-size: .8rem; color: #C4B5FD; flex-shrink: 0;
    }
    .lp-feat-text { font-size: .83rem; color: rgba(255,255,255,.82); font-weight: 500; }

    .lp-brand-footer {
        position: absolute; bottom: 1.75rem; left: 3.5rem;
        font-size: .72rem; color: rgba(255,255,255,.4);
        z-index: 1;
    }

    /* ── Right form panel ── */
    .lp-form-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 2.5rem 2rem;
        background: #F8FAFF;
    }

    .lp-form-box {
        width: 100%;
        max-width: 420px;
    }

    .lp-form-head {
        margin-bottom: 2rem;
    }
    .lp-form-title {
        font-size: 1.6rem; font-weight: 800; color: #1E293B;
        letter-spacing: -.4px; margin-bottom: .3rem;
    }
    .lp-form-sub {
        font-size: .87rem; color: #94A3B8;
    }

    /* Alert */
    .lp-alert {
        border-radius: 10px; border: none; font-size: .82rem;
        padding: .7rem 1rem; margin-bottom: 1.25rem;
        display: flex; align-items: flex-start; gap: .5rem;
    }

    /* Field */
    .lp-field { margin-bottom: 1.1rem; }
    .lp-label {
        font-size: .72rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: .5px;
        color: #64748B; display: block; margin-bottom: .35rem;
    }
    .lp-input-wrap { position: relative; }
    .lp-input-icon {
        position: absolute; left: .85rem; top: 50%;
        transform: translateY(-50%);
        color: #CBD5E1; font-size: .85rem; pointer-events: none;
    }
    .lp-input {
        width: 100%;
        border: 1.5px solid #E2E8F0; border-radius: 11px;
        padding: .65rem .85rem .65rem 2.5rem;
        font-size: .9rem; color: #1E293B;
        background: #fff; outline: none;
        transition: border-color .15s, box-shadow .15s;
        font-family: 'Inter', sans-serif;
    }
    .lp-input:focus {
        border-color: #7C3AED;
        box-shadow: 0 0 0 3px rgba(124,58,237,.1);
    }
    .lp-input.is-invalid { border-color: #EF4444; }
    .lp-pw-toggle {
        position: absolute; right: .85rem; top: 50%;
        transform: translateY(-50%);
        background: none; border: none; color: #94A3B8;
        cursor: pointer; padding: 0; font-size: .85rem;
        transition: color .15s;
    }
    .lp-pw-toggle:hover { color: #4F46E5; }

    /* Row label */
    .lp-label-row {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: .35rem;
    }
    .lp-forgot {
        font-size: .75rem; color: #7C3AED; font-weight: 600;
        text-decoration: none;
    }
    .lp-forgot:hover { color: #4F46E5; text-decoration: underline; }

    /* Submit */
    .lp-submit {
        width: 100%;
        padding: .75rem;
        border: none; border-radius: 11px;
        background: linear-gradient(135deg, #4F46E5, #7C3AED);
        color: #fff; font-size: .95rem; font-weight: 700;
        cursor: pointer; letter-spacing: -.1px;
        box-shadow: 0 4px 14px rgba(79,70,229,.35);
        transition: transform .15s, box-shadow .15s;
        margin-top: .4rem;
        display: flex; align-items: center; justify-content: center; gap: .5rem;
    }
    .lp-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(79,70,229,.4);
    }
    .lp-submit:active { transform: translateY(0); }

    /* Footer link */
    .lp-register-link {
        text-align: center; margin-top: 1.75rem;
        font-size: .84rem; color: #94A3B8;
    }
    .lp-register-link a {
        color: #4F46E5; font-weight: 700; text-decoration: none;
    }
    .lp-register-link a:hover { text-decoration: underline; }

    /* Divider */
    .lp-divider {
        display: flex; align-items: center; gap: .75rem;
        margin: 1.25rem 0;
        font-size: .74rem; color: #CBD5E1; font-weight: 600;
    }
    .lp-divider::before, .lp-divider::after {
        content: ''; flex: 1; height: 1px; background: #E2E8F0;
    }

    /* Role badges */
    .lp-roles {
        display: flex; gap: .5rem; flex-wrap: wrap;
        justify-content: center; margin-top: .6rem;
    }
    .lp-role-badge {
        padding: .28rem .75rem; border-radius: 50px;
        font-size: .7rem; font-weight: 700;
        border: 1.5px solid #E2E8F0; color: #64748B; background: #fff;
    }
    .lp-role-badge i { margin-right: .3rem; }

    /* ── Mobile ── */
    @media (max-width: 820px) {
        .lp-brand { display: none; }
        .lp-form-panel { background: #fff; padding: 2rem 1.25rem; }
    }
    </style>
</head>
<body>

<div class="lp-wrap">

    {{-- ── Left brand panel ── --}}
    <div class="lp-brand">

        <div class="lp-logo">
            <div class="lp-logo-icon"><i class="fa-solid fa-graduation-cap"></i></div>
            <span class="lp-logo-text">MyLMS</span>
        </div>

        <h1 class="lp-brand-headline">
            Your learning<br>journey <span>starts here.</span>
        </h1>
        <p class="lp-brand-sub">
            One platform for students, teachers, and administrators — access courses, manage assignments, and track progress all in one place.
        </p>

        <div class="lp-features">
            <div class="lp-feat">
                <div class="lp-feat-icon"><i class="fa-solid fa-book-open"></i></div>
                <span class="lp-feat-text">Access all your enrolled courses</span>
            </div>
            <div class="lp-feat">
                <div class="lp-feat-icon"><i class="fa-solid fa-video"></i></div>
                <span class="lp-feat-text">Join live online classes in real time</span>
            </div>
            <div class="lp-feat">
                <div class="lp-feat-icon"><i class="fa-solid fa-file-lines"></i></div>
                <span class="lp-feat-text">Submit assignments and track grades</span>
            </div>
            <div class="lp-feat">
                <div class="lp-feat-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <span class="lp-feat-text">Secure, role-based access control</span>
            </div>
        </div>

        <div class="lp-brand-footer">© {{ date('Y') }} MyLMS — All rights reserved</div>
    </div>

    {{-- ── Right form panel ── --}}
    <div class="lp-form-panel">
        <div class="lp-form-box">

            <div class="lp-form-head">
                <h2 class="lp-form-title">Welcome back</h2>
                <p class="lp-form-sub">Sign in to continue to your dashboard</p>
            </div>

            {{-- Success flash --}}
            @if(session('success'))
            <div class="lp-alert alert-success" style="background:#D1FAE5;color:#065F46;">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            {{-- Validation errors --}}
            @if($errors->any())
            <div class="lp-alert" style="background:#FEE2E2;color:#DC2626;">
                <i class="fa-solid fa-circle-xmark" style="margin-top:.1rem;"></i>
                <div>
                    @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                </div>
            </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" autocomplete="off">
                @csrf

                {{-- Email --}}
                <div class="lp-field">
                    <label class="lp-label">Email address</label>
                    <div class="lp-input-wrap">
                        <i class="fa-solid fa-envelope lp-input-icon"></i>
                        <input type="email" name="email" class="lp-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               placeholder="you@example.com"
                               value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                {{-- Password --}}
                <div class="lp-field">
                    <div class="lp-label-row">
                        <label class="lp-label" style="margin:0;">Password</label>
                        <a href="#" class="lp-forgot">Forgot password?</a>
                    </div>
                    <div class="lp-input-wrap">
                        <i class="fa-solid fa-lock lp-input-icon"></i>
                        <input type="password" name="password" id="lpPassword"
                               class="lp-input" placeholder="••••••••" required style="padding-right:2.5rem;">
                        <button type="button" class="lp-pw-toggle" onclick="togglePw()" title="Show / hide password">
                            <i class="fa-solid fa-eye" id="lpPwEye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="lp-submit">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> Sign In
                </button>
            </form>

            <div class="lp-divider">OR</div>

            <div style="text-align:center;">
                <p style="font-size:.78rem;color:#94A3B8;margin-bottom:.5rem;">Not registered yet?</p>
                <a href="{{ route('register') }}"
                   style="display:inline-flex;align-items:center;gap:.45rem;padding:.55rem 1.3rem;border-radius:10px;border:1.5px solid #E2E8F0;background:#fff;color:#4F46E5;font-size:.84rem;font-weight:700;text-decoration:none;transition:.15s;">
                    <i class="fa-solid fa-user-plus"></i> Create an Account
                </a>
            </div>

            <div class="lp-divider" style="margin-top:1.5rem;"></div>

            <div class="lp-roles">
                <span class="lp-role-badge"><i class="fa-solid fa-user-shield" style="color:#4F46E5;"></i>Admin</span>
                <span class="lp-role-badge"><i class="fa-solid fa-chalkboard-user" style="color:#059669;"></i>Teacher</span>
                <span class="lp-role-badge"><i class="fa-solid fa-user-graduate" style="color:#D97706;"></i>Student</span>
            </div>
            <p style="text-align:center;font-size:.7rem;color:#CBD5E1;margin-top:.5rem;">You'll be redirected to your role's dashboard automatically</p>

        </div>
    </div>

</div>

<script>
function togglePw() {
    const inp = document.getElementById('lpPassword');
    const eye = document.getElementById('lpPwEye');
    const show = inp.type === 'password';
    inp.type = show ? 'text' : 'password';
    eye.className = show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
}
</script>

</body>
</html>

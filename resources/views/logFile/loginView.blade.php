<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — {{ $systemSettings['site_title'] ?? 'MyLMS' }}</title>
    <link rel="icon" href="{{ isset($systemSettings['site_favicon']) ? asset('storage/'.$systemSettings['site_favicon']) : asset('images/logo/logo1.png') }}" type="image/png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #6366F1;
            --brand-secondary: #4F46E5;
            --dark-bg: #0B0F19;
            --card-bg: #ffffff;
            --text-main: #111827;
            --text-muted: #6B7280;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--dark-bg);
            background-image: 
                radial-gradient(circle at top right, rgba(99, 102, 241, 0.15), transparent 400px),
                radial-gradient(circle at bottom left, rgba(79, 70, 229, 0.15), transparent 400px);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .main-auth-container {
            width: 100%;
            max-width: 920px;
            display: flex;
            background: var(--card-bg);
            border-radius: 28px;
            box-shadow: 0 40px 100px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            position: relative;
        }

        /* ── Left Side: Visual Branding ── */
        .brand-side {
            flex: 1.1;
            background: linear-gradient(135deg, #1E1B4B 0%, #312E81 100%);
            padding: 50px;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        /* Decorative background pattern */
        .brand-side::before {
            content: ""; position: absolute; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 1;
        }

        .brand-top { position: relative; z-index: 2; display: flex; align-items: center; gap: 14px; }
        .brand-logo-sq {
            width: 48px; height: 48px; background: rgba(255,255,255,0.1);
            backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.2);
            border-radius: 14px; display: flex; align-items: center; justify-content: center; padding: 6px;
        }
        .brand-logo-sq img { width: 100%; height: 100%; object-fit: contain; }
        .brand-logo-sq i { font-size: 1.4rem; color: #fff; }
        .brand-name { font-size: 1.4rem; font-weight: 800; letter-spacing: -0.5px; }

        .brand-main { position: relative; z-index: 2; }
        .brand-main h1 { font-size: 2.8rem; font-weight: 800; line-height: 1.1; margin-bottom: 24px; }
        .brand-main h1 span { color: #A5B4FC; }
        .brand-main p { font-size: 1.05rem; color: #C7D2FE; line-height: 1.6; max-width: 360px; }

        .brand-footer { position: relative; z-index: 2; font-size: 0.85rem; color: rgba(255,255,255,0.5); font-weight: 500; }

        /* ── Right Side: Form ── */
        .form-side {
            flex: 1;
            padding: 60px;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-intro { margin-bottom: 40px; }
        .form-intro h2 { font-size: 2rem; font-weight: 800; color: var(--text-main); margin-bottom: 8px; }
        .form-intro p { color: var(--text-muted); font-size: 0.95rem; }

        /* Custom Input Fields */
        .field-group { margin-bottom: 24px; }
        .field-label { font-size: 0.8rem; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; display: block; }
        
        .input-box { position: relative; display: flex; align-items: center; }
        .input-box i.main-icon { position: absolute; left: 16px; color: #9CA3AF; font-size: 1rem; transition: 0.2s; }
        .input-control {
            width: 100%; padding: 14px 16px 14px 46px;
            background: #F9FAFB; border: 2px solid #F3F4F6; border-radius: 14px;
            font-size: 1rem; font-weight: 500; color: var(--text-main);
            transition: all 0.2s ease; outline: none;
        }
        .input-control:focus {
            background: #fff; border-color: var(--brand-primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }
        .input-control:focus + i.main-icon { color: var(--brand-primary); }

        .btn-eye { position: absolute; right: 12px; background: none; border: none; color: #9CA3AF; cursor: pointer; padding: 8px; transition: 0.2s; }
        .btn-eye:hover { color: var(--brand-primary); }

        /* Action Button */
        .btn-auth-submit {
            width: 100%; padding: 16px; background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
            color: #fff; border: none; border-radius: 14px; font-weight: 700; font-size: 1.05rem;
            box-shadow: 0 10px 20px -2px rgba(79, 70, 229, 0.3);
            transition: all 0.3s; cursor: pointer; margin-top: 12px;
        }
        .btn-auth-submit:hover { transform: translateY(-2px); box-shadow: 0 15px 30px -5px rgba(79, 70, 229, 0.4); }
        .btn-auth-submit:active { transform: translateY(0); }

        /* Alerts */
        .alert-bubble {
            padding: 14px 18px; border-radius: 14px; font-size: 0.9rem; font-weight: 500; margin-bottom: 24px;
            display: flex; align-items: center; gap: 12px; animation: slideIn 0.4s ease-out;
        }
        @keyframes slideIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .alert-error { background: #FFF1F2; color: #991B1B; border: 1px solid #FFE4E6; }
        .alert-success { background: #ECFDF5; color: #065F46; border: 1px solid #D1FAE5; }

        /* Role Indicators */
        .access-footer { display: flex; align-items: center; justify-content: center; gap: 12px; margin-top: 40px; }
        .access-pill { font-size: 0.75rem; font-weight: 700; color: #9CA3AF; padding: 6px 14px; background: #F3F4F6; border-radius: 50px; }

        @media (max-width: 900px) {
            .brand-side { display: none; }
            .form-side { padding: 45px 30px; }
            .main-auth-container { max-width: 480px; }
        }
    </style>
</head>
<body>

    <div class="main-auth-container">
        
        <!-- Left: Brand Panel -->
        <div class="brand-side">
            <div class="brand-top">
                <div class="brand-logo-sq">
                    @if(isset($systemSettings['site_logo_nav']))
                        <img src="{{ asset('storage/'.$systemSettings['site_logo_nav']) }}" alt="Logo">
                    @else
                        <i class="fa-solid fa-graduation-cap"></i>
                    @endif
                </div>
                <span class="brand-name">{{ $systemSettings['site_title'] ?? 'MyLMS' }}</span>
            </div>

            <div class="brand-main">
                <h1>Elevate Your<br><span>Knowledge.</span></h1>
                <p>Welcome back to the portal. Access your courses, connect with peers, and continue your journey toward excellence.</p>
            </div>

            <div class="brand-footer">
                © {{ date('Y') }} {{ $systemSettings['site_title'] ?? 'MyLMS' }} • Central Learning Hub
            </div>
        </div>

        <!-- Right: Form Panel -->
        <div class="form-side">
            
            <div class="form-intro">
                <h2>Welcome Back</h2>
                <p>Please enter your account details below.</p>
            </div>

            {{-- Messages --}}
            @if(session('success'))
                <div class="alert-bubble alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-bubble alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div>@foreach($errors->all() as $e){{ $e }}@endforeach</div>
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf

                <div class="field-group">
                    <label class="field-label">Email Address</label>
                    <div class="input-box">
                        <input type="email" name="email" class="input-control" placeholder="name@company.com" value="{{ old('email') }}" required autofocus>
                        <i class="fa-solid fa-envelope main-icon"></i>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Password</label>
                    <div class="input-box">
                        <input type="password" name="password" id="loginPass" class="input-control" placeholder="••••••••" required>
                        <i class="fa-solid fa-lock main-icon"></i>
                        <button type="button" class="btn-eye" onclick="togglePass()">
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-auth-submit">
                    Sign In to Portal
                </button>
            </form>

            <div class="access-footer">
                <span class="access-pill">Student</span>
                <span class="access-pill">Teacher</span>
                <span class="access-pill">Admin</span>
            </div>

        </div>
    </div>

    <script>
        function togglePass() {
            const p = document.getElementById('loginPass');
            const i = document.getElementById('eyeIcon');
            if (p.type === 'password') {
                p.type = 'text';
                i.className = 'fa-solid fa-eye-slash';
            } else {
                p.type = 'password';
                i.className = 'fa-solid fa-eye';
            }
        }
    </script>

</body>
</html>

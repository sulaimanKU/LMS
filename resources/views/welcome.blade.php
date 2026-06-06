<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $systemSettings['site_title'] ?? config('app.name', 'LMS') }}</title>
    <link rel="icon" href="{{ isset($systemSettings['site_favicon']) ? asset('storage/'.$systemSettings['site_favicon']) : asset('images/logo/logo1.png') }}" type="image/png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('styles/styles.css') }}">

    <style>
        :root {
            --brand-primary: #6366F1;
            --brand-dark: #0F172A;
            --nav-height: 85px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
            background: #fff;
        }

        /* ── Modern Navbar ── */
        .lms-navbar {
            height: var(--nav-height);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            background: rgba(15, 23, 42, 0.8) !important; /* Semi-dark at top for visibility */
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .lms-navbar.scrolled {
            background: rgba(15, 23, 42, 0.95) !important; /* Deeper navy when scrolling */
            height: 72px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(99, 102, 241, 0.2);
        }

        .navbar-brand { display: flex; align-items: center; gap: 12px; font-weight: 800; color: #fff !important; font-size: 1.35rem; }
        
        /* Fixed Logo Visibility */
        .nav-logo-box {
            height: 55px; width: 55px; 
            background: rgba(255,255,255,0.1); 
            border-radius: 14px; 
            padding: 8px;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
        }
        .nav-logo-box:hover { background: rgba(255,255,255,0.2); transform: scale(1.05); }
        .nav-logo-box img { max-width: 100%; max-height: 100%; object-fit: contain; }

        /* Force high visibility for links - HIGH SPECIFICITY */
        html body .lms-navbar .navbar-nav .nav-link {
            font-size: 0.95rem;
            font-weight: 600;
            color: rgba(255,255,255,0.9) !important;
            padding: 0.5rem 1.4rem !important;
            transition: all 0.2s ease;
            position: relative;
            background: transparent !important;
        }
        
        html body .lms-navbar .navbar-nav .nav-link:hover,
        html body .lms-navbar .navbar-nav .nav-link.active { 
            color: #fff !important; 
            opacity: 1; 
            background: transparent !important;
        }
        
        /* Active State Underline */
        html body .lms-navbar .navbar-nav .nav-link.active::after {
            content: ""; position: absolute; bottom: -5px; left: 1.4rem; right: 1.4rem;
            height: 3px; background: var(--brand-primary); border-radius: 10px;
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.6);
        }

        /* Mobile Menu Fix */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: #0F172A !important; /* Force dark background on mobile */
                margin-top: 15px;
                padding: 20px;
                border-radius: 20px;
                border: 1px solid rgba(255,255,255,0.1);
                box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            }
        }

        .btn-nav-login {
            font-size: 0.85rem; font-weight: 700; color: #fff;
            padding: 8px 22px; border-radius: 12px; transition: all 0.2s;
            text-decoration: none;
        }
        .btn-nav-login:hover { color: var(--brand-primary); }

        .btn-nav-register {
            font-size: 0.85rem; font-weight: 700; background: var(--brand-primary); color: #fff !important;
            padding: 11px 28px; border-radius: 14px; transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2); text-decoration: none;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .btn-nav-register:hover { transform: translateY(-2px); box-shadow: 0 15px 30px rgba(99, 102, 241, 0.4); }

        /* ── Footer ── */
        .lms-footer { background: #0B0F19; color: #94A3B8; padding: 6rem 0 2rem; border-top: 1px solid rgba(255,255,255,0.05); }
        .footer-heading { color: #fff; font-weight: 700; margin-bottom: 2rem; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1.5px; }
        .footer-links { list-style: none; padding: 0; }
        .footer-links li { margin-bottom: 1rem; }
        .footer-links a { color: #94A3B8; text-decoration: none; transition: 0.2s; font-size: 0.9rem; font-weight: 500; }
        .footer-links a:hover { color: var(--brand-primary); padding-left: 6px; }

        .social-btn {
            width: 42px; height: 42px; background: rgba(255,255,255,0.05); border-radius: 12px;
            display: inline-flex; align-items: center; justify-content: center;
            color: #fff; text-decoration: none; transition: all 0.3s; margin-right: 12px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .social-btn:hover { background: var(--brand-primary); transform: translateY(-5px); border-color: transparent; }

        .footer-divider { border-color: rgba(255,255,255,0.05); margin: 4rem 0 2.5rem; }
        
        .footer-cta-card {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(15, 23, 42, 1));
            border-radius: 20px; padding: 2rem; border: 1px solid rgba(255,255,255,0.05);
        }
    </style>
</head>

<body>

<!-- ============================================================
     NAVBAR
============================================================ -->
<nav class="navbar navbar-expand-lg lms-navbar fixed-top">
    <div class="container">

        <a class="navbar-brand" href="/">
            <div class="nav-logo-box">
                @if(isset($systemSettings['site_logo_nav']))
                    <img src="{{ asset('storage/'.$systemSettings['site_logo_nav']) }}" alt="Logo">
                @else
                    <i class="bi bi-mortarboard-fill"></i>
                @endif
            </div>
            <span>{{ $systemSettings['site_title'] ?? 'MyLMS' }}</span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarLMS">
            <i class="bi bi-list fs-2 text-white"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarLMS">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('courses.index') || request()->routeIs('course.show') ? 'active' : '' }}" href="{{ route('courses.index') }}">Courses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('team.index') ? 'active' : '' }}" href="{{ route('team.index') }}">Faculty</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#statusModal">Track Status</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('payment.upload') ? 'active' : '' }}" href="{{ route('payment.upload') }}">Payments</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-nav-register">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-nav-login">Login</a>
                    <a href="{{ route('register') }}" class="btn-nav-register">Join Academy</a>
                @endauth
            </div>
        </div>

    </div>
</nav>

<!-- Track Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 28px; overflow: hidden;">
            <div class="modal-header border-0 pb-0" style="background: #0F172A; padding: 2.5rem;">
                <div>
                    <h5 class="modal-title fw-bold text-white mb-1">
                        <i class="bi bi-search me-2 text-primary"></i>Application Tracker
                    </h5>
                    <p class="text-white mb-0" style="opacity:.6; font-size:.85rem;">
                        Check your enrollment status instantly.
                    </p>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto shadow-none" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('registration.track') }}" method="post">
                @csrf
                <div class="modal-body p-4 p-md-5">
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase ls-1">Registered Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 px-3"><i class="bi bi-envelope text-primary"></i></span>
                            <input type="email" name="email" class="form-control bg-light border-0 py-3 shadow-none" style="border-radius: 0 12px 12px 0;" placeholder="your@email.com" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-4 shadow-lg border-0" style="background: var(--brand-primary);">
                        Check Status <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<main>
    @yield('content')
</main>

<!-- ============================================================
     FOOTER
============================================================ -->
<footer class="lms-footer">
    <div class="container">
        <div class="row g-5">

            <!-- Brand -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand d-flex align-items-center gap-3 mb-4">
                    <div class="nav-logo-box" style="height: 48px; width: 48px;">
                        @if(isset($systemSettings['site_logo_footer']))
                            <img src="{{ asset('storage/'.$systemSettings['site_logo_footer']) }}" alt="Logo">
                        @elseif(isset($systemSettings['site_logo_nav']))
                            <img src="{{ asset('storage/'.$systemSettings['site_logo_nav']) }}" alt="Logo" style="filter: brightness(0) invert(1);">
                        @else
                            <i class="bi bi-mortarboard-fill text-white fs-2"></i>
                        @endif
                    </div>
                    <span class="text-white fw-bold fs-4">{{ $systemSettings['site_title'] ?? 'MyLMS' }}</span>
                </div>
                <p class="footer-desc" style="line-height: 1.8; font-size: 0.92rem;">
                    {{ $systemSettings['site_about'] ?? 'A modern learning management system built to connect students, teachers, and institutions — all in one place.' }}
                </p>
                <div class="footer-social mt-4">
                    @if(isset($systemSettings['social_facebook']))
                        <a href="{{ $systemSettings['social_facebook'] }}" class="social-btn" target="_blank"><i class="bi bi-facebook"></i></a>
                    @endif
                    @if(isset($systemSettings['social_twitter']))
                        <a href="{{ $systemSettings['social_twitter'] }}" class="social-btn" target="_blank"><i class="bi bi-twitter-x"></i></a>
                    @endif
                    @if(isset($systemSettings['social_instagram']))
                        <a href="{{ $systemSettings['social_instagram'] }}" class="social-btn" target="_blank"><i class="bi bi-instagram"></i></a>
                    @endif
                    @if(isset($systemSettings['social_linkedin']))
                        <a href="{{ $systemSettings['social_linkedin'] }}" class="social-btn" target="_blank"><i class="bi bi-linkedin"></i></a>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-heading">Sitemap</h6>
                <ul class="footer-links">
                    <li><a href="/">Home Page</a></li>
                    <li><a href="{{ route('courses.index') }}">Browse Modules</a></li>
                    <li><a href="{{ route('team.index') }}">Our Experts</a></li>
                    <li><a href="{{ route('payment.upload') }}">Fee Portal</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-lg-6 col-md-12">
                <div class="footer-cta-card">
                    <h6 class="footer-heading mb-3">Institutional Support</h6>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <ul class="footer-links">
                                <li><i class="bi bi-envelope-at me-2 text-primary"></i>{{ $systemSettings['contact_email'] ?? 'info@mylms.edu' }}</li>
                                <li><i class="bi bi-telephone-outbound me-2 text-primary"></i>{{ $systemSettings['contact_phone'] ?? '+92 300 000 0000' }}</li>
                                <li><i class="bi bi-geo-alt me-2 text-primary"></i>{{ $systemSettings['contact_address'] ?? '123 Education St, City' }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('register') }}" class="btn btn-primary w-100 py-3 fw-bold rounded-4 shadow-lg border-0" style="background: var(--brand-primary);">
                                Join Academy Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <hr class="footer-divider">

        <div class="footer-bottom d-flex flex-wrap justify-content-between align-items-center gap-3 pb-4">
            <span class="small fw-500">{{ $systemSettings['footer_text'] ?? ('&copy; ' . date('Y') . ' ' . ($systemSettings['site_title'] ?? 'MyLMS') . ' — All Rights Reserved') }}</span>
            <div class="d-flex gap-4">
                <a href="#" class="footer-links-inline small text-muted text-decoration-none">Privacy Policy</a>
                <a href="#" class="footer-links-inline small text-muted text-decoration-none">Standard Terms</a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Smart Navbar Scroll Effect
    window.addEventListener('scroll', function() {
        const nav = document.querySelector('.lms-navbar');
        if (window.scrollY > 50) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });
</script>

</body>
</html>

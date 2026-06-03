<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LMS') }}</title>
    <link rel="icon" href="{{ asset('images/logo/logo1.png') }}" type="image/png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('styles/styles.css') }}">
</head>

<body>

<!-- ============================================================
     NAVBAR
============================================================ -->
<nav class="navbar navbar-expand-lg lms-navbar">
    <div class="container">

        <a class="navbar-brand" href="/">
            <img src="{{ asset('images/logo/logo.png') }}" alt="MyLMS">
            <span>MyLMS</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarLMS">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarLMS">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link active" href="/"><i class="bi bi-house me-1"></i>Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('courses.index') }}"><i class="bi bi-book me-1"></i>Courses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('team.index') }}"><i class="bi bi-people me-1"></i>Our Team</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <i class="bi bi-search me-1"></i>Track Status
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('payment.upload') }}">
                        <i class="bi bi-cloud-arrow-up me-1"></i>Upload Slip
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('login') }}" class="btn-nav-login">Login</a>
                <a href="{{ route('register') }}" class="btn-nav-register">Get Started</a>
            </div>
        </div>

    </div>
</nav>

<!-- Track Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header border-0 pb-0" style="background: var(--gradient-primary); padding: 1.5rem 1.75rem;">
                <div>
                    <h5 class="modal-title fw-bold text-white mb-1">
                        <i class="bi bi-search-heart me-2"></i>Check Registration Status
                    </h5>
                    <p class="text-white mb-0" style="opacity:.8; font-size:.85rem;">
                        Enter your email to track your application
                    </p>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('registration.track') }}" method="post">
                @csrf

                @if(session('success'))
                    <div class="alert alert-success border-0 rounded-0 mb-0 py-2 px-4">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger border-0 rounded-0 mb-0 py-2 px-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        @foreach($errors->all() as $error) {{ $error }} @endforeach
                    </div>
                @endif

                <div class="modal-body p-4">
                    <label class="form-label fw-semibold small text-uppercase text-muted ls-1">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-envelope text-muted"></i>
                        </span>
                        <input type="email" name="email"
                               class="form-control border-start-0 ps-0"
                               placeholder="your@email.com" required>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="submit" class="btn w-100 py-2 fw-semibold text-white"
                            style="background: var(--gradient-primary); border: none; border-radius: 10px;">
                        <i class="bi bi-arrow-right-circle me-2"></i>Search Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@yield('content')

<!-- ============================================================
     FOOTER
============================================================ -->
<footer class="lms-footer">
    <div class="container">
        <div class="row g-4">

            <!-- Brand -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand d-flex align-items-center gap-2 mb-2">
                    <img src="{{ asset('images/logo/logo.png') }}" alt="Logo" style="height:30px; filter:brightness(0) invert(1);">
                    MyLMS
                </div>
                <p class="footer-desc">
                    A modern learning management system built to connect students, teachers,
                    and institutions — all in one place.
                </p>
                <div class="footer-social mt-3">
                    <a href="#" class="social-btn" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-btn" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="social-btn" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-btn" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-heading">Quick Links</h6>
                <ul class="footer-links">
                    <li><a href="/">Home</a></li>
                    <li><a href="{{ route('courses.index') }}">Courses</a></li>
                    <li><a href="{{ route('team.index') }}">Our Team</a></li>
                    <li><a href="{{ route('payment.upload') }}">Upload Slip</a></li>
                </ul>
            </div>

            <!-- Programmes -->
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading">Programmes</h6>
                <ul class="footer-links">
                    <li><a href="#">Web Development</a></li>
                    <li><a href="#">Business Management</a></li>
                    <li><a href="#">Graphic Design</a></li>
                    <li><a href="#">Data Science</a></li>
                    <li><a href="#">Languages</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading">Contact</h6>
                <ul class="footer-links">
                    <li><i class="bi bi-envelope me-2 text-indigo-400"></i>info@mylms.edu</li>
                    <li><i class="bi bi-telephone me-2"></i>+92 300 000 0000</li>
                    <li><i class="bi bi-geo-alt me-2"></i>123 Education St, City</li>
                </ul>
                <div class="mt-3">
                    <a href="{{ route('register') }}"
                       class="btn btn-sm fw-semibold text-white px-3 py-2"
                       style="background: var(--gradient-primary); border-radius: 8px;">
                        <i class="bi bi-person-plus me-1"></i>Enroll Now
                    </a>
                </div>
            </div>

        </div>

        <hr class="footer-divider">

        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} MyLMS — All Rights Reserved</span>
            <div class="d-flex gap-3">
                <a href="#" class="footer-links-inline">Privacy Policy</a>
                <a href="#" class="footer-links-inline">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const carouselElement = document.querySelector('#lmsCarousel');
    if (carouselElement) {
        new bootstrap.Carousel(carouselElement, { interval: 4000, touch: true });
    }
    const testimonialCarousel = document.querySelector('#testimonialCarousel');
    if (testimonialCarousel) {
        new bootstrap.Carousel(testimonialCarousel, { interval: 5000, touch: true });
    }
</script>

</body>
</html>

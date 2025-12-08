<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'LMS') }}</title>
     <link rel="icon" href="{{ asset('images/logo/logo1.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('styles/styles.css') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


</head>

<body>

    <!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-white py-3 shadow-sm" style="height: 60px;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="{{ asset('images/logo/logo.png') }}" alt="MyLMS Logo"
                 style="max-height: 80px; width: auto; object-fit: contain;">
            <span class="fw-bold text-dark fs-5">MyLMS</span>
        </a>
            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarLMS">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Main Navbar -->
            <div class="collapse navbar-collapse" id="navbarLMS">

                <!-- CENTERED NAVIGATION -->
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-3">

                    <li class="nav-item">
                        <a class="nav-link active fw-semibold" href="#">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#">Courses</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#">Students</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#">Teachers</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-semibold" href="#"
                            data-bs-toggle="dropdown">More</a>

                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Attendance</a></li>
                            <li><a class="dropdown-item" href="#">Results</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                        </ul>
                    </li>

                </ul>

                <!-- RIGHT SIDE BUTTONS -->
                <div class="d-flex">
                    <a href="/login" class="btn btn-outline-primary me-2 px-3">Login</a>
                    <a href="/register" class="btn btn-primary px-3">Register</a>
                </div>

            </div>
        </div>
    </nav>

    <!-- HERO TEXT -->
    <section class="hero-section position-relative">

        <!-- OVERLAY TEXT -->
        <div class="hero-content text-center text-white">
            <h1 class="hero-title fw-bold">Welcome to the Learning Management System</h1>
            <p class="hero-subtitle lead">
                Manage courses, students, teachers & more efficiently.
            </p>
        </div>

        <!-- CAROUSEL -->
        <div id="lmsCarousel" class="carousel slide h-100" data-bs-ride="carousel">
            <div class="carousel-inner h-100 rounded shadow-sm">

                <div class="carousel-item active h-100">
                    <img src="{{ asset('images/learning-education-ideas-insight-intelligence-study-concept.jpg') }}"
                        class="d-block w-100 hero-img">
                </div>

                <div class="carousel-item h-100">
                    <img src="{{ asset('images/city-committed-education-collage-concept.jpg') }}"
                        class="d-block w-100 hero-img">
                </div>

                <div class="carousel-item h-100">
                    <img src="{{ asset('images/4144413.jpg') }}" class="d-block w-100 hero-img">
                </div>

            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#lmsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#lmsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>

    </section>


    <!-- POPULAR COURSES -->
    <section class="container my-5">

        <!-- Heading with View All -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary m-0">Popular Courses</h2>
            <a href="#" class="text-decoration-none fw-semibold text-primary view-all-link">
                View All Courses →
            </a>
        </div>

        <div class="row g-4">

            <!-- Course Card 1 -->
            <div class="col-md-4">
                <div class="card shadow-sm hover-card h-100">
                    <img src="{{ asset('images/learning-education-ideas-insight-intelligence-study-concept.jpg') }}"
                        class="card-img-top" alt="Web Development Basics Thumbnail">

                    <div class="card-body">
                        <h5 class="card-title fw-semibold">Web Development Basics</h5>

                        <!-- Small description snippet -->
                        <p class="small text-muted">
                            Learn HTML, CSS, and JavaScript fundamentals to build modern websites.
                        </p>

                        <!-- Instructor + extra details -->
                        <p class="mb-1">
                            <a href="#" class="instructor-link">John Doe</a>
                        </p>

                        <div class="course-meta small text-muted mb-3">
                            <span>⏳ 10 hours</span> •
                            <span>📘 18 lessons</span> •
                            <span class="badge bg-success">Free</span>
                        </div>

                        <a href="#" class="btn btn-primary w-100">View Course</a>
                    </div>
                </div>
            </div>

            <!-- Course Card 2 -->
            <div class="col-md-4">
                <div class="card shadow-sm hover-card h-100">
                    <img src="{{ asset('images/4144413.jpg') }}" class="card-img-top"
                        alt="Graphic Design Essentials Thumbnail">

                    <div class="card-body">
                        <h5 class="card-title fw-semibold">Graphic Design Essentials</h5>

                        <p class="small text-muted">
                            Master layout, typography, and color theory using industry tools.
                        </p>

                        <p class="mb-1">
                            <a href="#" class="instructor-link">Sarah Smith</a>
                        </p>

                        <div class="course-meta small text-muted mb-3">
                            <span>⏳ 8 hours</span> •
                            <span>📘 14 lessons</span> •
                            <span class="badge bg-primary">$49</span>
                        </div>

                        <a href="#" class="btn btn-primary w-100">View Course</a>
                    </div>
                </div>
            </div>

            <!-- Course Card 3 -->
            <div class="col-md-4">
                <div class="card shadow-sm hover-card h-100">
                    <img src="{{ asset('images/city-committed-education-collage-concept.jpg') }}" class="card-img-top"
                        alt="Business Management Thumbnail">

                    <div class="card-body">
                        <h5 class="card-title fw-semibold">Business Management</h5>

                        <p class="small text-muted">
                            Learn essential strategies to organize, plan, and grow businesses.
                        </p>

                        <p class="mb-1">
                            <a href="#" class="instructor-link">Mark Wilson</a>
                        </p>

                        <div class="course-meta small text-muted mb-3">
                            <span>⏳ 12 hours</span> •
                            <span>📘 20 lessons</span> •
                            <span class="badge bg-warning text-dark">Popular</span>
                        </div>

                        <a href="#" class="btn btn-primary w-100">View Course</a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- TOP CATEGORIES -->
    <section class="container section-spacing">
        <h2 class="section-title text-primary">Top Categories</h2>

        <div class="row g-4 text-center">

            <div class="col-6 col-md-3">
                <div class="category-card">
                    <h5 class="category-title">Development</h5>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="category-card">
                    <h5 class="category-title">Science</h5>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="category-card">
                    <h5 class="category-title">Business</h5>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="category-card">
                    <h5 class="category-title">Languages</h5>
                </div>
            </div>

        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section class="container my-5">
        <h2 class="text-center fw-bold mb-4 text-primary">What Our Students Say</h2>

        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">

                <!-- Testimonial 1 -->
                <div class="carousel-item active text-center">
                    <div class="testimonial-card mx-auto p-4 shadow-sm">

                        <img src="https://via.placeholder.com/80" class="rounded-circle mb-3 testimonial-img"
                            alt="Photo of Ali Khan">

                        <blockquote class="blockquote">
                            <p class="lead fst-italic">
                                “This LMS made studying so easy!”
                            </p>
                        </blockquote>

                        <h6 class="fw-bold text-secondary">
                            — Ali Khan
                        </h6>
                        <p class="small text-muted">
                            B.Sc. Computer Science Student
                        </p>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="carousel-item text-center">
                    <div class="testimonial-card mx-auto p-4 shadow-sm">

                        <img src="https://via.placeholder.com/80" class="rounded-circle mb-3 testimonial-img"
                            alt="Photo of Sara Ahmad">

                        <blockquote class="blockquote">
                            <p class="lead fst-italic">
                                “Managing students is effortless now.”
                            </p>
                        </blockquote>

                        <h6 class="fw-bold text-secondary">
                            — Sara Ahmad
                        </h6>
                        <p class="small text-muted">
                            High School Administrator
                        </p>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="carousel-item text-center">
                    <div class="testimonial-card mx-auto p-4 shadow-sm">

                        <img src="https://via.placeholder.com/80" class="rounded-circle mb-3 testimonial-img"
                            alt="Photo of Ahmed Raza">

                        <blockquote class="blockquote">
                            <p class="lead fst-italic">
                                “Perfect for teachers and institutes.”
                            </p>
                        </blockquote>

                        <h6 class="fw-bold text-secondary">
                            — Ahmed Raza
                        </h6>
                        <p class="small text-muted">
                            College Lecturer
                        </p>
                    </div>
                </div>

            </div>

            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel"
                data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>

        </div>
    </section>


    <!-- FOOTER -->
    <footer class="footer bg-dark text-light py-5 mt-4">
        <div class="container">

            <div class="row text-center text-md-start g-4">

                <!-- About / Legal Links -->
                <div class="col-md-4">
                    <h5 class="mb-3 text-primary">LMS System</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Sitemap</a></li>
                    </ul>
                </div>

                <!-- Quick Links / Main Navigation -->
                <div class="col-md-4">
                    <h5 class="mb-3 text-primary">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Courses</a></li>
                        <li><a href="#">Teachers</a></li>
                        <li><a href="#">Students</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>

                <!-- Developer / Contact -->
                <div class="col-md-4 text-md-end">
                    <h5 class="mb-3 text-primary">Contact</h5>
                    <p class="mb-1">&copy; 2025 LMS System — All Rights Reserved</p>
                    <small>Developed by Your Name</small>
                </div>

            </div>

        </div>
    </footer>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const carousel = new bootstrap.Carousel('#lmsCarousel', {
            interval: 3000,
            touch: true
        });
    </script>

</body>

</html>

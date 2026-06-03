@extends('welcome')
@section('content')

<!-- ============================================================
     HERO
============================================================ -->
<section class="hero-section position-relative">

    <div class="hero-content text-center">
        <div class="hero-badge">
            <i class="bi bi-mortarboard-fill"></i> Trusted by 500+ Students
        </div>
        <h1 class="hero-title">
            Learn Smarter.<br>
            <span class="highlight">Grow Faster.</span>
        </h1>
        <p class="hero-subtitle">
            Manage courses, track attendance, submit assignments, and connect with teachers —
            all from one powerful platform.
        </p>
        <div class="hero-cta-group">
            <a href="{{ route('register') }}" class="btn-hero-primary">
                <i class="bi bi-person-plus-fill"></i> Enroll Now
            </a>
            <a href="{{ route('courses.index') }}" class="btn-hero-outline">
                <i class="bi bi-play-circle"></i> Explore Courses
            </a>
        </div>
    </div>

    <!-- Carousel -->
    <div id="lmsCarousel" class="carousel slide h-100" data-bs-ride="carousel">
        <div class="carousel-inner h-100">
            <div class="carousel-item active h-100">
                <img src="{{ asset('images/learning-education-ideas-insight-intelligence-study-concept.jpg') }}"
                     class="d-block w-100 hero-img" alt="Learning">
            </div>
            <div class="carousel-item h-100">
                <img src="{{ asset('images/city-committed-education-collage-concept.jpg') }}"
                     class="d-block w-100 hero-img" alt="Education">
            </div>
            <div class="carousel-item h-100">
                <img src="{{ asset('images/4144413.jpg') }}"
                     class="d-block w-100 hero-img" alt="Study">
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

<!-- ============================================================
     STATS BAR
============================================================ -->
<div class="stats-bar">
    <div class="container">
        <div class="row align-items-center justify-content-center g-3 g-md-0">

            <div class="col-6 col-md">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Students Enrolled</div>
                </div>
            </div>

            <div class="col-auto d-none d-md-block">
                <div class="stat-sep"></div>
            </div>

            <div class="col-6 col-md">
                <div class="stat-item">
                    <div class="stat-number">40+</div>
                    <div class="stat-label">Active Courses</div>
                </div>
            </div>

            <div class="col-auto d-none d-md-block">
                <div class="stat-sep"></div>
            </div>

            <div class="col-6 col-md">
                <div class="stat-item">
                    <div class="stat-number">25+</div>
                    <div class="stat-label">Expert Teachers</div>
                </div>
            </div>

            <div class="col-auto d-none d-md-block">
                <div class="stat-sep"></div>
            </div>

            <div class="col-6 col-md">
                <div class="stat-item">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Satisfaction Rate</div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ============================================================
     POPULAR COURSES
============================================================ -->
<section class="py-5" style="padding: 5rem 0 !important;">
    <div class="container">

        <div class="section-header">
            <span class="section-label">What We Offer</span>
            <h2 class="section-title">Popular Courses</h2>
            <p class="section-subtitle">
                Discover top-rated courses designed and delivered by expert instructors.
            </p>
        </div>

        <div class="row g-4">

            <!-- Course 1 -->
            <div class="col-md-4">
                <div class="course-card-new">
                    <div class="course-img-wrap">
                        <span class="course-cat-badge">Development</span>
                        <span class="course-price-badge">Free</span>
                        <img src="{{ asset('images/learning-education-ideas-insight-intelligence-study-concept.jpg') }}"
                             alt="Web Development Basics">
                    </div>
                    <div class="course-card-body-new">
                        <h5 class="course-card-title-new">Web Development Basics</h5>
                        <p class="course-card-desc-new">
                            Learn HTML, CSS, and JavaScript fundamentals to build modern, responsive websites from scratch.
                        </p>
                        <div class="course-instructor-row">
                            <div class="inst-avatar">JD</div>
                            <span class="inst-name">John Doe</span>
                        </div>
                        <div class="course-meta-row">
                            <span><i class="bi bi-clock me-1"></i>10 hours</span>
                            <span><i class="bi bi-journal-bookmark me-1"></i>18 lessons</span>
                            <span><i class="bi bi-people me-1"></i>124 students</span>
                        </div>
                        <a href="#" class="btn-enroll-new">
                            <i class="bi bi-arrow-right me-1"></i> View Course
                        </a>
                    </div>
                </div>
            </div>

            <!-- Course 2 -->
            <div class="col-md-4">
                <div class="course-card-new">
                    <div class="course-img-wrap">
                        <span class="course-cat-badge">Design</span>
                        <span class="course-price-badge">$49</span>
                        <img src="{{ asset('images/4144413.jpg') }}"
                             alt="Graphic Design Essentials">
                    </div>
                    <div class="course-card-body-new">
                        <h5 class="course-card-title-new">Graphic Design Essentials</h5>
                        <p class="course-card-desc-new">
                            Master layout, typography, and color theory using industry-standard design tools.
                        </p>
                        <div class="course-instructor-row">
                            <div class="inst-avatar">SS</div>
                            <span class="inst-name">Sarah Smith</span>
                        </div>
                        <div class="course-meta-row">
                            <span><i class="bi bi-clock me-1"></i>8 hours</span>
                            <span><i class="bi bi-journal-bookmark me-1"></i>14 lessons</span>
                            <span><i class="bi bi-people me-1"></i>89 students</span>
                        </div>
                        <a href="#" class="btn-enroll-new">
                            <i class="bi bi-arrow-right me-1"></i> View Course
                        </a>
                    </div>
                </div>
            </div>

            <!-- Course 3 -->
            <div class="col-md-4">
                <div class="course-card-new">
                    <div class="course-img-wrap">
                        <span class="course-cat-badge">Business</span>
                        <span class="course-price-badge">Popular</span>
                        <img src="{{ asset('images/city-committed-education-collage-concept.jpg') }}"
                             alt="Business Management">
                    </div>
                    <div class="course-card-body-new">
                        <h5 class="course-card-title-new">Business Management</h5>
                        <p class="course-card-desc-new">
                            Learn essential strategies to plan, organize, and grow successful businesses effectively.
                        </p>
                        <div class="course-instructor-row">
                            <div class="inst-avatar">MW</div>
                            <span class="inst-name">Mark Wilson</span>
                        </div>
                        <div class="course-meta-row">
                            <span><i class="bi bi-clock me-1"></i>12 hours</span>
                            <span><i class="bi bi-journal-bookmark me-1"></i>20 lessons</span>
                            <span><i class="bi bi-people me-1"></i>210 students</span>
                        </div>
                        <a href="#" class="btn-enroll-new">
                            <i class="bi bi-arrow-right me-1"></i> View Course
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <div class="text-center mt-4">
            <a href="{{ route('courses.index') }}"
               class="btn px-4 py-2 fw-semibold"
               style="border: 2px solid var(--color-primary); color: var(--color-primary); border-radius: 50px;">
                View All Courses <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

    </div>
</section>

<!-- ============================================================
     TOP CATEGORIES
============================================================ -->
<section class="categories-section">
    <div class="container">

        <div class="section-header">
            <span class="section-label">Browse by Topic</span>
            <h2 class="section-title">Top Categories</h2>
            <p class="section-subtitle">
                Choose from a wide range of subjects taught by passionate experts.
            </p>
        </div>

        <div class="row g-3">

            <div class="col-6 col-md-4 col-lg-2">
                <div class="cat-card">
                    <div class="cat-icon" style="background:#EEF2FF;">
                        <i class="bi bi-code-slash" style="color:#4F46E5;"></i>
                    </div>
                    <h6>Development</h6>
                    <small>12 Courses</small>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <div class="cat-card">
                    <div class="cat-icon" style="background:#ECFDF5;">
                        <i class="bi bi-flask" style="color:#10B981;"></i>
                    </div>
                    <h6>Science</h6>
                    <small>8 Courses</small>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <div class="cat-card">
                    <div class="cat-icon" style="background:#FFF7ED;">
                        <i class="bi bi-graph-up-arrow" style="color:#F59E0B;"></i>
                    </div>
                    <h6>Business</h6>
                    <small>10 Courses</small>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <div class="cat-card">
                    <div class="cat-icon" style="background:#FFF1F2;">
                        <i class="bi bi-translate" style="color:#F43F5E;"></i>
                    </div>
                    <h6>Languages</h6>
                    <small>6 Courses</small>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <div class="cat-card">
                    <div class="cat-icon" style="background:#F0F9FF;">
                        <i class="bi bi-palette" style="color:#0EA5E9;"></i>
                    </div>
                    <h6>Design</h6>
                    <small>9 Courses</small>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <div class="cat-card">
                    <div class="cat-icon" style="background:#FAF5FF;">
                        <i class="bi bi-calculator" style="color:#8B5CF6;"></i>
                    </div>
                    <h6>Mathematics</h6>
                    <small>7 Courses</small>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================
     TESTIMONIALS
============================================================ -->
<section class="testimonials-section">
    <div class="container">

        <div class="section-header">
            <span class="section-label">Student Stories</span>
            <h2 class="section-title">What Our Students Say</h2>
            <p class="section-subtitle">
                Real feedback from learners who transformed their skills with MyLMS.
            </p>
        </div>

        <div class="row g-4">

            <!-- Testimonial 1 -->
            <div class="col-md-4">
                <div class="testi-card">
                    <div class="testi-quote-mark">"</div>
                    <div class="testi-stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="testi-text">
                        "This LMS completely changed the way I study. The course materials are well-organized
                        and the teachers are very supportive. I passed my exams with confidence!"
                    </p>
                    <div class="testi-author">
                        <div class="testi-avatar" style="background: linear-gradient(135deg,#4F46E5,#7C3AED);">AK</div>
                        <div>
                            <div class="testi-author-name">Ali Khan</div>
                            <div class="testi-author-role">B.Sc. Computer Science</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="col-md-4">
                <div class="testi-card">
                    <div class="testi-quote-mark">"</div>
                    <div class="testi-stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                    </div>
                    <p class="testi-text">
                        "Managing students and tracking attendance has never been easier.
                        The admin dashboard gives me full control and saves hours every week."
                    </p>
                    <div class="testi-author">
                        <div class="testi-avatar" style="background: linear-gradient(135deg,#10B981,#06B6D4);">SA</div>
                        <div>
                            <div class="testi-author-name">Sara Ahmad</div>
                            <div class="testi-author-role">High School Administrator</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="col-md-4">
                <div class="testi-card">
                    <div class="testi-quote-mark">"</div>
                    <div class="testi-stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="testi-text">
                        "Uploading materials, creating assignments, and grading submissions — everything
                        is seamless. Perfect for any educational institution."
                    </p>
                    <div class="testi-author">
                        <div class="testi-avatar" style="background: linear-gradient(135deg,#F59E0B,#EF4444);">AR</div>
                        <div>
                            <div class="testi-author-name">Ahmed Raza</div>
                            <div class="testi-author-role">College Lecturer</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================
     CTA SECTION
============================================================ -->
<section class="cta-section">
    <div class="container" style="position: relative; z-index: 1;">
        <h2 class="fw-bold">Ready to Start Learning?</h2>
        <p>
            Join hundreds of students and educators already using MyLMS to achieve
            their academic goals. Registration is quick and easy.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('register') }}" class="btn-cta-white">
                <i class="bi bi-person-plus-fill"></i> Create Free Account
            </a>
            <a href="{{ route('courses.index') }}"
               class="btn-hero-outline" style="position:relative; z-index:1;">
                <i class="bi bi-book-half"></i> Browse Courses
            </a>
        </div>
    </div>
</section>

@endsection

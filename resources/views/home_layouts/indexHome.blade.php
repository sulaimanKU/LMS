@extends('welcome')
@section('content')

<!-- ============================================================
     HERO
============================================================ -->
<section class="hero-section position-relative">

    <div class="hero-content text-center">
        <div class="hero-badge">
            <i class="bi bi-mortarboard-fill"></i> Trusted by {{ $stats['students'] }}+ Students
        </div>
        <h1 class="hero-title">
            @if(isset($systemSettings['site_hero_title']))
                {!! nl2br(e($systemSettings['site_hero_title'])) !!}
            @else
                Learn Smarter.<br>
                <span class="highlight">Grow Faster.</span>
            @endif
        </h1>
        <p class="hero-subtitle">
            {{ $systemSettings['site_hero_subtitle'] ?? 'Manage courses, track attendance, submit assignments, and connect with teachers — all from one powerful platform.' }}
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
                    <div class="stat-number">{{ $stats['students'] }}+</div>
                    <div class="stat-label">Students Enrolled</div>
                </div>
            </div>

            <div class="col-auto d-none d-md-block">
                <div class="stat-sep"></div>
            </div>

            <div class="col-6 col-md">
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['courses'] }}+</div>
                    <div class="stat-label">Active Courses</div>
                </div>
            </div>

            <div class="col-auto d-none d-md-block">
                <div class="stat-sep"></div>
            </div>

            <div class="col-6 col-md">
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['teachers'] }}+</div>
                    <div class="stat-label">Expert Teachers</div>
                </div>
            </div>

            <div class="col-auto d-none d-md-block">
                <div class="stat-sep"></div>
            </div>

            <div class="col-6 col-md">
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['satisfaction'] }}%</div>
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

            @foreach($popularCourses as $course)
            <div class="col-md-4">
                <div class="course-card-new">
                    <div class="course-img-wrap">
                        <span class="course-cat-badge">{{ $course->category }}</span>
                        <span class="course-price-badge">{{ $course->price > 0 ? '$'.number_format($course->price, 0) : 'Free' }}</span>
                        <img src="{{ $course->image ? asset('storage/'.$course->image) : asset('images/learning-education-ideas-insight-intelligence-study-concept.jpg') }}"
                             alt="{{ $course->title }}">
                    </div>
                    <div class="course-card-body-new">
                        <h5 class="course-card-title-new">{{ $course->title }}</h5>
                        <p class="course-card-desc-new">
                            {{ \Illuminate\Support\Str::limit($course->short_description, 100) }}
                        </p>
                        @php
                            $instructor = $course->teacher->first();
                            $initials = '';
                            if ($instructor) {
                                $names = explode(' ', $instructor->name);
                                foreach ($names as $name) {
                                    $initials .= strtoupper(substr($name, 0, 1));
                                }
                                $initials = substr($initials, 0, 2);
                            }
                        @endphp
                        <div class="course-instructor-row">
                            <div class="inst-avatar">{{ $initials ?: '?' }}</div>
                            <span class="inst-name">{{ $instructor->name ?? 'Unknown' }}</span>
                        </div>
                        <div class="course-meta-row">
                            <span><i class="bi bi-clock me-1"></i>{{ $course->duration }}</span>
                            <span><i class="bi bi-journal-bookmark me-1"></i>{{ $course->lessons->count() }} lessons</span>
                            <span><i class="bi bi-people me-1"></i>{{ $course->enrollments_count }} students</span>
                        </div>
                        <a href="{{ route('courses.index') }}" class="btn-enroll-new">
                            <i class="bi bi-arrow-right me-1"></i> View Course
                        </a>
                    </div>
                </div>
            </div>
            @endforeach

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

            @foreach($reviews as $review)
            <div class="col-md-4">
                <div class="testi-card">
                    <div class="testi-quote-mark">"</div>
                    <div class="testi-stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                <i class="bi bi-star-fill"></i>
                            @else
                                <i class="bi bi-star"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="testi-text">
                        "{{ $review->content }}"
                    </p>
                    <div class="testi-author">
                        @php
                            $names = explode(' ', $review->name);
                            $initials = '';
                            foreach($names as $n) $initials .= strtoupper(substr($n, 0, 1));
                            $initials = substr($initials, 0, 2);
                            
                            // Random gradient for variety
                            $gradients = [
                                'linear-gradient(135deg,#4F46E5,#7C3AED)',
                                'linear-gradient(135deg,#10B981,#06B6D4)',
                                'linear-gradient(135deg,#F59E0B,#EF4444)',
                                'linear-gradient(135deg,#EC4899,#8B5CF6)',
                                'linear-gradient(135deg,#3B82F6,#2DD4BF)'
                            ];
                            $bg = $gradients[$review->id % count($gradients)];
                        @endphp
                        <div class="testi-avatar" style="background: {{ $bg }};">{{ $initials }}</div>
                        <div>
                            <div class="testi-author-name">{{ $review->name }}</div>
                            <div class="testi-author-role">{{ $review->role }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            @if($reviews->isEmpty())
                <div class="col-12 text-center text-muted py-4">
                    <p>No student stories shared yet. Be the first to review!</p>
                </div>
            @endif

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

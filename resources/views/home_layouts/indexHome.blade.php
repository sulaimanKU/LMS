@extends('welcome')

@section('content')
<!-- Premium Font -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --brand-indigo: #6366F1;
        --brand-indigo-dark: #4F46E5;
        --slate-900: #0F172A;
        --slate-800: #1E293B;
        --slate-600: #475569;
        --slate-400: #94A3B8;
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── Premium Aurora Hero ── */
    .hero-premium {
        background-color: var(--slate-900);
        background-image: 
            radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.15) 0%, transparent 40%),
            radial-gradient(circle at 90% 80%, rgba(79, 70, 229, 0.15) 0%, transparent 40%);
        padding: 8rem 0 7rem;
        position: relative;
        overflow: hidden;
        color: #fff;
    }
    .hero-premium::after {
        content: ""; position: absolute; bottom: 0; left: 0; width: 100%; height: 100px;
        background: linear-gradient(to top, #fff, transparent);
    }
    .badge-premium {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
        padding: 8px 20px; border-radius: 50px; font-size: 0.75rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: 1.5px; color: #A5B4FC; margin-bottom: 2rem;
        backdrop-filter: blur(10px);
    }
    .hero-h1 { 
        font-size: clamp(2.8rem, 6vw, 4.5rem); font-weight: 800; margin-bottom: 1.5rem; line-height: 1; 
        background: linear-gradient(to bottom, #FFFFFF 40%, #94A3B8 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .hero-h1 span { 
        background: linear-gradient(135deg, #818CF8 0%, #6366F1 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .hero-p { font-size: 1.2rem; color: var(--slate-400); max-width: 700px; margin: 0 auto 2.5rem; line-height: 1.6; }

    .btn-brand-primary {
        padding: 16px 36px; background: var(--brand-indigo); color: #fff;
        border: none; border-radius: 14px; font-weight: 700; font-size: 1.05rem;
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4); transition: all 0.3s;
        text-decoration: none; display: inline-flex; align-items: center; gap: 10px;
    }
    .btn-brand-primary:hover { transform: translateY(-3px); box-shadow: 0 20px 35px rgba(99, 102, 241, 0.5); color: #fff; }

    .btn-brand-outline {
        padding: 16px 36px; background: rgba(255,255,255,0.05); color: #fff;
        border: 1px solid rgba(255,255,255,0.2); border-radius: 14px; font-weight: 700; font-size: 1.05rem;
        transition: all 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 10px;
        backdrop-filter: blur(10px);
    }
    .btn-brand-outline:hover { background: rgba(255,255,255,0.1); border-color: #fff; color: #fff; }

    /* ── High-Fidelity Stats Bar ── */
    .stats-premium { margin-top: -50px; position: relative; z-index: 10; }
    .stats-container {
        background: #fff; border: 1px solid #E5E7EB; border-radius: 24px;
        padding: 2.5rem; box-shadow: 0 20px 50px rgba(0,0,0,0.05);
    }
    .stat-val { font-size: 2.2rem; font-weight: 800; color: var(--slate-900); margin-bottom: 2px; }
    .stat-name { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: var(--slate-400); letter-spacing: 1px; }

    /* ── International Card Design ── */
    .course-card-premium {
        background: #fff; border-radius: 24px; border: 1px solid #E5E7EB;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%; display: flex; flex-direction: column; overflow: hidden;
    }
    .course-card-premium:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.12);
        border-color: var(--brand-indigo);
    }
    .course-img-box {
        position: relative; width: 100%; padding-top: 56.25%; /* 16:9 */
        overflow: hidden; background: #F1F5F9;
    }
    .course-img-box img {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        object-fit: cover; transition: transform 0.6s ease;
    }
    .course-card-premium:hover .course-img-box img { transform: scale(1.08); }

    .badge-cat {
        position: absolute; top: 1.25rem; left: 1.25rem; z-index: 2;
        background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(8px);
        color: #fff; padding: 5px 14px; border-radius: 10px; font-size: 0.7rem; font-weight: 700;
    }

    .course-body-premium { padding: 1.75rem; display: flex; flex-direction: column; flex: 1; }
    .course-title-premium { font-size: 1.25rem; font-weight: 800; color: var(--slate-900); margin-bottom: 0.75rem; line-height: 1.4; }
    .course-desc-premium { font-size: 0.9rem; color: var(--slate-600); line-height: 1.6; margin-bottom: 1.5rem; flex: 1; }

    .course-meta-premium { display: flex; align-items: center; gap: 15px; padding-top: 1.25rem; border-top: 1px solid #F1F5F9; }
    .meta-item-p { display: flex; align-items: center; gap: 6px; font-size: 0.75rem; font-weight: 700; color: var(--slate-400); }
    .meta-item-p i { color: var(--brand-indigo); font-size: 0.9rem; }

    .btn-view-premium {
        width: 100%; padding: 12px; background: #F1F5F9; color: var(--brand-indigo);
        border: none; border-radius: 12px; font-weight: 700; font-size: 0.9rem;
        transition: all 0.2s; margin-top: 1.5rem; display: flex; align-items: center; justify-content: center; gap: 8px;
        text-decoration: none;
    }
    .btn-view-premium:hover { background: var(--brand-indigo); color: #fff; }

    /* ── Testimonials ── */
    .testi-premium-card {
        background: #fff; padding: 2.5rem; border-radius: 24px; border: 1px solid #E5E7EB;
        position: relative; height: 100%;
    }
    .quote-icon { font-size: 3rem; color: var(--brand-indigo-dark); opacity: 0.1; position: absolute; top: 1rem; right: 2rem; }
    .testi-stars-p { color: #F59E0B; font-size: 0.8rem; margin-bottom: 1rem; display: flex; gap: 3px; }
    .testi-text-p { font-size: 1rem; line-height: 1.7; color: var(--slate-600); margin-bottom: 1.5rem; font-style: italic; }

    .testi-user { display: flex; align-items: center; gap: 12px; }
    .user-avatar-p { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 800; font-size: 0.9rem; }

    @media (max-width: 768px) {
        .hero-premium { padding: 6rem 0 5rem; text-align: center; }
        .stats-container { padding: 1.5rem; }
        .stat-val { font-size: 1.6rem; }
    }
</style>

{{-- ── Hero Section ── --}}
<section class="hero-premium">
    <div class="container text-center">
        <div class="badge-premium">
            <i class="bi bi-shield-check"></i> Globally Trusted Learning Platform
        </div>
        <h1 class="hero-h1">
            @if(isset($systemSettings['site_hero_title']))
                {!! nl2br(e($systemSettings['site_hero_title'])) !!}
            @else
                Master Your Future.<br><span>Excel Globally.</span>
            @endif
        </h1>
        <p class="hero-p">
            {{ $systemSettings['site_hero_subtitle'] ?? 'Experience next-generation academic training with expert mentors and industry-standard research modules.' }}
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('register') }}" class="btn-brand-primary">
                Get Started Today <i class="bi bi-arrow-right"></i>
            </a>
            <a href="{{ route('courses.index') }}" class="btn-brand-outline">
                Explore Modules
            </a>
        </div>
    </div>
</section>

{{-- ── Stats Section ── --}}
<section class="stats-premium">
    <div class="container">
        <div class="stats-container">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <div class="stat-val">{{ $stats['students'] }}+</div>
                    <div class="stat-name">Active Learners</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-val">{{ $stats['courses'] }}+</div>
                    <div class="stat-name">Premium Modules</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-val">{{ $stats['teachers'] }}+</div>
                    <div class="stat-name">Expert Faculty</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-val">{{ $stats['satisfaction'] }}%</div>
                    <div class="stat-name">Global Success</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Popular Courses Section ── --}}
<section class="py-5" style="padding: 7rem 0 !important;">
    <div class="container">
        <div class="mb-5 text-center">
            <span style="color: var(--brand-indigo); font-weight: 800; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 2px;">Academy Spotlight</span>
            <h2 class="fw-bold mt-2" style="font-size: 2.5rem; color: var(--slate-900);">Featured Modules</h2>
            <p class="text-muted mx-auto" style="max-width: 500px;">Begin your professional journey with our most sought-after training programs.</p>
        </div>

        <div class="row g-4">
            @foreach($popularCourses as $course)
            <div class="col-md-6 col-lg-4">
                <div class="course-card-premium">
                    <div class="course-img-box">
                        <span class="badge-cat">{{ $course->category }}</span>
                        <img src="{{ $course->image ? asset('storage/'.$course->image) : 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=800&q=80' }}"
                             alt="{{ $course->title }}">
                    </div>
                    <div class="course-body-premium">
                        <h5 class="course-title-premium">{{ $course->title }}</h5>
                        <p class="course-desc-premium">
                            {{ \Illuminate\Support\Str::limit(strip_tags($course->short_description), 100) }}
                        </p>
                        
                        <div class="course-meta-premium">
                            <div class="meta-item-p"><i class="bi bi-clock"></i> {{ $course->duration }}</div>
                            <div class="meta-item-p"><i class="bi bi-people"></i> {{ $course->enrollments_count }}</div>
                            <div class="meta-item-p" style="margin-left: auto; color: var(--brand-indigo); font-size: 0.9rem;">PKR {{ number_format($course->price, 0) }}</div>
                        </div>

                        <a href="{{ route('course.show', $course->id) }}" class="btn-view-premium">
                            View Module <i class="bi bi-arrow-up-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Testimonials Section ── --}}
<section class="py-5" style="padding: 5rem 0 8rem !important; background: #F8FAFF;">
    <div class="container">
        <div class="mb-5 text-center">
            <span style="color: var(--brand-indigo); font-weight: 800; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 2px;">Global Feedback</span>
            <h2 class="fw-bold mt-2" style="font-size: 2.5rem; color: var(--slate-900);">Student Stories</h2>
        </div>

        <div class="row g-4">
            @foreach($reviews as $review)
            <div class="col-md-6 col-lg-4">
                <div class="testi-premium-card">
                    <i class="bi bi-quote quote-icon"></i>
                    <div class="testi-stars-p">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star-fill"></i>
                        @endfor
                    </div>
                    <p class="testi-text-p">"{{ $review->content }}"</p>
                    
                    <div class="testi-user">
                        @php
                            $names = explode(' ', $review->name);
                            $initials = '';
                            foreach($names as $n) $initials .= strtoupper(substr($n, 0, 1));
                            $initials = substr($initials, 0, 2);
                            $gradients = ['#6366F1', '#10B981', '#F59E0B', '#EC4899', '#3B82F6'];
                            $bg = $gradients[$review->id % count($gradients)];
                        @endphp
                        <div class="user-avatar-p" style="background: {{ $bg }};">{{ $initials }}</div>
                        <div>
                            <div style="font-size: 0.9rem; font-weight: 800; color: var(--slate-900);">{{ $review->name }}</div>
                            <div style="font-size: 0.75rem; font-weight: 600; color: var(--slate-400);">{{ $review->role }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Final CTA ── --}}
<section class="py-5" style="background: var(--brand-indigo); color: #fff; padding: 6rem 0 !important;">
    <div class="container text-center">
        <h2 class="fw-bold mb-4" style="font-size: 2.5rem;">Join the Global Research Community</h2>
        <p class="opacity-75 mb-5 mx-auto" style="max-width: 600px;">Take the first step towards academic excellence. Enroll now and gain instant access to our expert-led modules.</p>
        <a href="{{ route('register') }}" class="btn btn-light rounded-pill px-5 py-3 fw-bold" style="color: var(--brand-indigo); box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            Join Academy
        </a>
    </div>
</section>

@endsection

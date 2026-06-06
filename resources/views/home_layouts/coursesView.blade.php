@extends('welcome')

@section('content')
<!-- Add Premium Font -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --intl-primary: #4F46E5;
        --intl-primary-soft: #EEF2FF;
        --intl-slate-900: #0F172A;
        --intl-slate-600: #475569;
        --intl-slate-400: #94A3B8;
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── High-End Hero ── */
    .intl-courses-hero {
        background: #0B0F19;
        background-image: 
            radial-gradient(circle at 20% 30%, rgba(99, 102, 241, 0.15), transparent 500px),
            radial-gradient(circle at 80% 70%, rgba(79, 70, 229, 0.15), transparent 500px);
        padding: 7rem 0 6rem;
        position: relative;
        color: #fff;
        text-rendering: optimizeLegibility;
        -webkit-font-smoothing: antialiased;
    }
    .intl-hero-label {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.3);
        padding: 8px 20px; border-radius: 50px; font-size: 0.75rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: 1.5px; color: #C7D2FE; margin-bottom: 2rem;
        box-shadow: 0 0 20px rgba(99, 102, 241, 0.1);
    }
    .intl-hero-title { 
        font-size: clamp(2.8rem, 6vw, 4rem); font-weight: 800; margin-bottom: 1.25rem; line-height: 1; 
        background: linear-gradient(to bottom, #FFFFFF 30%, #94A3B8 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(0 2px 10px rgba(0,0,0,0.2));
    }
    .intl-hero-title span { 
        background: linear-gradient(135deg, #818CF8 0%, #6366F1 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .intl-hero-sub { font-size: 1.15rem; color: #94A3B8; max-width: 650px; margin: 0 auto; line-height: 1.6; font-weight: 500; }

    /* ── Premium Grid ── */
    .intl-grid-section { background: #F8FAFF; padding: 5rem 0; }
    
    /* ── International Standard Card ── */
    .intl-course-card {
        background: #fff; border-radius: 20px; border: 1px solid #E5E7EB;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%; display: flex; flex-direction: column; overflow: hidden;
        position: relative;
    }
    .intl-course-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.1);
        border-color: var(--intl-primary);
    }

    /* Fixed Aspect Ratio for Non-Blurry, Adjusted Images */
    .intl-img-container {
        position: relative; width: 100%; padding-top: 56.25%; /* 16:9 Aspect Ratio */
        overflow: hidden; background: #F1F5F9;
    }
    .intl-img-container img {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        object-fit: cover; transition: transform 0.6s ease;
    }
    .intl-course-card:hover .intl-img-container img { transform: scale(1.08); }

    .intl-badge-cat {
        position: absolute; top: 1rem; left: 1rem; z-index: 2;
        background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(8px);
        color: #fff; padding: 4px 12px; border-radius: 8px; font-size: 0.7rem; font-weight: 700;
    }
    .intl-badge-price {
        position: absolute; top: 1rem; right: 1rem; z-index: 2;
        background: var(--intl-primary); color: #fff; padding: 4px 12px;
        border-radius: 8px; font-size: 0.75rem; font-weight: 800;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    .intl-card-body { padding: 1.5rem; display: flex; flex-direction: column; flex: 1; }
    .intl-card-title { font-size: 1.15rem; font-weight: 800; color: var(--intl-slate-900); margin-bottom: 0.75rem; line-height: 1.4; transition: color 0.2s; }
    .intl-card-title a { text-decoration: none; color: inherit; }
    .intl-card-title a:hover { color: var(--intl-primary); }
    
    .intl-card-desc { font-size: 0.88rem; color: var(--intl-slate-600); line-height: 1.6; margin-bottom: 1.5rem; flex: 1; }

    .intl-meta-row { display: flex; align-items: center; gap: 15px; margin-bottom: 1.5rem; border-top: 1px solid #F1F5F9; padding-top: 1rem; }
    .intl-meta-item { display: flex; align-items: center; gap: 6px; font-size: 0.78rem; font-weight: 600; color: var(--intl-slate-400); }
    .intl-meta-item i { color: var(--intl-primary); font-size: 0.9rem; }

    .intl-instructor { display: flex; align-items: center; gap: 10px; margin-bottom: 1.5rem; }
    .intl-inst-avatar { 
        width: 32px; height: 32px; border-radius: 50%; background: var(--intl-primary-soft);
        color: var(--intl-primary); display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: 800; border: 1px solid rgba(79, 70, 229, 0.1);
    }
    .intl-inst-name { font-size: 0.82rem; font-weight: 700; color: var(--intl-slate-900); }

    .intl-actions { display: flex; gap: 10px; }
    .btn-intl-enroll {
        flex: 2; padding: 10px; background: var(--intl-primary); color: #fff;
        border: none; border-radius: 10px; font-weight: 700; font-size: 0.85rem;
        transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 6px;
    }
    .btn-intl-enroll:hover { background: #4338CA; transform: scale(1.02); color: #fff; }
    
    .btn-intl-details {
        flex: 1; padding: 10px; background: #fff; color: var(--intl-slate-600);
        border: 1.5px solid #E5E7EB; border-radius: 10px; font-weight: 700; font-size: 0.85rem;
        transition: all 0.2s; display: flex; align-items: center; justify-content: center;
    }
    .btn-intl-details:hover { border-color: var(--intl-primary); color: var(--intl-primary); background: var(--intl-primary-soft); }

    @media (max-width: 768px) {
        .intl-courses-hero { padding: 4rem 0; text-align: center; }
    }
</style>

{{-- ── Page Hero ── --}}
<section class="intl-courses-hero">
    <div class="container text-center">
        <div class="intl-hero-label">
            <i class="bi bi-rocket-takeoff-fill"></i> International Standard Learning
        </div>
        <h1 class="intl-hero-title">Academic Training<br><span>Programmes</span></h1>
        <p class="intl-hero-sub">
            Master the art of research with our globally recognized training modules.
            Professional certifications designed for the modern scholar.
        </p>
    </div>
</section>

{{-- ── Grid Section ── --}}
<section class="intl-grid-section">
    <div class="container">
        
        @if($courses->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3"><i class="bi bi-search" style="font-size: 3rem; color: var(--intl-slate-400);"></i></div>
                <h4 class="fw-bold">No Modules Found</h4>
                <p class="text-muted">We're updating our curriculum. Please check back shortly!</p>
            </div>
        @else
            <div class="row g-4">
                @foreach ($courses as $course)
                    @php 
                        $teacher = $course->teacher->first();
                        $fallbackImg = 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=800&q=80';
                    @endphp
                    <div class="col-md-6 col-lg-4">
                        <div class="intl-course-card">
                            
                            {{-- Image Container with Fixed Ratio --}}
                            <div class="intl-img-container">
                                <span class="intl-badge-cat">{{ $course->category }}</span>
                                <span class="intl-badge-price">PKR {{ number_format($course->price, 0) }}</span>
                                <img src="{{ $course->image ? asset('storage/' . $course->image) : $fallbackImg }}" 
                                     alt="{{ $course->title }}">
                            </div>

                            <div class="intl-card-body">
                                <h5 class="intl-card-title">
                                    <a href="{{ route('course.show', $course->id) }}">{{ $course->title }}</a>
                                </h5>
                                
                                <p class="intl-card-desc">
                                    {{ Str::limit(strip_tags($course->short_description), 100) }}
                                </p>

                                <div class="intl-instructor">
                                    <div class="intl-inst-avatar">
                                        @if($teacher)
                                            {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                        @else
                                            ?
                                        @endif
                                    </div>
                                    <span class="intl-inst-name">{{ $teacher->name ?? 'Guest Instructor' }}</span>
                                </div>

                                <div class="intl-meta-row">
                                    <div class="intl-meta-item">
                                        <i class="bi bi-clock"></i> {{ $course->duration }}
                                    </div>
                                    <div class="intl-meta-item">
                                        <i class="bi bi-book"></i> {{ $course->lessons_count }} Lessons
                                    </div>
                                    <div class="intl-meta-item">
                                        <i class="bi bi-people"></i> {{ $course->enrollments_count }} Enrolled
                                    </div>
                                </div>

                                <div class="intl-actions">
                                    <a href="{{ route('register') }}" class="btn-intl-enroll">
                                        <i class="bi bi-shield-check"></i> Enroll Now
                                    </a>
                                    <a href="{{ route('course.show', $course->id) }}" class="btn-intl-details" title="View Course Details">
                                        <i class="bi bi-info-circle"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Custom Pagination --}}
            <div class="mt-5 d-flex justify-content-center">
                {{ $courses->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>
</section>

{{-- ── CTA Section ── --}}
<section class="py-5 bg-white border-top">
    <div class="container text-center py-4">
        <h3 class="fw-bold mb-3">Institutional Partnerships</h3>
        <p class="text-muted mb-4 mx-auto" style="max-width: 500px;">We collaborate with top universities to bring you the best in research training and academic development.</p>
        <div class="d-flex justify-content-center gap-4 opacity-50 flex-wrap">
            <i class="bi bi-mortarboard fs-2"></i>
            <i class="bi bi-award fs-2"></i>
            <i class="bi bi-bank fs-2"></i>
            <i class="bi bi-globe fs-2"></i>
        </div>
    </div>
</section>

@endsection

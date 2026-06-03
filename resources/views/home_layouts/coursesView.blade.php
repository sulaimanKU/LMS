@extends('welcome')

@section('content')

{{-- ── Page Hero ── --}}
<section class="courses-hero">
    <div class="container">
        <div class="courses-hero-inner text-center">
            <span class="section-label">
                <i class="bi bi-mortarboard-fill me-1"></i>All Programmes
            </span>
            <h1 class="courses-hero-title">Research Training Modules</h1>
            <p class="courses-hero-sub">
                Advance your academic career with our expert-led, practice-focused programmes.
                From methodology to publication — we have you covered.
            </p>
            <div class="courses-stats-row">
                <div class="cs-stat"><span class="cs-num">{{ $courses->count() }}</span><span class="cs-lbl">Modules</span></div>
                <div class="cs-divider"></div>
                <div class="cs-stat"><span class="cs-num">100%</span><span class="cs-lbl">Online</span></div>
                <div class="cs-divider"></div>
                <div class="cs-stat"><span class="cs-num">Expert</span><span class="cs-lbl">Instructors</span></div>
            </div>
        </div>
    </div>
</section>

{{-- ── Course Grid ── --}}
<section class="py-5 bg-soft">
    <div class="container">

        @php
            $courseImages = [
                'Core Research'                               => 'https://images.unsplash.com/photo-1532619675605-1ede6c2ed2b0?auto=format&fit=crop&w=800&q=80',
                'Research Methodologies'                      => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=800&q=80',
                'Data Collection'                             => 'https://images.unsplash.com/photo-1504868584819-f8e8b4b6d7e3?auto=format&fit=crop&w=800&q=80',
                'Instrument Development and Validations'      => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80',
                'Digital Research'                            => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80',
                'Application of AI in Research'               => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&w=800&q=80',
                'Academic Leadership'                         => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=800&q=80',
                'Research Supervision and Mentorship'         => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=800&q=80',
                'Qualitative Data Analysis (Nvivo/Atlas)'     => 'https://images.unsplash.com/photo-1556761175-4b46a572b786?auto=format&fit=crop&w=800&q=80',
                'Quantitative Data Analysis (SPSS)'           => 'https://images.unsplash.com/photo-1508385082359-f38ae991e8f2?auto=format&fit=crop&w=800&q=80',
                'SEM Analysis (AMOS)'                         => 'https://images.unsplash.com/photo-1509228468518-180dd4864904?auto=format&fit=crop&w=800&q=80',
                'Research Writing & Publication'              => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=800&q=80',
                'Research Outreaches'                         => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80',
                'Education Tech'                              => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=800&q=80',
                'Effective Teaching & AI Tools'               => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=800&q=80',
            ];
            $fallback = 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80';
        @endphp

        @if($courses->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-journal-x" style="font-size:3rem; color:var(--color-muted);"></i>
                <p class="mt-3 text-muted">No modules are available yet. Check back soon!</p>
            </div>
        @else
            <div class="row g-4">
                @foreach ($courses as $course)
                    @php $teacher = $course->teacher->first(); @endphp
                    <div class="col-md-6 col-lg-4">
                        <div class="course-card-new">

                            {{-- Thumbnail --}}
                            <div class="course-img-wrap">
                                <span class="course-cat-badge">{{ $course->category }}</span>
                                <span class="course-price-badge">PKR {{ number_format($course->price, 0) }}</span>
                                @if($course->image)
                                    <img src="{{ asset('storage/' . $course->image) }}"
                                         alt="{{ $course->title }}" loading="lazy">
                                @else
                                    <img src="{{ $courseImages[$course->category] ?? $fallback }}"
                                         alt="{{ $course->title }}" loading="lazy">
                                @endif
                            </div>

                            {{-- Body --}}
                            <div class="course-card-body-new">
                                <h5 class="course-card-title-new">{{ $course->title }}</h5>

                                @if($course->short_description)
                                    <p class="course-card-desc-new">{{ Str::limit($course->short_description, 90) }}</p>
                                @endif

                                {{-- Meta --}}
                                <div class="course-meta-row">
                                    <span><i class="bi bi-clock me-1"></i>{{ $course->duration }}</span>
                                    @if($teacher)
                                        <span><i class="bi bi-person me-1"></i>{{ $teacher->name }}</span>
                                    @endif
                                </div>

                                {{-- Instructor row --}}
                                @if($teacher)
                                    <div class="course-instructor-row">
                                        <div class="inst-avatar">{{ strtoupper(substr($teacher->name, 0, 1)) }}</div>
                                        <div>
                                            <div class="inst-name">{{ $teacher->name }}</div>
                                            @if($teacher->designation)
                                                <div style="font-size:.75rem; color:var(--color-muted);">{{ $teacher->designation }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Actions --}}
                                <div class="course-actions-row">
                                    <a href="{{ route('register') }}" class="btn-enroll-new">
                                        <i class="bi bi-person-plus me-1"></i>Enroll Now
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Bottom CTA --}}
            <div class="text-center mt-5 pt-2">
                <p class="text-muted mb-3">Ready to advance your research career?</p>
                <a href="{{ route('register') }}" class="btn-cta-white" style="display:inline-flex;">
                    <i class="bi bi-arrow-right-circle me-2"></i>Register Now
                </a>
            </div>
        @endif

    </div>
</section>

<style>
/* ── Courses page hero ── */
.courses-hero {
    background: var(--gradient-primary);
    padding: 4rem 0 3.5rem;
    position: relative;
    overflow: hidden;
}
.courses-hero::before {
    content: '';
    position: absolute;
    top: -40%;
    right: -6%;
    width: 460px;
    height: 460px;
    background: rgba(255,255,255,.06);
    border-radius: 50%;
    pointer-events: none;
}
.courses-hero::after {
    content: '';
    position: absolute;
    bottom: -40%;
    left: -5%;
    width: 340px;
    height: 340px;
    background: rgba(255,255,255,.05);
    border-radius: 50%;
    pointer-events: none;
}
.courses-hero .section-label {
    color: rgba(255,255,255,.85);
    font-size: .78rem;
    margin-bottom: .75rem;
}
.courses-hero-title {
    font-size: clamp(1.8rem, 4vw, 2.8rem);
    font-weight: 800;
    color: #fff;
    margin-bottom: .85rem;
    position: relative;
    z-index: 1;
}
.courses-hero-sub {
    color: rgba(255,255,255,.82);
    font-size: 1rem;
    max-width: 560px;
    margin: 0 auto 2rem;
    line-height: 1.7;
    position: relative;
    z-index: 1;
}
.courses-stats-row {
    display: inline-flex;
    align-items: center;
    gap: 0;
    background: rgba(255,255,255,.12);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 50px;
    padding: .6rem 1.75rem;
    position: relative;
    z-index: 1;
}
.cs-stat { text-align: center; padding: 0 1.25rem; }
.cs-num { display: block; font-size: 1.35rem; font-weight: 800; color: #fff; line-height: 1.15; }
.cs-lbl { font-size: .72rem; color: rgba(255,255,255,.75); text-transform: uppercase; letter-spacing: .8px; }
.cs-divider { width: 1px; height: 40px; background: rgba(255,255,255,.25); flex-shrink: 0; }

/* ── Card actions ── */
.course-actions-row { margin-top: auto; padding-top: .75rem; }

@media (max-width: 576px) {
    .courses-stats-row { flex-direction: column; gap: .5rem; padding: 1rem 1.5rem; border-radius: 16px; }
    .cs-divider { width: 80%; height: 1px; }
}
</style>

@endsection

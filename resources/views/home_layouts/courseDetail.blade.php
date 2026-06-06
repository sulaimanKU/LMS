@extends('welcome')

@section('content')
<style>
    .cd-hero {
        background: linear-gradient(135deg, #1E1B4B 0%, #312E81 100%);
        padding: 5rem 0 4rem;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .cd-hero::before {
        content: ""; position: absolute; inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .cd-cat {
        display: inline-block; padding: 6px 16px; background: rgba(99, 102, 241, 0.2);
        border: 1px solid rgba(255,255,255,0.2); border-radius: 50px;
        font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
        margin-bottom: 1.5rem; backdrop-filter: blur(4px);
    }
    .cd-title { font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 800; margin-bottom: 1.5rem; line-height: 1.1; }
    .cd-meta-row { display: flex; flex-wrap: wrap; gap: 2rem; margin-top: 2rem; }
    .cd-meta-item { display: flex; align-items: center; gap: 10px; font-size: 1rem; color: #C7D2FE; }
    .cd-meta-item i { color: #fff; font-size: 1.2rem; }

    .cd-card {
        background: #fff; border-radius: 20px; border: 1px solid #E5E7EB;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); margin-top: -3rem;
        position: relative; z-index: 10; overflow: hidden;
    }
    .cd-content-section { padding: 3rem; }
    .cd-section-title { font-size: 1.5rem; font-weight: 800; color: #111827; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 12px; }
    .cd-section-title i { color: #6366F1; }
    
    .cd-details-text { font-size: 1.05rem; line-height: 1.8; color: #4B5563; }
    .cd-details-text h1, .cd-details-text h2, .cd-details-text h3 { color: #111827; margin-top: 2rem; margin-bottom: 1rem; font-weight: 800; }
    .cd-details-text h4, .cd-details-text h5, .cd-details-text h6 { color: #111827; margin-top: 1.5rem; margin-bottom: 0.75rem; font-weight: 700; }
    .cd-details-text p { margin-bottom: 1.25rem; }
    .cd-details-text ul, .cd-details-text ol { margin-bottom: 1.5rem; padding-left: 1.5rem; }
    .cd-details-text li { margin-bottom: 0.5rem; }
    .cd-details-text img { max-width: 100%; height: auto; border-radius: 12px; margin: 1.5rem 0; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .cd-details-text blockquote { border-left: 4px solid #6366F1; padding-left: 1.5rem; font-style: italic; color: #374151; margin: 2rem 0; }
    .cd-details-text table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; }
    .cd-details-text table th, .cd-details-text table td { border: 1px solid #E5E7EB; padding: 12px; font-size: 0.95rem; }
    .cd-details-text table th { background: #F9FAFB; font-weight: 700; }

    .cd-sidebar { position: sticky; top: 100px; }
    .cd-price-box {
        background: #fff; border-radius: 20px; border: 1px solid #E5E7EB;
        padding: 2rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        text-align: center;
    }
    .cd-price-tag { font-size: 2.5rem; font-weight: 800; color: #111827; margin-bottom: 1rem; }
    .btn-cd-enroll {
        width: 100%; padding: 16px; background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
        color: #fff; border: none; border-radius: 14px; font-weight: 700; font-size: 1.1rem;
        box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3); transition: 0.3s;
    }
    .btn-cd-enroll:hover { transform: translateY(-2px); box-shadow: 0 15px 30px rgba(79, 70, 229, 0.4); color: #fff; }

    .cd-curriculum-item {
        display: flex; align-items: center; gap: 15px; padding: 14px 20px;
        background: #F9FAFB; border-radius: 12px; margin-bottom: 10px; border: 1px solid #F3F4F6;
    }
    .cd-curr-num { width: 32px; height: 32px; background: #fff; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #6366F1; font-size: 0.9rem; }
    .cd-curr-title { font-size: 0.95rem; font-weight: 600; color: #374151; }

    .cd-instructor { display: flex; align-items: center; gap: 15px; margin-top: 1rem; padding: 1.5rem; background: #F8FAFF; border-radius: 16px; }
    .cd-inst-avatar { width: 60px; height: 60px; border-radius: 50%; background: #4F46E5; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 800; }
    .cd-inst-name { font-size: 1.1rem; font-weight: 700; color: #111827; margin: 0; }
    .cd-inst-role { font-size: 0.85rem; color: #6B7280; }

    @media (max-width: 768px) {
        .cd-content-section { padding: 2rem 1.5rem; }
        .cd-meta-row { gap: 1rem; }
    }
</style>

<section class="cd-hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <span class="cd-cat">{{ $course->category }}</span>
                <h1 class="cd-title">{{ $course->title }}</h1>
                <p class="lead opacity-75">{{ $course->short_description }}</p>
                
                <div class="cd-meta-row">
                    <div class="cd-meta-item">
                        <i class="bi bi-clock"></i>
                        <span>{{ $course->duration ?? 'Self-paced' }}</span>
                    </div>
                    <div class="cd-meta-item">
                        <i class="bi bi-journal-text"></i>
                        <span>{{ $course->lessons_count }} Lessons</span>
                    </div>
                    <div class="cd-meta-item">
                        <i class="bi bi-people"></i>
                        <span>{{ $course->enrollments_count }} Enrolled</span>
                    </div>
                    <div class="cd-meta-item">
                        <i class="bi bi-translate"></i>
                        <span>English</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pb-5">
    <div class="container">
        <div class="row g-4">
            
            {{-- Main Content --}}
            <div class="col-lg-8">
                <div class="cd-card">
                    @if($course->image)
                        <img src="{{ asset('storage/'.$course->image) }}" class="img-fluid w-100" style="max-height: 400px; object-fit: cover;" alt="Course Image">
                    @endif
                    
                    <div class="cd-content-section">
                        <h4 class="cd-section-title"><i class="bi bi-info-circle"></i> Course Description</h4>
                        <div class="cd-details-text">
                            @if($course->details)
                                {!! $course->details !!}
                            @else
                                <p class="text-muted italic">Detailed description for this course will be available soon.</p>
                            @endif
                        </div>

                        <hr class="my-5 border-light">

                        <h4 class="cd-section-title"><i class="bi bi-list-check"></i> Course Curriculum</h4>
                        <div class="cd-curriculum">
                            @forelse($course->lessons as $index => $lesson)
                                <div class="cd-curriculum-item">
                                    <div class="cd-curr-num">{{ $index + 1 }}</div>
                                    <div class="cd-curr-title">{{ $lesson->title }}</div>
                                    <div class="ms-auto"><i class="bi bi-lock-fill text-muted opacity-50"></i></div>
                                </div>
                            @empty
                                <p class="text-muted small">Curriculum details are being updated.</p>
                            @endforelse
                        </div>

                        <hr class="my-5 border-light">

                        <h4 class="cd-section-title"><i class="bi bi-person-badge"></i> Your Instructor</h4>
                        @forelse($course->teacher as $teacher)
                            <div class="cd-instructor">
                                <div class="cd-inst-avatar">
                                    @php
                                        $initials = '';
                                        $names = explode(' ', $teacher->name);
                                        foreach ($names as $n) $initials .= strtoupper(substr($n, 0, 1));
                                        $initials = substr($initials, 0, 2);
                                    @endphp
                                    {{ $initials }}
                                </div>
                                <div>
                                    <h6 class="cd-inst-name">{{ $teacher->name }}</h6>
                                    <p class="cd-inst-role">{{ $teacher->designation ?? 'Expert Instructor' }}</p>
                                    @if($teacher->bio)
                                        <p class="small text-muted mb-0">{{ Str::limit($teacher->bio, 150) }}</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small">No instructor assigned yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="cd-sidebar">
                    <div class="cd-price-box">
                        <div class="text-muted small mb-1 fw-bold">Full Course Access</div>
                        <div class="cd-price-tag">PKR {{ number_format($course->price, 0) }}</div>
                        
                        <a href="{{ route('register') }}" class="btn btn-cd-enroll mb-3">
                            Enroll in Course
                        </a>
                        
                        <ul class="list-unstyled text-start small mt-4">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Lifetime access</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Certificate of completion</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Access on mobile and TV</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Direct instructor support</li>
                        </ul>
                    </div>
                    
                    <div class="mt-4 p-3 text-center">
                        <p class="small text-muted mb-0">Need help? <a href="mailto:{{ $systemSettings['contact_email'] ?? 'support@lms.com' }}" class="text-decoration-none fw-bold">Contact Support</a></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@extends('welcome')

@section('content')
<!-- Add Premium Font -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --tm-primary: #4F46E5;
        --tm-slate-900: #0F172A;
        --tm-slate-600: #475569;
        --tm-slate-400: #94A3B8;
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── High-End Hero ── */
    .tm-hero {
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
    .tm-hero-label {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.3);
        padding: 8px 20px; border-radius: 50px; font-size: 0.75rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: 1.5px; color: #C7D2FE; margin-bottom: 2rem;
    }
    .tm-hero-title { 
        font-size: clamp(2.8rem, 6vw, 4rem); font-weight: 800; margin-bottom: 1.25rem; line-height: 1; 
        background: linear-gradient(to bottom, #FFFFFF 30%, #94A3B8 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .tm-hero-title span { 
        background: linear-gradient(135deg, #818CF8 0%, #6366F1 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .tm-hero-sub { font-size: 1.15rem; color: #94A3B8; max-width: 650px; margin: 0 auto; line-height: 1.6; }

    /* ── Team Grid ── */
    .tm-grid-section { background: #F8FAFF; padding: 6rem 0; }

    .tm-card {
        background: #fff; border-radius: 24px; border: 1px solid #E5E7EB;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%; display: flex; flex-direction: column; overflow: hidden;
    }
    .tm-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.12);
        border-color: var(--tm-primary);
    }

    /* Fixed Aspect Ratio for High-Resolution Portraits */
    .tm-img-wrap {
        position: relative; width: 100%; padding-top: 125%; /* 4:5 Portrait Ratio */
        overflow: hidden; background: #F1F5F9;
    }
    .tm-img-wrap img {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        object-fit: cover; transition: transform 0.6s ease;
    }
    .tm-card:hover .tm-img-wrap img { transform: scale(1.05); }

    .tm-card-body { padding: 2rem; text-align: center; flex: 1; display: flex; flex-direction: column; }
    .tm-name { font-size: 1.35rem; font-weight: 800; color: var(--tm-slate-900); margin-bottom: 0.4rem; }
    .tm-role { font-size: 0.85rem; font-weight: 700; color: var(--tm-primary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 1.25rem; }
    
    .tm-spec { 
        font-size: 0.88rem; color: var(--tm-slate-600); line-height: 1.5; margin-bottom: 1.5rem; 
        padding: 10px; background: #F9FAFB; border-radius: 12px; border: 1px solid #F3F4F6;
    }

    .tm-social { display: flex; justify-content: center; gap: 12px; margin-top: auto; margin-bottom: 1.5rem; }
    .tm-social-btn {
        width: 38px; height: 32px; background: #F1F5F9; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: var(--tm-slate-600); font-size: 1rem; transition: all 0.2s; text-decoration: none;
    }
    .tm-social-btn:hover { background: var(--tm-primary); color: #fff; transform: translateY(-2px); }

    .btn-tm-profile {
        width: 100%; padding: 12px; background: #fff; color: var(--tm-slate-900);
        border: 2px solid #E5E7EB; border-radius: 12px; font-weight: 700; font-size: 0.9rem;
        transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-tm-profile:hover { border-color: var(--tm-primary); color: var(--tm-primary); background: var(--tm-primary-soft); }

    @media (max-width: 768px) {
        .tm-hero { padding: 5rem 0; }
    }
</style>

{{-- ── Page Hero ── --}}
<section class="tm-hero">
    <div class="container text-center">
        <div class="tm-hero-label">
            <i class="bi bi-people-fill"></i> Distinguished Faculty
        </div>
        <h1 class="tm-hero-title">Meet Our<br><span>Experts</span></h1>
        <p class="tm-hero-sub">
            Learn from world-class instructors and industry leaders dedicated to 
            your academic success and professional growth.
        </p>
    </div>
</section>

{{-- ── Grid Section ── --}}
<section class="tm-grid-section">
    <div class="container">
        
        @if($teachers->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3"><i class="bi bi-people" style="font-size: 3rem; color: var(--tm-slate-400);"></i></div>
                <h4 class="fw-bold">Faculty Updating</h4>
                <p class="text-muted">Our expert team list is being refreshed. Please check back later!</p>
            </div>
        @else
            <div class="row g-4">
                @foreach ($teachers as $teacher)
                    @php 
                        $avatarUrl = 'https://ui-avatars.com/api/?name='.urlencode($teacher->name).'&background=4F46E5&color=fff&size=500&bold=true';
                    @endphp
                    <div class="col-md-6 col-lg-3">
                        <div class="tm-card">
                            
                            {{-- High Resolution Portrait --}}
                            <div class="tm-img-wrap">
                                <img src="{{ $teacher->profile_image ? asset('storage/teachers/' . $teacher->profile_image) : $avatarUrl }}" 
                                     alt="{{ $teacher->name }}">
                            </div>

                            <div class="tm-card-body">
                                <h5 class="tm-name">{{ $teacher->name }}</h5>
                                <p class="tm-role">{{ $teacher->designation ?? 'Expert Instructor' }}</p>
                                
                                @if($teacher->specialization)
                                    <div class="tm-spec">
                                        <i class="bi bi-award me-1 text-primary"></i> {{ Str::limit($teacher->specialization, 40) }}
                                    </div>
                                @endif

                                <div class="tm-social">
                                    @if($teacher->linkedin_url)
                                        <a href="{{ $teacher->linkedin_url }}" class="tm-social-btn" target="_blank"><i class="bi bi-linkedin"></i></a>
                                    @endif
                                    @if($teacher->twitter_url)
                                        <a href="{{ $teacher->twitter_url }}" class="tm-social-btn" target="_blank"><i class="bi bi-twitter-x"></i></a>
                                    @endif
                                    @if($teacher->scopus_link)
                                        <a href="{{ $teacher->scopus_link }}" class="tm-social-btn" target="_blank" title="Research Profile"><i class="bi bi-journal-text"></i></a>
                                    @endif
                                    <a href="mailto:{{ $teacher->email }}" class="tm-social-btn"><i class="bi bi-envelope"></i></a>
                                </div>

                                <button class="btn-tm-profile"
                                        data-bs-toggle="modal"
                                        data-bs-target="#profileModal"
                                        data-name="{{ $teacher->name }}"
                                        data-designation="{{ $teacher->designation ?? 'Instructor' }}"
                                        data-specialization="{{ $teacher->specialization ?? '' }}"
                                        data-bio="{{ $teacher->bio ?? '' }}"
                                        data-email="{{ $teacher->email ?? '' }}"
                                        data-linkedin="{{ $teacher->linkedin_url ?? '' }}"
                                        data-twitter="{{ $teacher->twitter_url ?? '' }}"
                                        data-scopus="{{ $teacher->scopus_link ?? '' }}"
                                        data-image="{{ $teacher->profile_image ? asset('storage/teachers/' . $teacher->profile_image) : $avatarUrl }}">
                                    View Full Profile
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>

{{-- ── Profile Modal ── --}}
<div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:550px;">
        <div class="modal-content" style="border:none; border-radius:28px; overflow:hidden; box-shadow: 0 40px 100px -12px rgba(0, 0, 0, 0.4);">
            
            <div class="modal-body p-0">
                <div style="background: linear-gradient(135deg, #1E1B4B 0%, #312E81 100%); padding: 3rem 2rem 2rem; text-align: center; position: relative;">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; top: 20px; right: 20px;"></button>
                    
                    <div style="width: 120px; height: 120px; border-radius: 50%; border: 4px solid rgba(255,255,255,0.2); overflow: hidden; margin: 0 auto 1.5rem; background: #fff;">
                        <img id="pm-photo" src="" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    
                    <h3 id="pm-name" style="color: #fff; font-weight: 800; margin-bottom: 0.5rem;"></h3>
                    <p id="pm-designation" style="color: #A5B4FC; font-weight: 700; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;"></p>
                </div>
                
                <div class="p-4 p-md-5">
                    <div class="mb-4">
                        <label style="font-size: 0.75rem; font-weight: 800; color: var(--tm-primary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; display: block;">About Instructor</label>
                        <p id="pm-bio" style="font-size: 0.95rem; color: var(--tm-slate-600); line-height: 1.7;"></p>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label style="font-size: 0.65rem; font-weight: 800; color: var(--tm-slate-400); text-transform: uppercase; display: block;">Email</label>
                            <span id="pm-email" style="font-size: 0.85rem; font-weight: 600; color: var(--tm-slate-900);"></span>
                        </div>
                        <div class="col-6 text-end">
                            <div id="pm-social-modal" class="d-flex justify-content-end gap-2"></div>
                        </div>
                    </div>
                    
                    <a href="{{ route('register') }}" class="btn btn-primary w-100 py-3 rounded-4 fw-bold shadow-lg" style="background: var(--tm-primary); border: none;">
                        Enroll in Instructor's Course
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('profileModal');
    if (!modal) return;

    modal.addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;

        document.getElementById('pm-photo').src = btn.dataset.image;
        document.getElementById('pm-name').textContent = btn.dataset.name;
        document.getElementById('pm-designation').textContent = btn.dataset.designation;
        document.getElementById('pm-bio').textContent = btn.dataset.bio || 'Professional biography coming soon.';
        document.getElementById('pm-email').textContent = btn.dataset.email;

        const socialModal = document.getElementById('pm-social-modal');
        socialModal.innerHTML = '';
        if (btn.dataset.linkedin) {
            socialModal.innerHTML += `<a href="${btn.dataset.linkedin}" target="_blank" class="tm-social-btn"><i class="bi bi-linkedin"></i></a>`;
        }
        if (btn.dataset.twitter) {
            socialModal.innerHTML += `<a href="${btn.dataset.twitter}" target="_blank" class="tm-social-btn"><i class="bi bi-twitter-x"></i></a>`;
        }
    });
});
</script>

@endsection

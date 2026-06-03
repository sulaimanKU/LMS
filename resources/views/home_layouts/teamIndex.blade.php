@extends('welcome')

@section('content')

{{-- ── Hero ── --}}
<section style="background:var(--gradient-primary);padding:4rem 0 3rem;position:relative;overflow:hidden;">
    <div style="position:absolute;top:-30%;right:-4%;width:420px;height:420px;background:rgba(255,255,255,.06);border-radius:50%;pointer-events:none;"></div>
    <div style="position:absolute;bottom:-35%;left:-4%;width:300px;height:300px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none;"></div>
    <div class="container text-center" style="position:relative;z-index:1;">
        <span style="display:inline-block;background:rgba(255,255,255,.15);color:rgba(255,255,255,.9);font-size:.78rem;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;padding:.35rem 1rem;border-radius:50px;border:1px solid rgba(255,255,255,.25);margin-bottom:1rem;">
            <i class="bi bi-people-fill me-1"></i>Expert Faculty
        </span>
        <h1 style="font-size:clamp(1.8rem,4vw,2.6rem);font-weight:800;color:#fff;margin-bottom:.75rem;">Meet Our Professional Team</h1>
        <p style="color:rgba(255,255,255,.8);font-size:1rem;max-width:520px;margin:0 auto 2rem;line-height:1.7;">
            Expert instructors dedicated to advancing your research career through practice-focused training.
        </p>
        <div style="display:inline-flex;align-items:center;gap:0;background:rgba(255,255,255,.12);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.2);border-radius:50px;padding:.6rem 1.75rem;">
            <div style="text-align:center;padding:0 1.25rem;">
                <span style="display:block;font-size:1.4rem;font-weight:800;color:#fff;line-height:1.15;">{{ $teachers->count() }}</span>
                <span style="font-size:.72rem;color:rgba(255,255,255,.75);text-transform:uppercase;letter-spacing:.8px;">Instructors</span>
            </div>
            <div style="width:1px;height:40px;background:rgba(255,255,255,.25);flex-shrink:0;"></div>
            <div style="text-align:center;padding:0 1.25rem;">
                <span style="display:block;font-size:1.4rem;font-weight:800;color:#fff;line-height:1.15;">100%</span>
                <span style="font-size:.72rem;color:rgba(255,255,255,.75);text-transform:uppercase;letter-spacing:.8px;">Online</span>
            </div>
            <div style="width:1px;height:40px;background:rgba(255,255,255,.25);flex-shrink:0;"></div>
            <div style="text-align:center;padding:0 1.25rem;">
                <span style="display:block;font-size:1.4rem;font-weight:800;color:#fff;line-height:1.15;">Expert</span>
                <span style="font-size:.72rem;color:rgba(255,255,255,.75);text-transform:uppercase;letter-spacing:.8px;">Certified</span>
            </div>
        </div>
    </div>
</section>

{{-- ── Team Grid ── --}}
<section class="py-5" style="background:#F8FAFF;">
    <div class="container">
        @if($teachers->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-people" style="font-size:3rem;color:#CBD5E1;"></i>
                <p class="mt-3 text-muted">No instructors available yet. Check back soon!</p>
            </div>
        @else
        <div class="row g-4">
            @foreach($teachers as $teacher)
            <div class="col-lg-3 col-md-6">
                <div class="tc-card">

                    {{-- Photo --}}
                    <div class="tc-photo-wrap">
                       <img src="{{ $teacher->profile_image
            ? asset('storage/teachers/' . $teacher->profile_image)
            : 'https://ui-avatars.com/api/?name='.urlencode($teacher->name).'&background=4F46E5&color=fff&size=400&bold=true' }}"
     alt="{{ $teacher->name }}"
     class="tc-photo">

                        {{-- Social overlay --}}
                        <div class="tc-social-overlay">
                            @if($teacher->linkedin_url)
                                <a href="{{ $teacher->linkedin_url }}" target="_blank" class="tc-social-btn" title="LinkedIn">
                                    <i class="bi bi-linkedin"></i>
                                </a>
                            @endif
                            @if($teacher->scopus_link)
                                <a href="{{ $teacher->scopus_link }}" target="_blank" class="tc-social-btn" title="Scopus / Profile">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="tc-body">
                        <h5 class="tc-name">{{ $teacher->name }}</h5>
                        <p class="tc-designation">{{ $teacher->designation ?? 'Instructor' }}</p>

                        @if($teacher->specialization)
                            <p class="tc-spec">
                                <i class="bi bi-mortarboard-fill me-1"></i>{{ $teacher->specialization }}
                            </p>
                        @endif

                        @if($teacher->courses->isNotEmpty())
                            <div class="tc-courses">
                                @foreach($teacher->courses->take(2) as $course)
                                    <span class="tc-course-pill">{{ Str::limit($course->title, 22) }}</span>
                                @endforeach
                                @if($teacher->courses->count() > 2)
                                    <span class="tc-course-pill tc-more">+{{ $teacher->courses->count() - 2 }} more</span>
                                @endif
                            </div>
                        @endif

                        <button class="tc-view-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#profileModal"
                                data-name="{{ $teacher->name }}"
                                data-designation="{{ $teacher->designation ?? 'Instructor' }}"
                                data-specialization="{{ $teacher->specialization ?? '' }}"
                                data-bio="{{ $teacher->bio ?? '' }}"
                                data-email="{{ $teacher->email ?? '' }}"
                                data-linkedin="{{ $teacher->linkedin_url ?? '' }}"
                                data-scopus="{{ $teacher->scopus_link ?? '' }}"
                                data-courses="{{ $teacher->courses->pluck('title')->join(' | ') }}"
                                data-image="{{ $teacher->profile_image ? asset('uploads/teachers/' . $teacher->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode($teacher->name).'&background=4F46E5&color=fff&size=400&bold=true' }}">
                            <i class="bi bi-person-lines-fill me-1"></i>View Profile
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
    <div class="modal-dialog modal-dialog-centered" style="max-width:600px;">
        <div class="modal-content" style="border:none;border-radius:20px;overflow:hidden;box-shadow:0 24px 60px rgba(0,0,0,.15);">

            {{-- Header with gradient --}}
            <div class="pm-header">
                <button type="button" class="pm-close" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                </button>
                <div class="pm-photo-wrap">
                    <img id="pm-photo" src="" alt="" class="pm-photo">
                </div>
                <h4 id="pm-name" class="pm-name"></h4>
                <p id="pm-designation" class="pm-desig"></p>
                <div id="pm-social" class="pm-social-row"></div>
            </div>

            {{-- Body --}}
            <div class="pm-body">

                <div id="pm-spec-wrap" class="pm-info-row" style="display:none;">
                    <div class="pm-info-icon"><i class="bi bi-mortarboard-fill"></i></div>
                    <div>
                        <div class="pm-info-label">Specialization</div>
                        <div id="pm-spec" class="pm-info-val"></div>
                    </div>
                </div>

                <div id="pm-email-wrap" class="pm-info-row" style="display:none;">
                    <div class="pm-info-icon"><i class="bi bi-envelope-fill"></i></div>
                    <div>
                        <div class="pm-info-label">Email</div>
                        <div id="pm-email" class="pm-info-val"></div>
                    </div>
                </div>

                <div id="pm-courses-wrap" class="pm-info-row" style="display:none;">
                    <div class="pm-info-icon"><i class="bi bi-book-fill"></i></div>
                    <div>
                        <div class="pm-info-label">Teaching Modules</div>
                        <div id="pm-courses" class="pm-info-val"></div>
                    </div>
                </div>

                <div id="pm-bio-wrap" style="display:none;">
                    <div class="pm-info-label" style="margin-bottom:.4rem;">About</div>
                    <p id="pm-bio" class="pm-bio-text"></p>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('register') }}" class="pm-enroll-btn">
                        <i class="bi bi-person-plus me-2"></i>Enroll in a Course
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
/* ── Teacher Card ── */
.tc-card {
    background: #fff;
    border-radius: 18px;
    border: 1.5px solid #F1F5F9;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    overflow: hidden;
    transition: transform .25s, box-shadow .25s;
    display: flex; flex-direction: column;
}
.tc-card:hover { transform: translateY(-5px); box-shadow: 0 12px 32px rgba(79,70,229,.14); }

/* Photo */
.tc-photo-wrap {
    position: relative;
    width: 100%; height: 220px;
    overflow: hidden;
    background: linear-gradient(135deg,#EEF2FF,#E0E7FF);
}
.tc-photo { width:100%; height:100%; object-fit:cover; display:block; transition:transform .4s; }
.tc-card:hover .tc-photo { transform: scale(1.05); }

.tc-social-overlay {
    position: absolute; bottom:0; left:0; right:0;
    background: linear-gradient(to top, rgba(79,70,229,.85), transparent);
    padding: 1.5rem .75rem .65rem;
    display: flex; gap: .5rem; justify-content: center;
    opacity: 0; transition: opacity .25s;
}
.tc-card:hover .tc-social-overlay { opacity: 1; }
.tc-social-btn {
    width: 34px; height: 34px;
    background: rgba(255,255,255,.2);
    border: 1px solid rgba(255,255,255,.4);
    border-radius: 50%;
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem;
    text-decoration: none;
    transition: background .2s;
}
.tc-social-btn:hover { background: rgba(255,255,255,.35); color: #fff; }

/* Body */
.tc-body { padding: 1.25rem 1.2rem 1.3rem; display: flex; flex-direction: column; gap: .55rem; flex: 1; }
.tc-name { font-size: 1rem; font-weight: 800; color: #1E293B; margin: 0; }
.tc-designation { font-size: .78rem; font-weight: 600; color: #4F46E5; margin: 0; }
.tc-spec { font-size: .75rem; color: #64748B; margin: 0; }
.tc-spec i { color: #94A3B8; }

.tc-courses { display: flex; flex-wrap: wrap; gap: 4px; }
.tc-course-pill {
    font-size: .68rem; font-weight: 600;
    background: #EEF2FF; color: #4F46E5;
    padding: .18rem .55rem; border-radius: 50px;
}
.tc-more { background: #F1F5F9; color: #64748B; }

.tc-view-btn {
    margin-top: auto;
    width: 100%;
    padding: .55rem;
    background: linear-gradient(135deg,#4F46E5,#7C3AED);
    color: #fff;
    border: none; border-radius: 10px;
    font-size: .82rem; font-weight: 600;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    gap: .35rem;
    transition: opacity .2s, transform .2s;
    box-shadow: 0 4px 12px rgba(79,70,229,.25);
}
.tc-view-btn:hover { opacity: .9; transform: translateY(-1px); }

/* ── Profile Modal ── */
.pm-header {
    background: linear-gradient(135deg,#4F46E5,#7C3AED);
    padding: 2rem 1.5rem 1.5rem;
    text-align: center;
    position: relative;
}
.pm-close {
    position: absolute; top: .9rem; right: .9rem;
    width: 32px; height: 32px;
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.28);
    border-radius: 8px;
    color: #fff; font-size: .85rem;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: background .15s;
}
.pm-close:hover { background: rgba(255,255,255,.3); }
.pm-photo-wrap {
    width: 90px; height: 90px;
    border-radius: 50%;
    border: 3px solid rgba(255,255,255,.4);
    overflow: hidden;
    margin: 0 auto .9rem;
    background: rgba(255,255,255,.1);
}
.pm-photo { width: 100%; height: 100%; object-fit: cover; }
.pm-name  { font-size: 1.2rem; font-weight: 800; color: #fff; margin: 0 0 .2rem; }
.pm-desig { font-size: .82rem; color: rgba(255,255,255,.8); margin: 0; }
.pm-social-row { display: flex; justify-content: center; gap: .5rem; margin-top: .75rem; }
.pm-social-link {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .28rem .8rem;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    border-radius: 50px;
    color: #fff; font-size: .75rem; font-weight: 600;
    text-decoration: none; transition: background .15s;
}
.pm-social-link:hover { background: rgba(255,255,255,.28); color: #fff; }

.pm-body { padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem; }
.pm-info-row { display: flex; align-items: flex-start; gap: .85rem; }
.pm-info-icon {
    width: 36px; height: 36px;
    border-radius: 9px;
    background: #EEF2FF;
    color: #4F46E5;
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; flex-shrink: 0;
}
.pm-info-label { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #94A3B8; margin-bottom: .15rem; }
.pm-info-val   { font-size: .875rem; color: #1E293B; font-weight: 500; }
.pm-bio-text   { font-size: .875rem; color: #475569; line-height: 1.7; margin: 0; background: #F8FAFF; border-radius: 10px; padding: .85rem 1rem; }
.pm-enroll-btn {
    display: inline-flex; align-items: center;
    background: linear-gradient(135deg,#4F46E5,#7C3AED);
    color: #fff; text-decoration: none;
    padding: .6rem 1.5rem; border-radius: 10px;
    font-size: .855rem; font-weight: 600;
    box-shadow: 0 4px 12px rgba(79,70,229,.28);
    transition: opacity .2s, transform .2s;
}
.pm-enroll-btn:hover { opacity: .9; transform: translateY(-1px); color: #fff; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('profileModal');
    if (!modal) return;

    modal.addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;

        document.getElementById('pm-photo').src          = btn.dataset.image || '';
        document.getElementById('pm-name').textContent   = btn.dataset.name  || '';
        document.getElementById('pm-designation').textContent = btn.dataset.designation || '';

        // Specialization
        const specWrap = document.getElementById('pm-spec-wrap');
        const spec     = btn.dataset.specialization;
        if (spec) {
            document.getElementById('pm-spec').textContent = spec;
            specWrap.style.display = 'flex';
        } else { specWrap.style.display = 'none'; }

        // Email
        const emailWrap = document.getElementById('pm-email-wrap');
        const email     = btn.dataset.email;
        if (email) {
            document.getElementById('pm-email').textContent = email;
            emailWrap.style.display = 'flex';
        } else { emailWrap.style.display = 'none'; }

        // Courses
        const coursesWrap = document.getElementById('pm-courses-wrap');
        const courses     = btn.dataset.courses;
        if (courses) {
            document.getElementById('pm-courses').textContent = courses.split(' | ').join(', ');
            coursesWrap.style.display = 'flex';
        } else { coursesWrap.style.display = 'none'; }

        // Bio
        const bioWrap = document.getElementById('pm-bio-wrap');
        const bio     = btn.dataset.bio;
        if (bio) {
            document.getElementById('pm-bio').textContent = bio;
            bioWrap.style.display = 'block';
        } else { bioWrap.style.display = 'none'; }

        // Social links
        const socialRow = document.getElementById('pm-social');
        socialRow.innerHTML = '';
        if (btn.dataset.linkedin) {
            socialRow.innerHTML += `<a href="${btn.dataset.linkedin}" target="_blank" class="pm-social-link"><i class="bi bi-linkedin me-1"></i>LinkedIn</a>`;
        }
        if (btn.dataset.scopus) {
            socialRow.innerHTML += `<a href="${btn.dataset.scopus}" target="_blank" class="pm-social-link"><i class="bi bi-box-arrow-up-right me-1"></i>Scopus</a>`;
        }
    });
});
</script>

@endsection

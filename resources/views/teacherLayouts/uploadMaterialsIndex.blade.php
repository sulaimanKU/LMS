@extends('applayouts.app')

@section('contents')
<style>
/* ── Page Wrapper ── */
.tml-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; }

/* ── Header ── */
.tml-header {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem; margin-bottom: 1.75rem;
}
.tml-title    { font-size: 1.3rem; font-weight: 800; color: #1E293B; margin: 0; }
.tml-subtitle { font-size: .82rem; color: #64748B; margin: .15rem 0 0; }

.tml-stat-badge {
    background: #fff; border: 1.5px solid #E2E8F0; padding: .6rem 1.1rem;
    border-radius: 12px; display: flex; align-items: center; gap: .85rem;
    box-shadow: 0 2px 4px rgba(0,0,0,.02);
}
.tml-stat-num { font-size: 1.1rem; font-weight: 800; color: #1E293B; line-height: 1; }
.tml-stat-lbl { font-size: .62rem; font-weight: 700; text-transform: uppercase; color: #94A3B8; letter-spacing: .5px; }

/* ── Upload Section ── */
.tml-upload-card {
    background: #fff; border-radius: 16px; border: 1.5px solid #F1F5F9;
    padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 1px 6px rgba(0,0,0,.05);
}
.tml-section-tag {
    display: inline-block; padding: .2rem .75rem; border-radius: 50px;
    background: #EEF2FF; color: #4F46E5; font-size: .65rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: .6px; margin-bottom: 1.25rem;
}

.tml-form-label { display: block; font-size: .75rem; font-weight: 700; text-transform: uppercase; color: #64748B; margin-bottom: .4rem; }
.tml-input-group { position: relative; }
.tml-input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94A3B8; font-size: .85rem; }
.tml-input {
    width: 100%; padding: .6rem .85rem .6rem 2.4rem; border: 1.5px solid #E2E8F0;
    border-radius: 10px; font-size: .88rem; background: #F8FAFF; transition: all .2s;
}
.tml-input:focus { border-color: #4F46E5; background: #fff; box-shadow: 0 0 0 4px rgba(79,70,229,.1); outline: none; }
select.tml-input { padding-left: 2.4rem; cursor: pointer; }

.tml-btn-submit {
    width: 100%; padding: .65rem; border-radius: 10px; border: none;
    background: linear-gradient(135deg, #4F46E5, #7C3AED); color: #fff;
    font-size: .88rem; font-weight: 700; box-shadow: 0 4px 12px rgba(79,70,229,.25);
    transition: all .2s;
}
.tml-btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79,70,229,.35); }

/* ── Library Card ── */
.tml-library-card {
    background: #fff; border-radius: 16px; border: 1.5px solid #F1F5F9;
    box-shadow: 0 1px 6px rgba(0,0,0,.05); overflow: hidden;
}
.tml-card-head { padding: 1.1rem 1.5rem; border-bottom: 1.5px solid #F1F5F9; display: flex; align-items: center; gap: .6rem; }
.tml-card-title { font-size: .9rem; font-weight: 700; color: #1E293B; margin: 0; }

.tml-table thead th {
    background: #F8FAFF; font-size: .68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .7px; color: #94A3B8; padding: .85rem 1.25rem; border-bottom: 1.5px solid #F1F5F9;
}
.tml-table tbody td { padding: 1rem 1.25rem; border-bottom: 1px solid #F8FAFF; vertical-align: middle; }
.tml-table tbody tr:last-child td { border-bottom: none; }
.tml-table tbody tr:hover { background: #FAFBFF; }

/* ── File Icons & Details ── */
.tml-file-icon {
    width: 42px; height: 42px; border-radius: 12px; display: flex;
    align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;
}
.tml-file-name { font-size: .88rem; font-weight: 700; color: #1E293B; margin: 0; line-height: 1.2; }
.tml-file-sub  { font-size: .72rem; color: #64748B; display: flex; align-items: center; gap: .4rem; margin-top: .15rem; }
.tml-module-pill {
    font-size: .62rem; font-weight: 800; padding: .1rem .45rem; border-radius: 4px;
    background: #EEF2FF; color: #4F46E5; text-transform: uppercase;
}

.tml-ext-badge {
    padding: .2rem .6rem; border-radius: 6px; font-size: .65rem; font-weight: 800;
    text-transform: uppercase; border: 1px solid #E2E8F0; background: #fff; color: #475569;
}

/* ── Actions ── */
.tml-action-group { display: flex; align-items: center; justify-content: flex-end; gap: .5rem; }
.tml-btn-icon {
    width: 32px; height: 32px; border-radius: 8px; border: 1.5px solid #E2E8F0;
    background: #fff; color: #64748B; display: flex; align-items: center;
    justify-content: center; font-size: .8rem; transition: all .15s; text-decoration: none;
}
.tml-btn-icon:hover { border-color: #4F46E5; color: #4F46E5; background: #F5F7FF; }
.tml-btn-delete:hover { border-color: #EF4444; color: #EF4444; background: #FEF2F2; }

@media (max-width: 991.98px) {
    .tml-upload-card .row > div { margin-bottom: .5rem; }
}
</style>

<div class="tml-page">

    {{-- ── Alerts ── --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-check me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="tml-header">
        <div>
            <h5 class="tml-title">Resource Library</h5>
            <p class="tml-subtitle">Manage and upload study materials for your modules.</p>
        </div>
        <div class="tml-stat-badge">
            <div>
                <p class="tml-stat-num">{{ $resources->count() }}</p>
                <p class="tml-stat-lbl">Total Files</p>
            </div>
            <i class="fa-solid fa-cloud-arrow-up text-primary opacity-25 fs-3"></i>
        </div>
    </div>

    {{-- ── Upload Section ── --}}
    <div class="tml-upload-card">
        <span class="tml-section-tag"><i class="fa-solid fa-plus me-1"></i>Quick Upload</span>
        <form action="{{ route('teacher.resources.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-lg-3">
                    <label class="tml-form-label">Module <span class="text-danger">*</span></label>
                    <div class="tml-input-group">
                        <i class="fa-solid fa-cube tml-input-icon"></i>
                        <select class="tml-input" id="module_selector" required>
                            <option selected disabled>Choose module...</option>
                            @foreach ($myModules as $mod)
                                <option value="{{ $mod->id }}">{{ $mod->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <label class="tml-form-label">Lesson <span class="text-danger">*</span></label>
                    <div class="tml-input-group">
                        <i class="fa-solid fa-layer-group tml-input-icon"></i>
                        <select class="tml-input" name="lesson_id" id="lesson_selector" required disabled>
                            <option selected disabled>First select module...</option>
                            @foreach ($lessons as $lesson)
                                <option value="{{ $lesson->id }}" data-module="{{ $lesson->module_id }}" style="display:none;">
                                    {{ $lesson->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <small id="lesson_hint" class="text-danger d-none" style="font-size: 0.65rem; font-weight: 600;">No lessons found. Create a lesson first!</small>
                </div>
                <div class="col-lg-2">
                    <label class="tml-form-label">Title <span class="text-danger">*</span></label>
                    <div class="tml-input-group">
                        <i class="fa-solid fa-pen-to-square tml-input-icon"></i>
                        <input type="text" name="title" class="tml-input" placeholder="e.g. Notes" required>
                    </div>
                </div>
                <div class="col-lg-2">
                    <label class="tml-form-label">File <span class="text-danger">*</span></label>
                    <div class="tml-input-group">
                        <i class="fa-solid fa-file-upload tml-input-icon"></i>
                        <input type="file" name="file" class="tml-input" style="padding-top: .45rem;" accept=".pdf,.doc,.docx,.zip,.ppt,.pptx,.jpg,.jpeg,.png" required>
                    </div>
                </div>
                <div class="col-lg-2 d-flex align-items-end">
                    <button type="submit" class="tml-btn-submit">
                        <i class="fa-solid fa-cloud-arrow-up me-2"></i>Post
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modSelect = document.getElementById('module_selector');
        const lesSelect = document.getElementById('lesson_selector');
        const lesHint   = document.getElementById('lesson_hint');
        const lesOpts   = lesSelect.querySelectorAll('option[data-module]');

        modSelect.addEventListener('change', function() {
            const modId = this.value;
            lesSelect.disabled = false;
            lesSelect.value = "";
            lesHint.classList.add('d-none');
            
            let found = false;
            lesOpts.forEach(opt => {
                if(opt.getAttribute('data-module') === modId) {
                    opt.style.display = "block";
                    found = true;
                } else {
                    opt.style.display = "none";
                }
            });

            if(!found) {
                lesSelect.disabled = true;
                lesHint.classList.remove('d-none');
                lesSelect.innerHTML = '<option selected disabled>No lessons available</option>';
            } else {
                // Reset to default option if lessons exist
                lesSelect.innerHTML = '<option selected disabled>Choose lesson...</option>';
                lesOpts.forEach(opt => {
                    if(opt.getAttribute('data-module') === modId) {
                        lesSelect.appendChild(opt);
                        opt.style.display = "block";
                    }
                });
            }
        });
    });
    </script>

    {{-- ── Library List ── --}}
    <div class="tml-library-card">
        <div class="tml-card-head">
            <i class="fa-solid fa-box-archive text-primary"></i>
            <h6 class="tml-card-title">Stored Materials</h6>
        </div>
        <div class="table-responsive">
            <table class="table tml-table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 40%;">File & Description</th>
                        <th>Type</th>
                        <th>Uploaded On</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($resources as $resource)
                        @php
                            $extension = strtolower(pathinfo($resource->file_path, PATHINFO_EXTENSION));
                            $icon = match ($extension) {
                                'pdf' => ['icon' => 'fa-file-pdf', 'color' => '#EF4444', 'bg' => '#FEF2F2'],
                                'doc', 'docx' => ['icon' => 'fa-file-word', 'color' => '#3B82F6', 'bg' => '#EFF6FF'],
                                'xls', 'xlsx' => ['icon' => 'fa-file-excel', 'color' => '#10B981', 'bg' => '#ECFDF5'],
                                'zip', 'rar' => ['icon' => 'fa-file-zipper', 'color' => '#F59E0B', 'bg' => '#FFFBEB'],
                                'jpg', 'jpeg', 'png' => ['icon' => 'fa-file-image', 'color' => '#8B5CF6', 'bg' => '#F5F3FF'],
                                default => ['icon' => 'fa-file-lines', 'color' => '#64748B', 'bg' => '#F8FAFF'],
                            };
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="tml-file-icon" style="background: {{ $icon['bg'] }}; color: {{ $icon['color'] }};">
                                        <i class="fa-solid {{ $icon['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <p class="tml-file-name">{{ $resource->title }}</p>
                                        <div class="tml-file-sub">
                                            <span class="tml-module-pill">{{ $resource->lesson->module->title ?? 'General' }}</span>
                                            <span class="opacity-50">·</span>
                                            <span>{{ $resource->lesson->title }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="tml-ext-badge">{{ $extension }}</span>
                            </td>
                            <td>
                                <div class="text-dark fw-semibold" style="font-size: .8rem;">{{ $resource->created_at->format('d M, Y') }}</div>
                                <small class="text-muted" style="font-size: .7rem;">{{ $resource->created_at->format('h:i A') }}</small>
                            </td>
                            <td class="text-end">
                                <div class="tml-action-group">
                                    <a href="{{ asset('storage/' . $resource->file_path) }}" target="_blank" class="tml-btn-icon" title="Preview File">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <form action="{{ route('teacher.resource.delete', $resource->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="tml-btn-icon tml-btn-delete" onclick="return confirm('Permanently delete this file?')" title="Delete">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="opacity-25 mb-3"><i class="fa-solid fa-folder-open fs-1"></i></div>
                                <p class="text-muted small mb-0">No resources have been uploaded to this library yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

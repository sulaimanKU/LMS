@extends('applayouts.app')

@section('contents')
<style>
    #settings-container {
        padding: 15px;
        margin-top: 20px;
        max-width: 980px;
        margin-left: auto;
        margin-right: auto;
    }

    .settings-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e3e6f0;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.05);
        margin-bottom: 20px;
    }

    .card-header-pro {
        padding: 15px 20px;
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        border-radius: 14px 14px 0 0;
    }

    .form-label-pro {
        font-size: 11px;
        font-weight: 800;
        color: #4e73df;
        text-transform: uppercase;
        margin-bottom: 6px;
        letter-spacing: 0.5px;
    }

    .input-pro {
        border: 1px solid #d1d3e2;
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 14px;
        background-color: #ffffff;
    }

    .input-pro:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1);
    }

    .settings-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding: 10px 0 50px 0;
    }

    .profile-img-preview {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #f8f9fc;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .smtp-preset-chip {
        cursor: pointer;
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        background: #eef2ff;
        color: #4f46e5;
        border: 1px solid #c7d2fe;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .smtp-preset-chip:hover {
        background: #4f46e5;
        color: #fff;
    }

    @media (max-width: 576px) {
        .settings-footer { flex-direction: column; }
        .settings-footer .btn { width: 100%; }
    }
</style>

<div id="settings-container">

    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1">Settings</h4>
        <p class="text-muted small">Manage your profile, system configurations, and mail server credentials.</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 small mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-3 small mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-3 small mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tabs Nav -->
    <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab">
                <i class="bi bi-person-circle me-1"></i> Profile Settings
            </button>
        </li>
        @if(auth()->user()->hasRole('admin'))
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="pills-system-tab" data-bs-toggle="pill" data-bs-target="#pills-system" type="button" role="tab">
                <i class="bi bi-gear-fill me-1"></i> System Configuration
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="pills-email-tab" data-bs-toggle="pill" data-bs-target="#pills-email" type="button" role="tab">
                <i class="bi bi-envelope-at-fill me-1"></i> Email / SMTP Configurations
            </button>
        </li>
        @endif
    </ul>

    <div class="tab-content" id="pills-tabContent">
        <!-- ── Profile Tab ── -->
        <div class="tab-pane fade show active" id="pills-profile" role="tabpanel">
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Profile Information --}}
                <div class="settings-card">
                    <div class="card-header-pro">
                        <h6 class="m-0 fw-bold text-primary"><i class="bi bi-person-circle me-2"></i>My Profile Details</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row align-items-center mb-4">
                            <div class="col-auto">
                                <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=4e73df&color=fff' }}" 
                                     class="profile-img-preview" alt="Avatar">
                            </div>
                            <div class="col">
                                <label class="form-label-pro">Profile Picture</label>
                                <input type="file" name="profile_image" class="form-control input-pro" accept="image/*">
                                <small class="text-muted">Recommended: Square image, max 2MB.</small>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-pro">Full Name</label>
                                <input type="text" name="name" class="form-control input-pro" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-pro">Email Address</label>
                                <input type="email" name="email" class="form-control input-pro" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-pro">Phone Number</label>
                                <input type="text" name="phone" class="form-control input-pro" value="{{ old('phone', $user->phone) }}" placeholder="+92 ...">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-pro">Mailing Address</label>
                                <input type="text" name="address" class="form-control input-pro" value="{{ old('address', $user->address) }}" placeholder="Street, City, Country">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Security --}}
                <div class="settings-card">
                    <div class="card-header-pro">
                        <h6 class="m-0 fw-bold text-primary"><i class="bi bi-shield-lock me-2"></i>Change Password</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-pro">New Password</label>
                                <input type="password" name="password" class="form-control input-pro" placeholder="Leave blank to keep current">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-pro">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control input-pro" placeholder="Repeat new password">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-footer">
                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">Save Profile Changes</button>
                </div>
            </form>
        </div>

        <!-- ── System Tab ── -->
        <div class="tab-pane fade" id="pills-system" role="tabpanel">
            <form action="{{ route('settings.update.system') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Branding --}}
                <div class="settings-card">
                    <div class="card-header-pro">
                        <h6 class="m-0 fw-bold text-primary"><i class="bi bi-display me-2"></i>Branding & Identity</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-pro">Site Title</label>
                                <input type="text" name="site_title" class="form-control input-pro" value="{{ $settings['site_title'] ?? '' }}" placeholder="e.g. My LMS Platform">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-pro">Hero Section Title</label>
                                <input type="text" name="site_hero_title" class="form-control input-pro" value="{{ $settings['site_hero_title'] ?? '' }}" placeholder="e.g. Learn Smarter. Grow Faster.">
                                <small class="text-muted" style="font-size: 10px;">Use &lt;br&gt; for line breaks.</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label-pro">Hero Section Subtitle</label>
                                <textarea name="site_hero_subtitle" class="form-control input-pro" rows="2" placeholder="Brief tagline under the main hero title...">{{ $settings['site_hero_subtitle'] ?? '' }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-pro">Nav Logo (Dark/Colored)</label>
                                <input type="file" name="site_logo_nav" class="form-control input-pro" accept="image/*">
                                @if(isset($settings['site_logo_nav']))
                                    <div class="bg-light p-2 rounded mt-2 text-center">
                                        <img src="{{ asset('storage/'.$settings['site_logo_nav']) }}" style="max-height: 40px;">
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-pro">Footer Logo (Light/White)</label>
                                <input type="file" name="site_logo_footer" class="form-control input-pro" accept="image/*">
                                @if(isset($settings['site_logo_footer']))
                                    <div class="bg-dark p-2 rounded mt-2 text-center">
                                        <img src="{{ asset('storage/'.$settings['site_logo_footer']) }}" style="max-height: 40px;">
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-pro">Favicon</label>
                                <input type="file" name="site_favicon" class="form-control input-pro" accept="image/*">
                                @if(isset($settings['site_favicon']))
                                    <div class="bg-light p-2 rounded mt-2 text-center">
                                        <img src="{{ asset('storage/'.$settings['site_favicon']) }}" style="max-height: 32px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact Info --}}
                <div class="settings-card">
                    <div class="card-header-pro">
                        <h6 class="m-0 fw-bold text-primary"><i class="bi bi-telephone me-2"></i>Contact & Institutional Info</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-pro">Official Email</label>
                                <input type="email" name="contact_email" class="form-control input-pro" value="{{ $settings['contact_email'] ?? '' }}" placeholder="info@lms.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-pro">Official Phone</label>
                                <input type="text" name="contact_phone" class="form-control input-pro" value="{{ $settings['contact_phone'] ?? '' }}" placeholder="+92 ...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-pro">WhatsApp Number (For Proofs)</label>
                                <input type="text" name="contact_whatsapp" class="form-control input-pro" value="{{ $settings['contact_whatsapp'] ?? '' }}" placeholder="e.g. 923469061650">
                                <small class="text-muted" style="font-size: 10px;">Include country code, no + or spaces (e.g. 923001234567).</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label-pro">Full Address</label>
                                <input type="text" name="contact_address" class="form-control input-pro" value="{{ $settings['contact_address'] ?? '' }}" placeholder="Institutional Address">
                            </div>
                            <div class="col-12">
                                <label class="form-label-pro">About Institution (Footer Text)</label>
                                <textarea name="site_about" class="form-control input-pro" rows="3" placeholder="Brief summary of your LMS/Institution for the footer...">{{ $settings['site_about'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Social Media --}}
                <div class="settings-card">
                    <div class="card-header-pro">
                        <h6 class="m-0 fw-bold text-primary"><i class="bi bi-share me-2"></i>Social Media Links</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-pro">Facebook URL</label>
                                <input type="url" name="social_facebook" class="form-control input-pro" value="{{ $settings['social_facebook'] ?? '' }}" placeholder="https://facebook.com/...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-pro">Twitter / X URL</label>
                                <input type="url" name="social_twitter" class="form-control input-pro" value="{{ $settings['social_twitter'] ?? '' }}" placeholder="https://twitter.com/...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-pro">Instagram URL</label>
                                <input type="url" name="social_instagram" class="form-control input-pro" value="{{ $settings['social_instagram'] ?? '' }}" placeholder="https://instagram.com/...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-pro">LinkedIn URL</label>
                                <input type="url" name="social_linkedin" class="form-control input-pro" value="{{ $settings['social_linkedin'] ?? '' }}" placeholder="https://linkedin.com/in/...">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SEO --}}
                <div class="settings-card">
                    <div class="card-header-pro">
                        <h6 class="m-0 fw-bold text-primary"><i class="bi bi-search me-2"></i>SEO & Meta Data</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label-pro">Meta Keywords</label>
                                <input type="text" name="seo_keywords" class="form-control input-pro" value="{{ $settings['seo_keywords'] ?? '' }}" placeholder="e.g. LMS, Education, Online Learning">
                            </div>
                            <div class="col-12">
                                <label class="form-label-pro">Meta Description</label>
                                <textarea name="seo_description" class="form-control input-pro" rows="2" placeholder="Brief description for search engines...">{{ $settings['seo_description'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment Info --}}
                <div class="settings-card">
                    <div class="card-header-pro">
                        <h6 class="m-0 fw-bold text-primary"><i class="bi bi-wallet2 me-2"></i>Payment Instructions (Registration)</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-pro">EasyPaisa Number</label>
                                <input type="text" name="payment_easypaisa" class="form-control input-pro" value="{{ $settings['payment_easypaisa'] ?? '' }}" placeholder="e.g. +92 3XX XXXXXXX">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-pro">JazzCash Number</label>
                                <input type="text" name="payment_jazzcash" class="form-control input-pro" value="{{ $settings['payment_jazzcash'] ?? '' }}" placeholder="e.g. +92 3XX XXXXXXX">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-pro">Bank Name</label>
                                <input type="text" name="payment_bank_name" class="form-control input-pro" value="{{ $settings['payment_bank_name'] ?? '' }}" placeholder="e.g. HBL Bank">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-pro">Account Title</label>
                                <input type="text" name="payment_bank_title" class="form-control input-pro" value="{{ $settings['payment_bank_title'] ?? '' }}" placeholder="e.g. John Doe">
                            </div>
                            <div class="col-12">
                                <label class="form-label-pro">Bank Account Number / IBAN</label>
                                <input type="text" name="payment_bank_number" class="form-control input-pro" value="{{ $settings['payment_bank_number'] ?? '' }}" placeholder="e.g. PK00 HABB 0000 0000 0000 0000">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Promotional Poster --}}
                <div class="settings-card">
                    <div class="card-header-pro">
                        <h6 class="m-0 fw-bold text-primary"><i class="bi bi-megaphone me-2"></i>Promotional Poster (Home Page)</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-pro">Poster Title / Heading</label>
                                <input type="text" name="home_poster_title" class="form-control input-pro" value="{{ $settings['home_poster_title'] ?? '' }}" placeholder="e.g. Special Offer: 50% Off on All Courses!">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-pro">Poster Visibility</label>
                                <select name="home_poster_enabled" class="form-select input-pro">
                                    <option value="1" {{ ($settings['home_poster_enabled'] ?? '') == '1' ? 'selected' : '' }}>Enabled (Visible on Home Page)</option>
                                    <option value="0" {{ ($settings['home_poster_enabled'] ?? '') == '0' ? 'selected' : '' }}>Disabled (Hidden)</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label-pro">Upload Poster Image</label>
                                <input type="file" name="home_poster" class="form-control input-pro" accept="image/*">
                                <small class="text-muted">This poster will be shown on the home page. Users can click to download it.</small>
                                
                                @if(isset($settings['home_poster']))
                                    <div class="mt-3 p-3 border rounded bg-light text-center">
                                        <p class="small text-muted mb-2">Current Poster Preview:</p>
                                        <img src="{{ asset('storage/'.$settings['home_poster']) }}" class="img-fluid rounded shadow-sm" style="max-height: 250px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Other --}}
                <div class="settings-card">
                    <div class="card-header-pro">
                        <h6 class="m-0 fw-bold text-primary"><i class="bi bi-c-circle me-2"></i>Footer Copyright</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label-pro">Custom Copyright Text</label>
                                <input type="text" name="footer_text" class="form-control input-pro" value="{{ $settings['footer_text'] ?? '' }}" placeholder="© 2026 Your Name. All Rights Reserved.">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-footer">
                    <button type="submit" class="btn btn-success px-5 fw-bold shadow-sm">Save All System Settings</button>
                </div>
            </form>
        </div>

        <!-- ── Email / SMTP Configurations Tab ── -->
        @if(auth()->user()->hasRole('admin'))
        <div class="tab-pane fade" id="pills-email" role="tabpanel">

            {{-- Quick Presets Helper Chips --}}
            <div class="settings-card bg-light border-0 p-3 mb-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h6 class="fw-bold text-dark mb-1"><i class="bi bi-magic me-2 text-primary"></i>Quick Presets & Guide</h6>
                        <span class="small text-muted">Click any preset below to quickly fill recommended SMTP port and encryption standards:</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="smtp-preset-chip" onclick="applyPreset('cpanel_ssl')">
                            <i class="bi bi-hdd-network"></i>cPanel Webmail (Port 465 SSL)
                        </span>
                        <span class="smtp-preset-chip" onclick="applyPreset('gmail')">
                            <i class="bi bi-google"></i>Gmail SMTP (Port 587 TLS)
                        </span>
                        <span class="smtp-preset-chip" onclick="applyPreset('custom_tls')">
                            <i class="bi bi-shield-check"></i>Custom SMTP (Port 587 TLS)
                        </span>
                    </div>
                </div>
            </div>

            <form action="{{ route('settings.update.email') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Mail Driver & Server Setup --}}
                <div class="settings-card">
                    <div class="card-header-pro d-flex align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold text-primary"><i class="bi bi-server me-2"></i>Mail Driver & Server Settings</h6>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill small fw-bold">Live .env Managed</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label-pro">Mail Protocol / Driver</label>
                                <select name="mail_mailer" id="mail_mailer" class="form-select input-pro" required>
                                    <option value="smtp" {{ ($mailSettings['mail_mailer'] ?? 'smtp') === 'smtp' ? 'selected' : '' }}>SMTP (Recommended)</option>
                                    <option value="sendmail" {{ ($mailSettings['mail_mailer'] ?? '') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="mailgun" {{ ($mailSettings['mail_mailer'] ?? '') === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                    <option value="ses" {{ ($mailSettings['mail_mailer'] ?? '') === 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                    <option value="log" {{ ($mailSettings['mail_mailer'] ?? '') === 'log' ? 'selected' : '' }}>Log File (Testing)</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-5">
                                <label class="form-label-pro">SMTP Host / Mail Server</label>
                                <input type="text" name="mail_host" id="mail_host" class="form-control input-pro" 
                                       value="{{ old('mail_host', $mailSettings['mail_host']) }}" 
                                       placeholder="e.g. mail.tread.com.pk or smtp.gmail.com" required>
                                <small class="text-muted" style="font-size: 10px;">Domain mail host or provider address.</small>
                            </div>

                            <div class="col-12 col-md-3">
                                <label class="form-label-pro">SMTP Port</label>
                                <input type="number" name="mail_port" id="mail_port" class="form-control input-pro" 
                                       value="{{ old('mail_port', $mailSettings['mail_port']) }}" 
                                       placeholder="e.g. 465, 587, 25, 2525" required>
                                <small class="text-muted" style="font-size: 10px;">SSL: 465 | TLS: 587 | Plain: 25/2525</small>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label-pro">Encryption Protocol</label>
                                <select name="mail_encryption" id="mail_encryption" class="form-select input-pro">
                                    <option value="ssl" {{ strtolower($mailSettings['mail_encryption']) === 'ssl' ? 'selected' : '' }}>SSL (Secure Sockets Layer - Port 465)</option>
                                    <option value="tls" {{ strtolower($mailSettings['mail_encryption']) === 'tls' ? 'selected' : '' }}>TLS (Transport Layer Security - Port 587)</option>
                                    <option value="none" {{ (empty($mailSettings['mail_encryption']) || strtolower($mailSettings['mail_encryption']) === 'none' || strtolower($mailSettings['mail_encryption']) === 'null') ? 'selected' : '' }}>None / Unencrypted (Port 25/2525)</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label-pro">SMTP Username / Email</label>
                                <input type="text" name="mail_username" id="mail_username" class="form-control input-pro" 
                                       value="{{ old('mail_username', $mailSettings['mail_username']) }}" 
                                       placeholder="info@tread.com.pk">
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label-pro">SMTP Password / App Key</label>
                                <input type="password" name="mail_password" id="mail_password" class="form-control input-pro" 
                                       value="{{ old('mail_password', $mailSettings['mail_password']) }}" 
                                       placeholder="••••••••••••••••">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sender Identity --}}
                <div class="settings-card">
                    <div class="card-header-pro">
                        <h6 class="m-0 fw-bold text-primary"><i class="bi bi-person-badge me-2"></i>Sender Identity ("From" Headers)</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-pro">Sender "From" Email Address</label>
                                <input type="email" name="mail_from_address" class="form-control input-pro" 
                                       value="{{ old('mail_from_address', $mailSettings['mail_from_address']) }}" 
                                       placeholder="info@tread.com.pk" required>
                                <small class="text-muted" style="font-size: 10px;">The email address recipient students will see as sender.</small>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-pro">Sender "From" Name</label>
                                <input type="text" name="mail_from_name" class="form-control input-pro" 
                                       value="{{ old('mail_from_name', $mailSettings['mail_from_name']) }}" 
                                       placeholder="LMS Academy" required>
                                <small class="text-muted" style="font-size: 10px;">Organization or portal name appearing in email headers.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-footer">
                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">Save Email Configurations</button>
                </div>
            </form>

            {{-- Test Email Connection Card --}}
            <div class="settings-card">
                <div class="card-header-pro bg-white">
                    <h6 class="m-0 fw-bold text-dark"><i class="bi bi-send-check-fill me-2 text-success"></i>Test SMTP Connection & Send Test Email</h6>
                </div>
                <div class="card-body p-4">
                    <p class="small text-muted mb-3">Send a test email to verify that your saved SMTP server credentials, port, and encryption work properly before sending emails to students.</p>
                    
                    <form action="{{ route('settings.test.email') }}" method="POST">
                        @csrf
                        <div class="row g-3 align-items-center">
                            <div class="col-12 col-md-8">
                                <label class="form-label-pro">Destination Test Email</label>
                                <input type="email" name="test_email" class="form-control input-pro" value="{{ auth()->user()->email }}" placeholder="Enter recipient email address..." required>
                            </div>
                            <div class="col-12 col-md-4 mt-md-4 pt-md-2">
                                <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm">
                                    <i class="bi bi-send me-2"></i>Send Test Email Now
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        @endif
    </div>
</div>

<script>
function applyPreset(type) {
    const hostInput = document.getElementById('mail_host');
    const portInput = document.getElementById('mail_port');
    const encInput  = document.getElementById('mail_encryption');

    if (type === 'cpanel_ssl') {
        if (!hostInput.value || hostInput.value.includes('gmail')) {
            hostInput.value = 'mail.tread.com.pk';
        }
        portInput.value = '465';
        encInput.value  = 'ssl';
    } else if (type === 'gmail') {
        hostInput.value = 'smtp.gmail.com';
        portInput.value = '587';
        encInput.value  = 'tls';
    } else if (type === 'custom_tls') {
        portInput.value = '587';
        encInput.value  = 'tls';
    }
}
</script>
@endsection

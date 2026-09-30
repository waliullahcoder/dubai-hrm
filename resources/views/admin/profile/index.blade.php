@extends('layouts.admin.app')

@section('breadcrumb')
<div class="d-flex gap-3 align-items-center justify-content-between px-sm-4 px-3 py-3 border-top border-light">
    <h4 class="mb-0 h6 fw-600 text-dark text-uppercase text-truncate-1">Profile</h4>
    <div class="flex-shrink-0">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ Route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Profile</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
@php
    $documents = [
        ['input' => 'profile_image',  'label' => 'Profile Photo',   'sub' => null,         'value' => $admin->image,          'default' => asset('backend/images/avatar/default/user.jpg'), 'icon' => 'fa-user'],
        ['input' => 'id_card_front',  'label' => 'ID Card',         'sub' => 'Front Side', 'value' => $admin->id_card_front,  'default' => null, 'icon' => 'fa-id-card'],
        ['input' => 'id_card_back',   'label' => 'ID Card',         'sub' => 'Back Side',  'value' => $admin->id_card_back,   'default' => null, 'icon' => 'fa-id-card'],
        ['input' => 'passport_image', 'label' => 'Passport',        'sub' => null,         'value' => $admin->passport_image, 'default' => null, 'icon' => 'fa-passport'],
    ];
    $docDone = collect($documents)->filter(fn($d) => !empty($d['value']))->count();
    $docTotal = count($documents);
    $isActive = $admin->status != 0;
@endphp

<style>
    .pf-card{background:#fff;border-radius:18px;padding:20px;box-shadow:0 2px 14px rgba(20,60,160,.07);margin-bottom:16px}
    .pf-avatar{position:relative;width:96px;height:96px;flex-shrink:0}
    .pf-avatar>img{width:96px;height:96px;border-radius:50%;object-fit:cover;border:4px solid #eef3ff}
    .pf-cam{position:absolute;right:-2px;bottom:-2px;width:34px;height:34px;border-radius:50%;background:#1a5cff;color:#fff;border:3px solid #fff;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:13px}
    .pf-dot{display:inline-block;width:11px;height:11px;border-radius:50%;background:#22a744;margin-right:6px}
    .pf-dot.off{background:#a3a3a3}
    .pf-progress{height:10px;border-radius:10px;background:#e8ecf3;overflow:hidden}
    .pf-progress>span{display:block;height:100%;border-radius:10px;background:#22a744;transition:width .6s ease}
    .pf-alert{background:#ffe9e9;color:#d61f1f;border-radius:10px;padding:10px 14px;display:flex;gap:10px;align-items:center;font-size:14px}
    .pf-alert.ok{background:#e6f7ec;color:#178a3a}
    .pf-alert i{font-size:16px}
    .pf-title{display:flex;align-items:center;gap:12px;margin-bottom:14px}
    .pf-title h5{margin:0;font-weight:700;color:#0d1b3e}
    .pf-ico{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;flex-shrink:0}
    .pf-ico.blue{background:#1a5cff}.pf-ico.purple{background:#8a3ffc}
    .pf-row{display:flex;align-items:center;gap:14px;padding:12px 0;border-top:1px solid #edf0f6}
    .pf-row .ri{width:38px;height:38px;border-radius:10px;background:#eaf1ff;color:#1a5cff;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .pf-row .ri.yellow{background:#fff3d6;color:#e59a00}
    .pf-row .k{color:#5b6578;flex:1}
    .pf-row .v{font-weight:600;color:#0d1b3e;text-align:right;word-break:break-word}
    .pf-edit-btn{background:#eaf1ff;color:#1a5cff;border:0;border-radius:10px;padding:8px 16px;font-weight:600}
    .pf-doc-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
    @media(max-width:991px){.pf-doc-grid{grid-template-columns:repeat(2,1fr)}}
    .pf-doc{border:1.5px solid #bfe6cc;background:#f4fbf6;border-radius:14px;padding:12px;display:flex;flex-direction:column}
    .pf-doc.missing{border:1.5px dashed #f2a5a5;background:#fff5f5}
    .pf-doc-head{display:flex;justify-content:space-between;align-items:flex-start;min-height:42px}
    .pf-doc-head b{display:block;color:#0d1b3e;font-size:14px}
    .pf-doc-head small{color:#7a8496}
    .pf-tick{width:24px;height:24px;border-radius:50%;background:#22a744;color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0}
    .pf-tick.bad{background:#e5202a}
    .pf-thumb{height:130px;border-radius:10px;background:#e9edf3;overflow:hidden;display:flex;align-items:center;justify-content:center;margin:8px 0 10px}
    .pf-thumb img{width:100%;height:100%;object-fit:cover}
    .pf-doc.missing .pf-thumb{background:#ffe9e9;border:1.5px dashed #f2a5a5;flex-direction:column;color:#d61f1f;font-size:14px;gap:6px}
    .pf-doc.missing .pf-thumb i{font-size:34px}
    .pf-upload{display:flex;align-items:center;justify-content:center;gap:8px;background:#eaf1ff;color:#1a5cff;border-radius:10px;padding:8px;font-weight:600;font-size:14px;cursor:pointer;margin:0}
    .pf-doc.missing .pf-upload{background:#fff;border:1px solid #f8d0d0}
    .pf-menu{display:flex;align-items:center;gap:16px;width:100%;text-align:left;background:#fff;border:0;border-radius:18px;padding:16px 20px;box-shadow:0 2px 14px rgba(20,60,160,.07);margin-bottom:16px;color:inherit;text-decoration:none}
    .pf-menu .mi{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0}
    .pf-menu .mi.purple{background:#f0e8ff;color:#8a3ffc}.pf-menu .mi.red{background:#ffe1e1;color:#e5202a}
    .pf-menu b{display:block;color:#0d1b3e}.pf-menu small{color:#6b7488}
    .pf-menu>.fa-chevron-right{margin-left:auto;color:#0d1b3e}
    #pf-save-bar{display:none;position:sticky;bottom:12px;z-index:20;background:#0d1b3e;color:#fff;border-radius:14px;padding:12px 16px;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;box-shadow:0 8px 24px rgba(0,0,0,.25)}
    #pf-save-bar.show{display:flex}
    .pf-collapse-form{display:none}
    .pf-collapse-form.show{display:block}
</style>

<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">

        {{-- ================= HEADER: avatar + completion ================= --}}
        <div class="pf-card">
            <div class="row g-3 align-items-center">
                <div class="col-md-5 d-flex align-items-center gap-3">
                    <div class="pf-avatar">
                        <img id="pf-avatar-img" src="{{ $admin->image ? asset($admin->image) : asset('backend/images/avatar/default/user.jpg') }}" alt="Profile photo">
                        <label for="pf-input-profile_image" class="pf-cam mb-0"><i class="fas fa-camera"></i></label>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-700 text-dark">{{ $admin->name }}</h4>
                        <div class="fs-5 text-secondary">{{ $admin->user_name }}</div>
                        <div class="fw-500"><span class="pf-dot {{ $isActive ? '' : 'off' }}"></span>{{ $isActive ? 'Active' : 'Inactive' }}</div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <span class="fw-600 fs-5 text-dark">Profile Completion</span>
                        <span class="fw-700 fs-3" style="color:#22a744">{{ $completion }}%</span>
                    </div>
                    <div class="pf-progress mb-3"><span style="width: {{ $completion }}%"></span></div>
                    @if ($completion < 100)
                        <div class="pf-alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>Please complete: {{ implode(', ', $missing) }}.</span>
                        </div>
                    @else
                        <div class="pf-alert ok">
                            <i class="fas fa-check-circle"></i>
                            <span>Your profile is 100% complete.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ================= PERSONAL INFORMATION ================= --}}
        <div class="pf-card">
            <div class="pf-title">
                <span class="pf-ico blue"><i class="fas fa-user"></i></span>
                <h5 class="flex-grow-1">Personal Information</h5>
                <button type="button" class="pf-edit-btn" id="pf-edit-toggle"><i class="fal fa-pencil-alt me-1"></i> Edit</button>
            </div>

            <div id="pf-info-view">
                <div class="pf-row"><span class="ri yellow"><i class="fas fa-id-card"></i></span><span class="k">Employee ID</span><span class="v">{{ $admin->user_name }}</span></div>
                <div class="pf-row"><span class="ri"><i class="fas fa-user"></i></span><span class="k">Name</span><span class="v">{{ $admin->name }}</span></div>
                <div class="pf-row"><span class="ri"><i class="fas fa-phone-alt"></i></span><span class="k">Mobile Number</span><span class="v">{{ $admin->phone ?: '—' }}</span></div>
                <div class="pf-row"><span class="ri"><i class="fas fa-envelope"></i></span><span class="k">Email Address</span><span class="v">{{ $admin->email ?: '—' }}</span></div>
                <div class="pf-row"><span class="ri"><i class="fas fa-map-marker-alt"></i></span><span class="k">Address</span><span class="v">{{ $admin->address ?: '—' }}</span></div>
            </div>

            <form id="pf-info-form" class="pf-collapse-form" action="{{ Route('admin.profile.update', $admin->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row g-3 pt-2">
                    <div class="col-md-6">
                        <label for="admin-name" class="form-label">Your Name</label>
                        <input class="form-control" type="text" id="admin-name" name="name" value="{{ old('name', $admin->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="admin-phone" class="form-label">Mobile Number</label>
                        <input class="form-control" type="tel" id="admin-phone" name="phone" value="{{ old('phone', $admin->phone) }}">
                    </div>
                    <div class="col-12">
                        <label for="admin-email" class="form-label">Email Address</label>
                        <input class="form-control" type="email" id="admin-email" name="email" value="{{ old('email', $admin->email) }}">
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" placeholder="Write your address here...">{{ old('address', $admin->address) }}</textarea>
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light btn-sm px-4" id="pf-edit-cancel">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4">Save changes</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- ================= REQUIRED DOCUMENTS ================= --}}
        <form action="{{ Route('admin.change-images', $admin->id) }}" method="post" enctype="multipart/form-data" id="pf-docs-form">
            @csrf
            @method('PUT')
            <div class="pf-card">
                <div class="pf-title">
                    <span class="pf-ico purple"><i class="fas fa-file-alt"></i></span>
                    <h5 class="flex-grow-1">Required Documents</h5>
                    <span class="badge rounded-pill px-3 py-2" style="background:{{ $docDone == $docTotal ? '#e6f7ec' : '#ffe9e9' }};color:{{ $docDone == $docTotal ? '#178a3a' : '#d61f1f' }}">{{ $docDone }} of {{ $docTotal }} uploaded</span>
                </div>

                <div class="pf-doc-grid">
                    @foreach ($documents as $doc)
                        @php
                            $has = !empty($doc['value']);
                            $src = $has ? asset($doc['value']) : ($doc['default'] ?? '');
                        @endphp
                        <div class="pf-doc {{ $has ? '' : 'missing' }}" data-doc="{{ $doc['input'] }}">
                            <div class="pf-doc-head">
                                <div>
                                    <b>{{ $doc['label'] }}</b>
                                    @if ($doc['sub'])<small>{{ $doc['sub'] }}</small>@endif
                                </div>
                                <span class="pf-tick {{ $has ? '' : 'bad' }}"><i class="fas {{ $has ? 'fa-check' : 'fa-exclamation' }}"></i></span>
                            </div>

                            <div class="pf-thumb">
                                @if ($has)
                                    <img src="{{ $src }}" alt="{{ $doc['label'] }}">
                                @else
                                    <i class="fas {{ $doc['icon'] }}"></i>
                                    <span>Upload {{ $doc['label'] }}</span>
                                @endif
                            </div>

                            <input class="d-none pf-file" type="file" id="pf-input-{{ $doc['input'] }}" name="{{ $doc['input'] }}" accept=".jpg,.jpeg,.png,.webp">
                            <label for="pf-input-{{ $doc['input'] }}" class="pf-upload">
                                <i class="fas fa-camera"></i> {{ $has ? 'Change Photo' : 'Upload Photo' }}
                            </label>
                        </div>
                    @endforeach
                </div>

                @if ($docDone < $docTotal)
                    <div class="pf-alert mt-3">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Please upload all required documents to complete your profile.</span>
                    </div>
                @endif
            </div>

            {{-- Save bar (appears after selecting a file) --}}
            <div id="pf-save-bar">
                <span>Do you want to save the selected image(s)?</span>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-light" id="pf-docs-cancel">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Save changes</button>
                </div>
            </div>
        </form>

        {{-- ================= CHANGE PASSWORD ================= --}}
        <button type="button" class="pf-menu" id="pf-pass-toggle">
            <span class="mi purple"><i class="fas fa-lock"></i></span>
            <span><b>Change Password</b><small>Update your account password</small></span>
            <i class="fas fa-chevron-right"></i>
        </button>

        <div class="pf-card pf-collapse-form" id="pf-pass-card">
            <form action="{{ Route('admin.change-password', $admin->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="old-password" class="form-label">Old Password</label>
                        <div class="input-group">
                            <input class="form-control" type="password" id="old-password" placeholder="Old Password" required name="old_password">
                            <button type="button" class="input-group-text password-toggler text-primary"><i class="fas fa-eye-slash fs-18"></i></button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="new-password" class="form-label">New Password</label>
                        <div class="input-group">
                            <input class="form-control" type="password" id="new-password" placeholder="New Password" required name="new_password">
                            <button type="button" class="input-group-text password-toggler text-primary"><i class="fas fa-eye-slash fs-18"></i></button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="confirm-password" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input class="form-control" type="password" id="confirm-password" placeholder="Confirm Password" required name="new_password_confirmation">
                            <button type="button" class="input-group-text password-toggler text-primary"><i class="fas fa-eye-slash fs-18"></i></button>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn px-4 btn-primary btn-sm">Update password</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- ================= LOGOUT ================= --}}
        <a href="{{ Route::has('admin.logout') ? Route('admin.logout') : '#' }}" class="pf-menu">
            <span class="mi red"><i class="fas fa-sign-out-alt"></i></span>
            <span><b>Logout</b><small>Sign out from your account</small></span>
            <i class="fas fa-chevron-right"></i>
        </a>

    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    $(document).ready(function () {

        // ---- Edit personal information ----
        $('#pf-edit-toggle').on('click', function () {
            $('#pf-info-view').hide();
            $('#pf-info-form').addClass('show');
            $(this).hide();
        });
        $('#pf-edit-cancel').on('click', function () {
            $('#pf-info-form').removeClass('show');
            $('#pf-info-view').show();
            $('#pf-edit-toggle').show();
        });

        // ---- Change password toggle ----
        $('#pf-pass-toggle').on('click', function () {
            $('#pf-pass-card').toggleClass('show');
        });

        // ---- Image preview + save bar ----
        $('.pf-file').on('change', function () {
            const file = this.files[0];
            const input = this;
            if (!file) return;

            // only one file at a time is previewed per input; keep others selected
            const url = URL.createObjectURL(file);
            const box = $(this).closest('.pf-doc');

            if (box.length) {
                box.removeClass('missing');
                box.find('.pf-thumb').html('<img src="' + url + '" alt="Preview">');
                box.find('.pf-tick').removeClass('bad').html('<i class="fas fa-check"></i>');
            }
            if (input.name === 'profile_image') {
                $('#pf-avatar-img').attr('src', url);
            }
            $('#pf-save-bar').addClass('show');
        });

        // The avatar camera label points to the profile image input, which is
        // inside the docs form - so it also lives in the same save flow.

        $('#pf-docs-cancel').on('click', function () {
            $('#pf-docs-form')[0].reset();
            window.location.reload();
        });
    });
</script>
@endpush

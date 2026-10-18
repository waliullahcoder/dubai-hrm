@extends('layouts.admin.create_app')

@section('content')
    <style>
        .staff-card { border-radius: 14px; overflow: hidden; background: #fff; box-shadow: 0 4px 18px rgba(16, 42, 92, .08); }
        .staff-hero {
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
            padding: 22px 28px; color: #fff;
            background: linear-gradient(100deg, #0b4aa8 0%, #1565d8 60%, #1a6fe6 100%);
        }
        .staff-hero .hero-left { display: flex; align-items: center; gap: 22px; }
        .staff-hero .hero-icon { font-size: 44px; }
        .staff-hero h4 { margin: 0; font-weight: 700; color: #fff; }
        .staff-hero p { margin: 2px 0 0; opacity: .95; }
        .staff-hero .brand { text-align: center; line-height: 1; }
        .staff-hero .brand .script { font-family: 'Brush Script MT', 'Segoe Script', cursive; font-size: 40px; }
        .staff-hero .brand .sub { letter-spacing: 4px; font-weight: 600; font-size: 13px; }

        .staff-body { display: flex; flex-wrap: wrap; }
        .staff-side { width: 300px; padding: 28px 22px; background: #f3f7fd; }
        .staff-main { flex: 1; min-width: 300px; padding: 26px 28px; }

        .avatar-wrap { position: relative; width: 190px; height: 190px; margin: 0 auto 18px; border: 2px dashed #b9c8e0; border-radius: 50%; padding: 10px; }
        .avatar-wrap img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; background: #e4e7eb; }
        .avatar-wrap .cam-btn {
            position: absolute; right: 6px; bottom: 10px; width: 46px; height: 46px; border-radius: 50%;
            background: #1565d8; color: #fff; border: 3px solid #fff; display: flex; align-items: center; justify-content: center; cursor: pointer;
        }
        .btn-upload { width: 100%; border: 1.5px solid #1565d8; color: #1565d8; background: #fff; font-weight: 600; }
        .btn-upload:hover { background: #1565d8; color: #fff; }
        .side-hint { text-align: center; color: #6b7280; font-size: 13px; margin-top: 8px; }
        .feature-list { margin-top: 24px; padding: 16px; background: #e9f0fb; border-radius: 10px; list-style: none; }
        .feature-list li { display: flex; align-items: center; gap: 10px; margin: 10px 0; font-size: 14px; color: #1f2a44; }
        .feature-list i { color: #1e9e4a; font-size: 20px; }

        .staff-main .form-label { color: #0f1f4b; font-weight: 600; }
        .staff-main .form-label i { color: #0f2a66; margin-right: 6px; }
        .staff-main .form-control, .staff-main .form-select, .staff-main .input-group-text { min-height: 46px; }
        .staff-main .input-group-text { background: #eef2f8; color: #1f2a44; font-weight: 600; }
        .staff-main .btn-auto { background: #eef2f8; border: 1px solid #ced4da; color: #1f2a44; }

        .staff-footer { display: flex; justify-content: flex-end; gap: 14px; padding: 18px 28px 26px; }
        .staff-footer .btn { min-width: 240px; padding: 12px 20px; font-weight: 700; font-size: 18px; }
        .btn-cancel { background: #e9eef6; color: #0f1f4b; border: 0; }
        .btn-cancel:hover { background: #dbe3ef; color: #0f1f4b; }

        @media (max-width: 768px) {
            .staff-side { width: 100%; }
            .staff-hero .brand { display: none; }
            .staff-footer .btn { min-width: 0; flex: 1; }
        }
    </style>

    <div class="staff-card">
        {{-- Header --}}
        <div class="staff-hero">
            <i class="fas fa-user-plus hero-icon" style="color:white !important"></i>
                <div>
                    <h4>Add New Staff / Employee</h4>
                    <p>Create a new staff member for Jannat Parties HRM</p>
                </div>
        </div>

        <div class="staff-body">
            {{-- Left: photo --}}
            <div class="staff-side">
                <div class="avatar-wrap">
                    <img id="photo_preview" src="{{ asset('backend/images/avatar/default.png') }}"
                        onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 fill=%22%23e4e7eb%22/><circle cx=%2250%22 cy=%2238%22 r=%2218%22 fill=%22%23697386%22/><ellipse cx=%2250%22 cy=%2282%22 rx=%2230%22 ry=%2222%22 fill=%22%23697386%22/></svg>'"
                        alt="Photo">
                    <label for="image" class="cam-btn"><i class="fas fa-camera" style="color:white !important"></i></label>
                </div>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png" class="d-none">
                <label for="image" class="btn btn-upload"><i class="fas fa-upload me-1"></i> Upload Photo</label>
                <div class="side-hint">JPG, PNG (Max 2MB)</div>
                @error('image')
                    <div class="text-danger small text-center mt-1">{{ $message }}</div>
                @enderror

                <ul class="feature-list">
                    <li><i class="fas fa-check-circle"></i> Quick &amp; Easy</li>
                    <li><i class="fas fa-check-circle"></i> Only Required Fields</li>
                    <li><i class="fas fa-check-circle"></i> Auto Generate Employee ID</li>
                    <li><i class="fas fa-check-circle"></i> Suitable for All Staff</li>
                </ul>
            </div>

            {{-- Right: fields --}}
            <div class="staff-main">
                <div class="row g-4">
                    @if (Auth::user()->hasRole('Software Admin'))
                        <div class="col-md-12">
                            <label for="company_id" class="form-label"><i class="fas fa-building"></i>Company <span class="text-danger">*</span></label>
                            <select name="company_id" id="company_id" class="select form-select" data-placeholder="Select Company" required>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-md-6">
                        <label for="name" class="form-label"><i class="fas fa-user"></i>Employee Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required
                            value="{{ old('name') }}" placeholder="Enter full name">
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label"><i class="fas fa-phone"></i>Mobile Number <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">🇦🇪 +971</span>
                            <input type="text" class="form-control" id="phone" name="phone" required
                                value="{{ old('phone') }}" placeholder="Enter mobile number">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="code" class="form-label"><i class="far fa-id-card"></i>Employee ID <span class="fw-normal">(Auto Generate)</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="code" name="code"
                                value="{{ old('code', $nextCode) }}" data-auto="{{ $nextCode }}" placeholder="EMP-001">
                            <button type="button" class="btn btn-auto" id="btn_auto_code"><i class="fas fa-sync-alt me-1"></i> Auto</button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="national_id" class="form-label"><i class="far fa-id-card"></i>Emirates ID</label>
                        <input type="text" class="form-control" id="national_id" name="national_id"
                            value="{{ old('national_id') }}" placeholder="Enter Emirates ID number">
                    </div>

                    <div class="col-md-6">
                        <label for="basic_salary" class="form-label"><i class="fas fa-coins"></i>Hourly Rate (AED) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">AED</span>
                            <input type="number" step="0.01" min="0" class="form-control" id="basic_salary" name="basic_salary" required
                                value="{{ old('basic_salary') }}" placeholder="Enter hourly rate">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="address" class="form-label"><i class="fas fa-map-marker-alt"></i>Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="address" name="address" rows="2" required
                            placeholder="Enter address (e.g. building, street, area, city)">{{ old('address') }}</textarea>
                    </div>

                    <div class="col-md-12">
                        <label for="hotel_id" class="form-label"><i class="fas fa-store"></i>Outlet <span class="fw-normal">(Optional)</span></label>
                        <select class="select form-select" id="hotel_id" name="hotel_id" data-placeholder="Select outlet">
                            <option value="">Select outlet</option>
                            @foreach ($hotels as $hotel)
                                <option value="{{ $hotel->id }}" {{ old('hotel_id') == $hotel->id ? 'selected' : '' }}>
                                    {{ $hotel->name }}{{ $hotel->address ? ', ' . $hotel->address : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer buttons (remove if your create_app layout already renders them) --}}
        <div class="staff-footer">
            <a href="{{ route('admin.employee.index') }}" class="btn btn-cancel"><i class="fas fa-times-circle me-2"></i>Cancel</a>
            <button type="submit" class="btn btn-success"><i class="fas fa-save me-2"></i>Save Staff</button>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            // file upload needs multipart form
            $('#image').closest('form').attr('enctype', 'multipart/form-data');

            // photo preview
            $('#image').on('change', function() {
                const file = this.files && this.files[0];
                if (!file) return;
                if (file.size > 2 * 1024 * 1024) {
                    alert('Image must be 2MB or smaller.');
                    $(this).val('');
                    return;
                }
                const reader = new FileReader();
                reader.onload = e => $('#photo_preview').attr('src', e.target.result);
                reader.readAsDataURL(file);
            });

            // auto employee id
            $('#btn_auto_code').on('click', function() {
                $('#code').val($('#code').data('auto'));
            });
        });
    </script>
@endpush
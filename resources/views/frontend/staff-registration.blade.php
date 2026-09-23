<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Staff Registration - Jannat Parties</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: #eef1f5;
    display: flex;
    justify-content: center;
    padding: 24px 12px;
    min-height: 100vh;
  }

  .card {
    background: #fbfbf9;
    width: 100%;
    max-width: 380px;
    border-radius: 18px;
    padding: 28px 22px 24px;
    box-shadow: 0 8px 24px rgba(20, 30, 60, 0.08);
  }

  /* Logo */
  .brand {
    text-align: center;
    margin-bottom: 18px;
  }
  .crown {
    color: #d4a017;
    font-size: 20px;
    margin-bottom: 2px;
  }
  .brand-mark {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    font-size: 34px;
    font-weight: 800;
    line-height: 1;
  }
  .brand-mark .j { color: #b8860b; }
  .brand-mark .p { color: #1a1a1a; }
  .brand-name {
    margin-top: 6px;
    font-weight: 800;
    font-size: 18px;
    letter-spacing: 0.5px;
    color: #1a1a1a;
  }
  .brand-sub {
    font-size: 10px;
    letter-spacing: 2px;
    color: #9a9a9a;
    margin-top: 2px;
    font-weight: 600;
  }

  /* Title */
  .title {
    text-align: center;
    color: #1957d6;
    font-size: 22px;
    font-weight: 700;
    margin-top: 18px;
  }
  .subtitle {
    text-align: center;
    color: #8b8f99;
    font-size: 13px;
    margin-top: 4px;
    margin-bottom: 22px;
  }

  /* Fields */
  .field {
    display: flex;
    align-items: center;
    gap: 12px;
    border: 1px solid #e2e5eb;
    border-radius: 12px;
    padding: 13px 14px;
    margin-bottom: 12px;
    background: #fcfcfd;
  }
  .field i.left-icon {
    color: #9aa0ab;
    font-size: 15px;
    width: 16px;
    text-align: center;
  }
  .field input {
    border: none;
    outline: none;
    background: transparent;
    flex: 1;
    font-size: 14px;
    color: #1a1a1a;
  }
  .field input::placeholder {
    color: #a7abb5;
  }
  .field .toggle-eye {
    color: #9aa0ab;
    cursor: pointer;
    font-size: 14px;
  }

  /* Upload photo */
  .upload-field {
    display: flex;
    align-items: center;
    gap: 14px;
    border: 1px dashed #d4d8e0;
    border-radius: 12px;
    padding: 12px 14px;
    margin-bottom: 12px;
    background: #fcfcfd;
    cursor: pointer;
  }
  .upload-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #1a1a1a;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
  }
  .upload-text strong {
    display: block;
    font-size: 13.5px;
    color: #1a1a1a;
    font-weight: 600;
  }
  .upload-text span {
    font-size: 12px;
    color: #9aa0ab;
  }
  .upload-field input[type="file"] {
    display: none;
  }

  /* Register button */
  .btn-register {
    width: 100%;
    background: #1957d6;
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 14px;
    font-size: 15.5px;
    font-weight: 700;
    margin-top: 10px;
    cursor: pointer;
    transition: background 0.15s ease;
  }
  .btn-register:hover { background: #1447b0; }

  .login-row {
    text-align: center;
    margin-top: 16px;
    font-size: 13px;
    color: #8b8f99;
  }
  .login-row a {
    color: #1957d6;
    font-weight: 700;
    text-decoration: none;
  }
  .login-row a:hover { text-decoration: underline; }
</style>
</head>
<body>

<div class="card">

  <div class="brand">
    <img src="{{asset(@$admin_setting->logo)}}" class="logo" alt="{{$admin_setting->title}}" style="border-radius:10px;">
                    
    <!-- <div class="crown"><i class="fas fa-crown"></i></div>
    <div class="brand-mark"><span class="j">J</span><span class="p">P</span></div> -->
    <div class="brand-name"><h1>{{$admin_setting->title}}</h1></div>
    <!-- <div class="brand-sub">ORGANIZES SERVICE LLC</div> -->
  </div>

  <div class="title">Staff Registration</div>
  <div class="subtitle">Create your account to get started</div>
 

 <form action="{{ route('frontend.staff.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Validation Error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin:0; padding-left:20px;color:red">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Session Error --}}
    @if(session('error'))
        <div style="text-align:center;color:red;margin-bottom:10px;">
            <strong>{{ session('error') }}</strong>
        </div>
    @endif

    <div class="field">
        <i class="fas fa-user left-icon"></i>
        <input type="text"
               name="name"
               placeholder="Staff Name"
               value="{{ old('name') }}"
               required>
    </div>

    <div class="field">
        <i class="fas fa-phone left-icon"></i>
        <input type="tel"
               name="phone"
               placeholder="Mobile Number"
               value="{{ old('phone') }}"
               required>
    </div>

    <div class="field">
        <i class="fas fa-envelope left-icon"></i>
        <input type="email"
               name="email"
               placeholder="Email Address"
               value="{{ old('email') }}"
               required>
    </div>

    <div class="field">
        <i class="fas fa-map-marker-alt left-icon"></i>
        <input type="text"
               name="address"
               placeholder="Address"
               value="{{ old('address') }}"
               required>
    </div>

    <label class="upload-field" for="profile_photo">
        <div class="upload-icon">
            <i class="fas fa-camera"></i>
        </div>

        <div class="upload-text">
            <strong>Upload Profile Photo</strong>
            <span>Tap to select a photo</span>
        </div>

        <input type="file"
               id="profile_photo"
               name="profile_photo"
               accept="image/*">
    </label>

    <div class="field">
        <i class="fas fa-lock left-icon"></i>

        <input type="password"
               id="password"
               name="password"
               placeholder="Password"
               required>

        <i class="fas fa-eye toggle-eye"
           onclick="togglePassword('password', this)"></i>
    </div>

    @if(session('error'))
        <div style="text-align:center;color:red;margin-bottom:10px;">
            <strong>{{ session('error') }}</strong>
        </div>
    @endif

    <div class="field">
        <i class="fas fa-lock left-icon"></i>

        <input type="password"
               id="confirm_password"
               name="confirm_password"
               placeholder="Confirm Password"
               required>

        <i class="fas fa-eye toggle-eye"
           onclick="togglePassword('confirm_password', this)"></i>
    </div>

    <button type="submit" class="btn-register">
        Register
    </button>

</form>

  <div class="login-row">
    Already have an account? <a href="{{url('admin')}}">Login</a>
  </div>

</div>

<script>
function togglePassword(fieldId, iconEl) {
  const field = document.getElementById(fieldId);
  const isHidden = field.type === 'password';
  field.type = isHidden ? 'text' : 'password';
  iconEl.classList.toggle('fa-eye');
  iconEl.classList.toggle('fa-eye-slash');
}
</script>

</body>
</html>

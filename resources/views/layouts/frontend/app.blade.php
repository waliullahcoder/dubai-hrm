<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login | {{ $admin_setting->title ?? 'HRM' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

    <style>
    :root {
        --blue: #2563eb;
        --blue-dark: #1d4ed8;
        --navy: #0f172a;
        --muted: #64748b;
        --border: #dbe3ee;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Roboto, sans-serif;
        min-height: 100vh;
        /* Background photo: put your image at public/assets/login-bg.jpg */
        background:#fffdfd;
        background-attachment: fixed;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px 16px;
    }

    .page {
        width: 100%;
        max-width: 430px;
        position: relative;
    }

    /* Language selector */
    .lang-wrap {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 10px;
    }

    .lang-btn {
        background: rgba(30, 41, 59, .55);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, .25);
        color: #fff;
        border-radius: 14px;
        padding: 8px 14px;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .lang-btn:hover,
    .lang-btn:focus,
    .lang-btn.show {
        background: rgba(30, 41, 59, .75);
        color: #fff;
    }

    .lang-btn i.fa-chevron-down {
        font-size: 11px;
        margin-left: 6px;
    }

    /* Logo */
    .logo-box {
        text-align: center;
        padding: 10px 0 24px;
    }

    .logo-box img {
        max-width: 230px;
        max-height: 130px;
        object-fit: contain;
    }

    /* Card */
    .login-card {
        background: rgba(255, 255, 255, .96);
        border-radius: 22px;
        padding: 30px 24px 22px;
        box-shadow: 0 25px 60px rgba(0, 0, 0, .3);
    }

    .login-card h2 {
        text-align: center;
        font-weight: 800;
        font-size: 28px;
        color: var(--navy);
    }

    .login-card h3 {
        text-align: center;
        font-weight: 700;
        font-size: 20px;
        color: var(--blue);
        margin-top: 4px;
    }

    .subtitle {
        text-align: center;
        color: var(--muted);
        font-size: 14px;
        margin: 6px 0 22px;
    }

    /* Inputs */
    .input-group-custom {
        position: relative;
        margin-bottom: 14px;
    }

    .input-group-custom .form-control {
        height: 52px;
        border-radius: 12px;
        border: 1px solid var(--border);
        background: #fff;
        padding-left: 46px;
        padding-right: 46px;
        font-size: 15px;
    }

    .input-group-custom .form-control::placeholder {
        color: #94a3b8;
    }

    .input-group-custom .form-control:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .15);
    }

    .input-group-custom .left-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 17px;
    }

    .input-group-custom .eye {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #475569;
        cursor: pointer;
        font-size: 17px;
    }

    /* Remember / forgot */
    .row-opts {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 4px 0 18px;
        font-size: 14px;
        color: #334155;
    }

    .row-opts label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .row-opts input[type=checkbox] {
        width: 18px;
        height: 18px;
        accent-color: var(--blue);
    }

    .forgot {
        color: var(--blue);
        font-weight: 600;
        text-decoration: none;
    }

    .forgot:hover {
        text-decoration: underline;
    }

    /* Buttons */
    .login-btn {
        width: 100%;
        height: 54px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        color: #fff;
        font-size: 16px;
        font-weight: 700;
        letter-spacing: .3px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: .25s;
    }

    .login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(37, 99, 235, .35);
    }

    .divider {
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--muted);
        font-size: 13px;
        margin: 18px 0;
    }

    .divider::before,
    .divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    .register-btn {
        width: 100%;
        height: 52px;
        border-radius: 12px;
        border: 1.5px solid #93b4ea;
        background: #f1f6ff;
        color: var(--blue-dark);
        font-weight: 700;
        font-size: 15px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: .25s;
    }

    .register-btn:hover {
        background: #e3edff;
        color: var(--blue-dark);
    }

    /* Footer */
    .footer {
        text-align: center;
        color: #fff;
        font-size: 12.5px;
        margin-top: 18px;
        text-shadow: 0 1px 3px rgba(0, 0, 0, .5);
        line-height: 1.6;
    }

    .footer b {
        font-weight: 700;
    }

    .alert {
        border-radius: 12px;
        font-size: 14px;
    }
    </style>
</head>

<body>

    <div class="page">

        <!-- Language -->
        <!-- <div class="lang-wrap">
            <div class="dropdown">
                <button class="lang-btn dropdown-toggle-custom" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-globe"></i>
                    <span>English</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="?lang=en">English</a></li>
                    <li><a class="dropdown-item" href="?lang=bn">বাংলা</a></li>
                </ul>
            </div>
        </div> -->

        <!-- Logo -->
        <div class="logo-box">
            <img src="{{ asset(@$admin_setting->logo) }}" alt="{{ @$admin_setting->title }}">
        </div>

        <!-- Card -->
        <div class="login-card">

            <h2>Welcome Back</h2>
            <h3>Staff Login</h3>
            <p class="subtitle">Sign in to access your account.</p>

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="input-group-custom">
                    <i class="fas fa-user left-icon"></i>
                    <input type="text" name="user_name" class="form-control"
                        placeholder="Mobile Number" required autofocus>
                </div>

                <div class="input-group-custom">
                    <i class="fas fa-lock left-icon"></i>
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Password" required>
                    <span class="eye" id="togglePassword"><i class="fas fa-eye"></i></span>
                </div>

                <div class="row-opts">
                    <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                    <a href="#" class="forgot">Forgot Password?</a>
                </div>

                <button type="submit" class="login-btn">
                    <i class="fas fa-right-to-bracket"></i>
                    LOGIN AS STAFF
                </button>
            </form>

            <div class="divider">OR</div>

            <a href="{{ url('staff-registration') }}" class="register-btn">
                <i class="fas fa-file-circle-plus"></i>
                Register as Staff
            </a>

        </div>

        <!-- Footer -->
        <div class="footer">
            © {{ date('Y') }} <b>{{ @$admin_setting->title }}</b><br>
            Employee &amp; Payroll Management System
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    const toggle = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    toggle.addEventListener('click', function() {
        const show = password.type === 'password';
        password.type = show ? 'text' : 'password';
        toggle.innerHTML = show ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
    });
    </script>

</body>

</html>
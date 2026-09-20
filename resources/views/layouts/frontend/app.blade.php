<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>HRM Login | Techno Park Bangladesh</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {

        font-family: 'Segoe UI', sans-serif;
        min-height: 100vh;

        background: linear-gradient(-45deg, #0f172a, #1e3a8a, #0f766e, #111827);
        background-size: 400% 400%;
        animation: bgAnimation 12s ease infinite;

        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;

    }

    @keyframes bgAnimation {

        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }

    }

    .circle {

        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, .08);
        animation: float 8s infinite ease-in-out;

    }

    .circle:nth-child(1) {

        width: 250px;
        height: 250px;
        left: -80px;
        top: -80px;

    }

    .circle:nth-child(2) {

        width: 180px;
        height: 180px;
        right: -40px;
        bottom: -30px;

    }

    .circle:nth-child(3) {

        width: 120px;
        height: 120px;
        left: 15%;
        bottom: 8%;

    }

    @keyframes float {

        50% {

            transform: translateY(-20px);

        }

    }

    .login-wrapper {

        width: 1100px;
        max-width: 95%;
        background: rgba(255, 255, 255, .08);

        backdrop-filter: blur(20px);

        border-radius: 25px;

        overflow: hidden;

        box-shadow: 0 25px 70px rgba(0, 0, 0, .45);

    }

    .left-panel {

        background:linear-gradient(135deg, #0b282e, #06b6d4);

        color: #fff;

        padding: 70px 60px;

        display: flex;

        flex-direction: column;

        justify-content: center;

        height: 100%;

    }

    .logo {
      
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #fff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 45px;
        margin-bottom: 30px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, .2);
        margin-top:20px;
    }

    .left-panel h1 {

        font-size: 38px;
        font-weight: 700;
        margin-bottom: 15px;

    }

    .left-panel p {

        color: #e5e7eb;
        line-height: 30px;
        font-size: 17px;

    }

    .features {

        margin-top: 35px;

    }

    .features li {

        list-style: none;
        margin-bottom: 18px;
        font-size: 16px;

    }

    .features i {

        margin-right: 10px;
        color: #fff;

    }

    .right-panel {

        background: #ececed;

        padding: 60px;

    }

    .right-panel h2 {

        font-weight: 700;

        color: #0f172a;

    }

    .subtitle {

        color: #64748b;

        margin-bottom: 35px;

    }

    .form-control {

        height: 55px;

        border-radius: 12px;

        padding-left: 45px;

    }

    .form-control:focus {

        border-color: #2563eb;

        box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .15);

    }

    .input-group-custom {

        position: relative;

        margin-bottom: 25px;

    }

    .input-group-custom .left-icon {

        position: absolute;
        left: 15px;
        top: 17px;
        color: #94a3b8;

    }

    .input-group-custom .eye {

        position: absolute;
        right: 18px;
        top: 18px;
        cursor: pointer;
        color: #94a3b8;

    }

    .login-btn {

        width: 100%;

        height: 55px;

        border: none;

        border-radius: 12px;

        background: linear-gradient(135deg, #2563eb, #06b6d4);

        color: #fff;

        font-size: 17px;

        font-weight: 700;

        transition: .3s;

    }

    .login-btn:hover {

        transform: translateY(-2px);

        box-shadow: 0 15px 35px rgba(37, 99, 235, .25);

    }

    .forgot {

        text-decoration: none;

        color: #2563eb;

        font-weight: 600;

    }

    .footer {

        text-align: center;

        margin-top: 30px;

        color: #64748b;

        font-size: 14px;

    }

    @media(max-width:991px) {

        .left-panel {

            display: none;

        }

        .right-panel {

            padding: 35px;

        }

    }
    </style>

</head>

<body>

    <div class="circle"></div>
    <div class="circle"></div>
    <div class="circle"></div>

    <div class="login-wrapper">

        <div class="row g-0">

            <div class="col-lg-6">

                <div class="left-panel">

                    <div>

                    <img src="{{asset(@$admin_setting->logo)}}" class="logo" alt="{{$admin_setting->title}}">
                    
                    </div>

                    <h1>{{$admin_setting->title}}</h1>

                    <ul class="features">

                        <li>
                            <i class="fas fa-check-circle"></i>
                            Staff Management
                        </li>

                        <li>
                            <i class="fas fa-check-circle"></i>
                            Salary Management
                        </li>

                        <li>
                            <i class="fas fa-check-circle"></i>
                           Attendance Management
                        </li>

                        <li>
                            <i class="fas fa-check-circle"></i>
                            Cash Management
                        </li>

                        <li>
                            <i class="fas fa-check-circle"></i>
                            Reports & Analytics
                        </li>

                    </ul>

                </div>

            </div>

            <div class="col-lg-6">

                <div class="right-panel">

                    <h2>Welcome Back 👋</h2>

                    <p class="subtitle">

                        Sign in to continue to your HRM Dashboard.

                    </p>

                    <form method="POST" action="{{ route('admin.login') }}">

                        @csrf

                        @if(session('error'))

                        <div class="alert alert-danger">

                            {{ session('error') }}

                        </div>

                        @endif

                        <div class="input-group-custom">

                            <i class="fas fa-user left-icon"></i>

                            <input type="text" name="user_name" class="form-control" value="admin" required>

                        </div>

                        <div class="input-group-custom">

                            <i class="fas fa-lock left-icon"></i>

                            <input type="password" name="password" id="password" class="form-control"
                                value="12345678" required>

                            <span class="eye" id="togglePassword">

                                <i class="fas fa-eye"></i>

                            </span>

                        </div>

                        <div class="d-flex justify-content-between mb-4">

                            <div>

                                <input type="checkbox" name="remember">

                                Remember Me

                            </div>

                            <a href="#" class="forgot">

                                Forgot Password?

                            </a>

                        </div>

                        <button class="login-btn">

                            <i class="fas fa-sign-in-alt me-2"></i>

                            LOGIN TO HRM

                        </button>

                    </form>

                    <div class="footer">

                        <hr>

                        © {{ date('Y') }}

                        <b>Techno Park Bangladesh</b>

                        <br>

                        Employee & Payroll Management System

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>
    const toggle = document.getElementById('togglePassword');

    const password = document.getElementById('password');

    toggle.onclick = function() {

        const type = password.type === 'password' ? 'text' : 'password';

        password.type = type;

        toggle.innerHTML = password.type === 'password' ?
            '<i class="fas fa-eye"></i>' :
            '<i class="fas fa-eye-slash"></i>';

    }
    </script>

</body>

</html>
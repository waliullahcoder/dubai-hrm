 <div class="app-header">
            <div class="user-profile">
                
                <img src="{{ file_exists(Auth::user()->image) ? asset(Auth::user()->image) : asset('backend/images/avatar/default/user.jpg') }}" alt="Avatar" class="user-avatar">
                <div class="user-info">
                    <p class="greeting">Welcome to Staff Dashboard,</p>
                    <h5 class="name">{{ Auth::user()->name }}</h5>
                    <span class="emp-id">ID-{{ $staff->code }}, {{ $staff->address }}</span>
                </div>
            </div>
            <a href="{{url('admin/dashboard')}}" class="notification-btn">
                <i class="fas fa-home"></i>
            </a>
        </div>
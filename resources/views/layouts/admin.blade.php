<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Panel Admin LSP Seminar</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    
    @yield('css')
</head>
<body>

    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar d-none d-md-block">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex align-items-center">
                    <i class="bi bi-mortarboard-fill text-warning me-2 fs-4"></i>
                    <span class="text-white fw-bold fs-5">ADMIN<span class="text-warning">LSP</span></span>
                </a>
            </div>
            <ul class="sidebar-menu">
                <li class="sidebar-item">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin.accounts.index') }}" class="sidebar-link {{ Route::is('admin.accounts.index') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <span>Verifikasi Akun</span>
                        @php
                            $pendingUsers = \App\Models\User::where('role', 'participant')->where('account_status', 'pending')->count();
                        @endphp
                        @if($pendingUsers > 0)
                            <span class="badge bg-warning text-dark ms-auto rounded-pill small">{{ $pendingUsers }}</span>
                        @endif
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin.seminars.index') }}" class="sidebar-link {{ Route::is('admin.seminars.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event-fill"></i>
                        <span>Manajemen Seminar</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin.registrations.index') }}" class="sidebar-link {{ Route::is('admin.registrations.index') ? 'active' : '' }}">
                        <i class="bi bi-credit-card-fill"></i>
                        <span>Verifikasi Bayar</span>
                        @php
                            $pendingReg = \App\Models\SeminarRegistration::where('payment_status', 'waiting_verification')->count();
                        @endphp
                        @if($pendingReg > 0)
                            <span class="badge bg-danger ms-auto rounded-pill text-white small">{{ $pendingReg }}</span>
                        @endif
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin.announcements.index') }}" class="sidebar-link {{ Route::is('admin.announcements.*') ? 'active' : '' }}">
                        <i class="bi bi-megaphone-fill"></i>
                        <span>Pengumuman</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content Area -->
        <div class="admin-content d-flex flex-column">
            <!-- Navbar -->
            <nav class="navbar navbar-expand admin-navbar bg-white shadow-sm px-4 justify-content-between">
                <button class="btn btn-outline-secondary d-md-none me-3" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                    <i class="bi bi-list"></i>
                </button>
                <div class="d-flex align-items-center">
                    <span class="fw-bold fs-5 text-secondary">@yield('page_title', 'Dashboard')</span>
                </div>
                <div class="dropdown">
                    <button class="btn dropdown-toggle border-0 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <span class="fw-medium text-secondary">{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-item py-2" href="{{ route('welcome') }}"><i class="bi bi-globe me-2"></i>Lihat Landing Page</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Collapsible Mobile Sidebar -->
            <div class="collapse d-md-none bg-dark" id="mobileSidebar">
                <ul class="sidebar-menu px-3 py-2">
                    <li class="sidebar-item">
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-grid-1x2-fill"></i> Dashboard
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('admin.accounts.index') }}" class="sidebar-link {{ Route::is('admin.accounts.index') ? 'active' : '' }}">
                            <i class="bi bi-people-fill"></i> Verifikasi Akun
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('admin.seminars.index') }}" class="sidebar-link {{ Route::is('admin.seminars.*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-event-fill"></i> Manajemen Seminar
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('admin.registrations.index') }}" class="sidebar-link {{ Route::is('admin.registrations.index') ? 'active' : '' }}">
                            <i class="bi bi-credit-card-fill"></i> Verifikasi Bayar
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('admin.announcements.index') }}" class="sidebar-link {{ Route::is('admin.announcements.*') ? 'active' : '' }}">
                            <i class="bi bi-megaphone-fill"></i> Pengumuman
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Page Body Content -->
            <div class="container-fluid p-4">
                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="bg-white border-top py-3 text-center text-muted mt-auto small">
                &copy; {{ date('Y') }} LSP-UMDP Admin Panel.
            </footer>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Global SweetAlert Flash Handling -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#4f46e5'
                });
            @endif

            @if($errors->has('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: "{{ $errors->first('error') }}",
                    confirmButtonColor: '#4f46e5'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#4f46e5'
                });
            @endif
        });
    </script>

    @yield('js')
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Portal Peserta LSP Seminar</title>
    
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
<body class="d-flex flex-column min-vh-100 bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand text-white d-flex align-items-center" href="{{ route('participant.dashboard') }}">
                <i class="bi bi-mortarboard-fill me-2 text-warning"></i>
                <span>PESERTA<span class="text-warning">LSP</span></span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#participantNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="participantNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom text-white-50 {{ Route::is('participant.dashboard') ? 'active text-warning' : '' }}" href="{{ route('participant.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom text-white-50" href="{{ route('public.seminars') }}">Daftar Seminar</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <div class="text-white-50 small me-2 d-none d-lg-block">
                        Status Akun: 
                        <span class="badge badge-custom badge-approved"><i class="bi bi-patch-check-fill me-1"></i>AKTIF</span>
                    </div>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle text-white border-0 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                            <div class="avatar bg-warning text-dark fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-size: 0.95rem;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="fw-medium">{{ auth()->user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li>
                                <div class="px-3 py-2 text-muted small border-bottom">
                                    <strong>{{ auth()->user()->email }}</strong><br>
                                    {{ auth()->user()->institution }}
                                </div>
                            </li>
                            <li><a class="dropdown-item py-2" href="{{ route('welcome') }}"><i class="bi bi-globe me-2"></i>Halaman Utama</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1 py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white-50 py-4 mt-auto border-top border-secondary small">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Sistem Pendaftaran Seminar LSP. All rights reserved.</p>
        </div>
    </footer>

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

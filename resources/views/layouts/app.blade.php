<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Sistem Pendaftaran Seminar UMDP</title>
    
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
<body class="d-flex flex-column min-vh-100">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand text-white d-flex align-items-center" href="{{ route('welcome') }}">
                <i class="bi bi-mortarboard-fill me-2 text-warning"></i>
                <span>SEMINAR<span class="text-warning">   UMDP</span></span>
            </a>
            <button class="navbar-dark navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom text-white-50 {{ Route::is('welcome') ? 'active text-warning' : '' }}" href="{{ route('welcome') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom text-white-50 {{ Route::is('public.seminars') ? 'active text-warning' : '' }}" href="{{ route('public.seminars') }}">Daftar Seminar</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-warning fw-semibold">Dashboard Admin</a>
                        @else
                            <a href="{{ route('participant.dashboard') }}" class="btn btn-warning fw-semibold">Dashboard Peserta</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light"><i class="bi bi-box-arrow-right"></i></button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light fw-medium px-4">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-warning fw-semibold px-4">Daftar Akun</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white-50 py-5 mt-auto border-top border-secondary">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <div class="d-flex align-items-center mb-2 justify-content-center justify-content-md-start">
                        <i class="bi bi-mortarboard-fill me-2 text-warning fs-4"></i>
                        <span class="text-white fw-bold fs-5">SEMINAR<span class="text-warning">  UMDP</span></span>
                    </div>
                    <p class="small mb-0">Sistem Pendaftaran Seminar UMDP</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="small mb-0">&copy; {{ date('Y') }} UMDP. All rights reserved.</p>
                </div>
            </div>
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

            @if(session('success_register'))
                Swal.fire({
                    icon: 'success',
                    title: 'Pendaftaran Berhasil!',
                    text: "{{ session('success_register') }}",
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
                    title: 'Akses Ditolak',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#4f46e5'
                });
            @endif

            @if(session('error_status') && session('error_status') === 'pending')
                Swal.fire({
                    icon: 'warning',
                    title: 'Akun Belum Aktif',
                    text: 'Akun Anda sedang menunggu verifikasi oleh Administrator. Harap periksa kembali nanti.',
                    confirmButtonColor: '#4f46e5'
                });
            @endif

            @if(session('error_status') && session('error_status') === 'rejected')
                Swal.fire({
                    icon: 'error',
                    title: 'Akun Ditolak',
                    text: 'Mohon maaf, pendaftaran akun Anda ditolak oleh Administrator. Silakan hubungi admin.',
                    confirmButtonColor: '#b91c1c'
                });
            @endif
        });
    </script>

    @yield('js')
</body>
</html>

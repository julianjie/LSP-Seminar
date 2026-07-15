@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-md-5">
            <div class="card card-premium p-4 shadow-lg border-0 animate-fade-in-up">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-mortarboard-fill text-warning fs-1"></i>
                        <h3 class="fw-bold mt-2">Selamat Datang</h3>
                        <p class="text-muted small">Silakan masuk menggunakan akun Anda yang telah terverifikasi</p>
                    </div>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" id="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                                <input type="password" name="password" id="password" class="form-control bg-light border-start-0 @error('password') is-invalid @enderror" placeholder="••••••••" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                                <label for="remember" class="form-check-label small text-muted">Ingat Saya</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-premium w-100 py-2.5 fs-6">Masuk Sekarang</button>
                    </form>

                    <div class="text-center mt-4 border-top pt-3">
                        <p class="text-muted small mb-0">Belum memiliki akun peserta? <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Daftar Akun Baru</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

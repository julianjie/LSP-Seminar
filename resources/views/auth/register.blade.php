@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6">
            <div class="card card-premium p-4 shadow-lg border-0 animate-fade-in-up">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus-fill text-warning fs-1"></i>
                        <h3 class="fw-bold mt-2">Daftar Akun Peserta</h3>
                        <p class="text-muted small">Buat akun untuk mendaftar seminar sertifikasi LSP</p>
                    </div>

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" name="name" id="name" class="form-control bg-light @error('name') is-invalid @enderror" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold">Alamat Email</label>
                                <input type="email" name="email" id="email" class="form-control bg-light @error('email') is-invalid @enderror" placeholder="nama@email.com" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label fw-semibold">Nomor Telepon / WhatsApp</label>
                                <input type="text" name="phone" id="phone" class="form-control bg-light @error('phone') is-invalid @enderror" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="institution" class="form-label fw-semibold">Asal Institusi / Sekolah</label>
                                <input type="text" name="institution" id="institution" class="form-control bg-light @error('institution') is-invalid @enderror" placeholder="Universitas / Sekolah" value="{{ old('institution') }}" required>
                                @error('institution')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input type="password" name="password" id="password" class="form-control bg-light @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control bg-light" placeholder="Ulangi password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-premium w-100 py-2.5 fs-6">Daftar Sekarang</button>
                    </form>

                    <div class="text-center mt-4 border-top pt-3">
                        <p class="text-muted small mb-0">Sudah memiliki akun? <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Masuk di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

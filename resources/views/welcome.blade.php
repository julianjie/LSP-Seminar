@extends('layouts.app')

@section('title', 'Sistem Pendaftaran Seminar Sertifikasi')

@section('content')
<!-- Hero Section -->
<section class="hero-section text-white d-flex align-items-center">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0 text-center text-lg-start animate-fade-in-up">
                <span class="badge bg-warning text-dark fw-bold px-3 py-2 mb-3">LSP WEB DEVELOPER</span>
                <h1 class="hero-title mb-4">Sistem Pendaftaran Seminar LSP</h1>
                <p class="lead text-white-50 mb-5">Daftarkan diri Anda untuk mengikuti berbagai seminar berkualitas yang dirancang khusus untuk meningkatkan kompetensi pengembang web profesional Anda.</p>
                <div class="d-flex gap-3 justify-content-center justify-content-lg-start">
                    <a href="{{ route('public.seminars') }}" class="btn btn-warning btn-lg fw-bold px-4 py-3 shadow-lg">Jelajahi Seminar</a>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg fw-medium px-4 py-3">Daftar Akun</a>
                    @endguest
                </div>
            </div>
            <div class="col-lg-6 text-center animate-fade-in-up" style="animation-delay: 0.2s;">
                <!-- Fallback premium illustration using CSS -->
                <div class="p-4 bg-white bg-opacity-10 backdrop-blur rounded-4 shadow-lg border border-white border-opacity-10 d-inline-block">
                    <div class="bg-gradient bg-primary text-white p-5 rounded-4 text-center" style="max-width: 450px; min-height: 300px; background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%) !important;">
                        <i class="bi bi-mortarboard-fill display-1 mb-4 text-warning"></i>
                        <h3 class="fw-bold">LSP-UMDP</h3>
                        <p class="mb-0 text-white-50">Mengukur Kompetensi, Menjamin Kualitas Profesional Pengembang Web di Indonesia.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest Seminars Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark">Seminar Terbaru</h2>
            <p class="text-muted">Jangan lewatkan seminar terkini dari pembicara ahli</p>
        </div>

        <div class="row g-4 justify-content-center">
            @forelse($latestSeminars as $seminar)
                <div class="col-md-4">
                    <div class="card card-premium h-100 border-0 shadow">
                        @if($seminar->poster)
                            <img src="{{ asset('storage/' . $seminar->poster) }}" class="card-img-top card-img-premium" alt="{{ $seminar->title }}">
                        @else
                            <div class="bg-gradient text-white d-flex flex-column align-items-center justify-content-center px-4 py-5 card-img-premium text-center" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;">
                                <i class="bi bi-calendar-event fs-1 mb-2 text-warning"></i>
                                <span class="fw-bold text-uppercase tracking-wider small text-white-50">Seminar</span>
                                <h5 class="fw-bold mb-0 mt-1" style="font-size: 1.1rem;">{{ $seminar->title }}</h5>
                            </div>
                        @endif
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge badge-custom badge-approved">{{ $seminar->speaker }}</span>
                                <span class="fw-bold {{ $seminar->price == 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $seminar->price == 0 ? 'GRATIS' : 'Rp ' . number_format($seminar->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <h5 class="card-title fw-bold text-dark mb-3">{{ $seminar->title }}</h5>
                            <p class="card-text text-muted text-sm line-clamp-3 mb-4" style="font-size: 0.9rem;">
                                {{ Str::limit($seminar->description, 120) }}
                            </p>
                            <div class="mt-auto border-top pt-3 d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    <i class="bi bi-calendar-check me-1"></i> {{ $seminar->seminar_date->format('d M Y') }}
                                </div>
                                <a href="{{ route('public.seminar.detail', $seminar->slug) }}" class="btn btn-premium btn-sm">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-calendar-x display-3 text-muted"></i>
                    <p class="mt-3 text-muted">Belum ada seminar yang dipublikasikan saat ini.</p>
                </div>
            @endforelse
        </div>

        @if($latestSeminars->count() > 0)
            <div class="text-center mt-5">
                <a href="{{ route('public.seminars') }}" class="btn btn-premium-outline px-4 py-2.5">Lihat Semua Seminar</a>
            </div>
        @endif
    </div>
</section>

<!-- About App Section -->
<section class="py-5 bg-white border-top">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4">Mengenai Sertifikasi LSP</h2>
                <p>Sertifikasi kompetensi kerja adalah proses pemberian sertifikat kompetensi yang dilakukan secara sistematis dan obyektif melalui uji kompetensi sesuai Standar Kompetensi Kerja Nasional Indonesia (SKKNI).</p>
                <div class="d-flex align-items-start gap-3 mt-4">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
                        <i class="bi bi-check-circle-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Terstandarisasi Nasional</h5>
                        <p class="text-muted small mb-0">Materi uji kompetensi berpedoman pada standar BNSP dan kebutuhan industri teknologi informasi terkini.</p>
                    </div>
                </div>
                <div class="d-flex align-items-start gap-3 mt-4">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
                        <i class="bi bi-shield-lock-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Meningkatkan Kredibilitas</h5>
                        <p class="text-muted small mb-0">Memiliki sertifikat kompetensi membuktikan keahlian Anda diakui secara sah di dunia kerja internasional.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-4">
                    <div class="col-6">
                        <div class="p-4 bg-light rounded-4 shadow-sm text-center">
                            <i class="bi bi-people display-5 text-primary mb-3"></i>
                            <h3 class="fw-bold mb-0">150+</h3>
                            <span class="text-muted small">Peserta Terdaftar</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-4 bg-light rounded-4 shadow-sm text-center">
                            <i class="bi bi-journals display-5 text-primary mb-3"></i>
                            <h3 class="fw-bold mb-0">12+</h3>
                            <span class="text-muted small">Seminar Berjalan</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-4 bg-light rounded-4 shadow-sm text-center">
                            <i class="bi bi-award display-5 text-primary mb-3"></i>
                            <h3 class="fw-bold mb-0">100%</h3>
                            <span class="text-muted small">Tersertifikasi BNSP</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-4 bg-light rounded-4 shadow-sm text-center">
                            <i class="bi bi-building display-5 text-primary mb-3"></i>
                            <h3 class="fw-bold mb-0">5+</h3>
                            <span class="text-muted small">Institusi Mitra</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@extends('layouts.app')

@section('title', 'Daftar Seminar')

@section('content')
<div class="bg-dark py-5 text-white text-center mb-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%) !important;">
    <div class="container py-3">
        <h1 class="fw-bold">Jelajahi Seminar</h1>
        <p class="text-white-50 lead mb-0">Temukan dan ikuti seminar kompetensi yang Anda butuhkan</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        @forelse($seminars as $seminar)
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

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $seminars->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@extends('layouts.participant')

@section('title', 'Dashboard Peserta')

@section('content')
<div class="row g-4 mb-4 animate-fade-in-up">
    <div class="col-12">
        <div class="card card-premium p-4 shadow-sm bg-white border-0">
            <div class="card-body p-0 d-flex flex-md-row align-items-center justify-content-between flex-column gap-3">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Selamat Datang, {{ $user->name }}!</h3>
                    <p class="text-muted mb-0"><i class="bi bi-building-fill me-1"></i> Asal Institusi: <strong>{{ $user->institution }}</strong> | <i class="bi bi-telephone-fill me-1"></i> Telepon: <strong>{{ $user->phone }}</strong></p>
                </div>
                <div>
                    <span class="badge badge-custom badge-approved px-4 py-2.5 fs-6"><i class="bi bi-patch-check-fill me-1"></i>Akun Terverifikasi</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4 animate-fade-in-up" style="animation-delay: 0.1s;">
    <!-- Left Column: Announcements -->
    <div class="col-lg-4">
        <div class="card card-premium shadow border-0 p-4 h-100">
            <h5 class="fw-bold text-dark mb-4"><i class="bi bi-megaphone-fill text-primary me-2"></i>Pengumuman Terbaru</h5>
            <div class="d-flex flex-column gap-3">
                @forelse($announcements as $ann)
                    <div class="p-3 bg-light rounded-3">
                        @if($ann->image)
                            <img src="{{ asset('storage/' . $ann->image) }}" class="img-fluid rounded mb-2 w-100" style="max-height: 120px; object-fit: cover;" alt="Announce Image">
                        @endif
                        <strong class="text-dark d-block text-sm" style="font-size: 0.95rem;">{{ $ann->title }}</strong>
                        <p class="text-muted small mb-2 mt-1 leading-relaxed" style="font-size: 0.85rem;">
                            {{ Str::limit(strip_tags($ann->content), 120) }}
                        </p>
                        <span class="text-muted d-block" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i>{{ $ann->published_at->format('d M Y H:i') }} WIB</span>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-megaphone display-6"></i>
                        <p class="mt-2 small mb-0">Belum ada pengumuman untuk saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: My Registered Seminars -->
    <div class="col-lg-8">
        <div class="card card-premium shadow border-0 p-4 h-100">
            <h5 class="fw-bold text-dark mb-4"><i class="bi bi-calendar2-check-fill text-primary me-2"></i>Seminar yang Saya Ikuti</h5>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Judul Seminar</th>
                            <th>Status Registrasi</th>
                            <th>Status Pembayaran</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($myRegistrations as $reg)
                            <tr>
                                <td class="fw-bold text-primary">{{ $reg->registration_code }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $reg->seminar->title }}</div>
                                    <div class="text-muted small"><i class="bi bi-calendar me-1"></i>{{ $reg->seminar->seminar_date->format('d M Y') }}</div>
                                </td>
                                <td>
                                    @if($reg->registration_status === 'pending')
                                        <span class="badge badge-custom badge-pending">PENDING</span>
                                    @elseif($reg->registration_status === 'approved')
                                        <span class="badge badge-custom badge-approved">APPROVED</span>
                                    @elseif($reg->registration_status === 'rejected')
                                        <span class="badge badge-custom badge-rejected">REJECTED</span>
                                    @else
                                        <span class="badge badge-custom badge-closed">CANCELLED</span>
                                    @endif
                                </td>
                                <td>
                                    @if($reg->payment_status === 'unpaid')
                                        <span class="badge badge-custom badge-closed">UNPAID</span>
                                    @elseif($reg->payment_status === 'waiting_verification')
                                        <span class="badge badge-custom badge-waiting">WAITING VERIFY</span>
                                    @elseif($reg->payment_status === 'paid')
                                        <span class="badge badge-custom badge-approved">PAID</span>
                                    @else
                                        <span class="badge badge-custom badge-rejected">REJECTED</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('participant.registrations.show', $reg->id) }}" class="btn btn-sm btn-premium">
                                        Detail &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-calendar-x text-muted display-4"></i>
                                    <p class="mt-3 text-muted">Anda belum mendaftar seminar apapun.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Available Seminars Section -->
<div class="row animate-fade-in-up" style="animation-delay: 0.2s;">
    <div class="col-12">
        <div class="card card-premium shadow border-0 p-4">
            <h5 class="fw-bold text-dark mb-4"><i class="bi bi-calendar-plus-fill text-primary me-2"></i>Seminar Tersedia untuk Diikuti</h5>
            
            <div class="row g-4">
                @forelse($availableSeminars as $seminar)
                    <div class="col-md-6 col-lg-4">
                        <div class="card bg-light border-0 shadow-sm rounded-3 h-100 overflow-hidden d-flex flex-column">
                            @if($seminar->poster)
                                <img src="{{ asset('storage/' . $seminar->poster) }}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="{{ $seminar->title }}">
                            @else
                                <div class="bg-gradient text-white d-flex flex-column align-items-center justify-content-center p-4 text-center" style="height: 150px; background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%) !important;">
                                    <i class="bi bi-calendar-event fs-2 mb-1 text-warning"></i>
                                    <h6 class="fw-bold mb-0">Poster Seminar</h6>
                                </div>
                            @endif
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-white text-primary fw-semibold px-2.5 py-1.5 border border-primary small" style="font-size: 0.75rem;">{{ $seminar->speaker }}</span>
                                    <span class="fw-bold {{ $seminar->price == 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $seminar->price == 0 ? 'GRATIS' : 'Rp ' . number_format($seminar->price, 0, ',', '.') }}
                                    </span>
                                </div>
                                <h6 class="fw-bold text-dark mb-2">{{ Str::limit($seminar->title, 40) }}</h6>
                                <p class="text-muted small mb-4 line-clamp-3">
                                    {{ Str::limit($seminar->description, 90) }}
                                </p>
                                <div class="mt-auto border-top pt-3 d-flex justify-content-between align-items-center small text-muted">
                                    <div>
                                        <i class="bi bi-calendar-check me-1"></i> {{ $seminar->seminar_date->format('d M Y') }}
                                    </div>
                                    <a href="{{ route('public.seminar.detail', $seminar->slug) }}" class="btn btn-sm btn-outline-primary fw-semibold px-3 py-1.5">
                                        Daftar & Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4 text-muted">
                        <i class="bi bi-check-circle display-6"></i>
                        <p class="mt-2 small mb-0">Tidak ada seminar baru yang tersedia untuk didaftar saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

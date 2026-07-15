@extends('layouts.app')

@section('title', $seminar->title)

@section('content')
<div class="container py-5 animate-fade-in-up">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('public.seminars') }}">Seminar</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($seminar->title, 30) }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Poster & Deskripsi -->
        <div class="col-lg-8">
            <div class="card card-premium shadow border-0 overflow-hidden mb-4">
                @if($seminar->poster)
                    <img src="{{ asset('storage/' . $seminar->poster) }}" class="img-fluid w-100" style="max-height: 450px; object-fit: cover;" alt="{{ $seminar->title }}">
                @else
                    <div class="bg-gradient text-white d-flex flex-column align-items-center justify-content-center px-4 py-5 text-center" style="min-height: 350px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;">
                        <i class="bi bi-calendar-event display-1 mb-3 text-warning"></i>
                        <span class="fw-bold text-uppercase tracking-wider">Seminar LSP</span>
                        <h2 class="fw-bold mb-0 mt-2">{{ $seminar->title }}</h2>
                    </div>
                @endif
                <div class="card-body p-5">
                    <h2 class="fw-bold text-dark mb-4">{{ $seminar->title }}</h2>
                    
                    <h5 class="fw-bold text-secondary mb-3"><i class="bi bi-file-text-fill text-primary me-2"></i>Deskripsi Kegiatan</h5>
                    <p class="text-muted leading-relaxed mb-0" style="font-size: 1.05rem; line-height: 1.8;">
                        {!! nl2br(e($seminar->description)) !!}
                    </p>
                </div>
            </div>
        </div>

        <!-- Info & Pendaftaran -->
        <div class="col-lg-4">
            <div class="card card-premium shadow border-0 mb-4 p-4 sticky-top" style="top: 100px;">
                <h5 class="fw-bold mb-4 border-bottom pb-2">Informasi Detail</h5>

                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-light p-2.5 rounded-3 text-primary">
                        <i class="bi bi-person-workspace fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Pembicara</span>
                        <strong class="text-dark">{{ $seminar->speaker }}</strong>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-light p-2.5 rounded-3 text-primary">
                        <i class="bi bi-calendar-check fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Tanggal Pelaksanaan</span>
                        <strong class="text-dark">{{ $seminar->seminar_date->format('d F Y') }}</strong>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-light p-2.5 rounded-3 text-primary">
                        <i class="bi bi-clock fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Waktu Kegiatan</span>
                        <strong class="text-dark">{{ date('H:i', strtotime($seminar->start_time)) }} - {{ date('H:i', strtotime($seminar->end_time)) }} WIB</strong>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-light p-2.5 rounded-3 text-primary">
                        <i class="bi bi-geo-alt fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Lokasi</span>
                        <strong class="text-dark">{{ $seminar->location }}</strong>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-light p-2.5 rounded-3 text-primary">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Kuota Tersedia</span>
                        <strong class="text-dark">
                            {{ $seminar->remainingSlots() }} / {{ $seminar->quota }} Peserta
                            @if(!$seminar->hasAvailableQuota())
                                <span class="badge bg-danger ms-1">Penuh</span>
                            @endif
                        </strong>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-light p-2.5 rounded-3 text-primary">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Batas Pendaftaran</span>
                        <strong class="text-danger">{{ $seminar->registration_deadline->format('d F Y') }}</strong>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-light p-2.5 rounded-3 text-primary">
                        <i class="bi bi-tags fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Biaya Pendaftaran</span>
                        <h4 class="fw-bold mb-0 mt-0 {{ $seminar->price == 0 ? 'text-success' : 'text-danger' }}">
                            {{ $seminar->price == 0 ? 'GRATIS' : 'Rp ' . number_format($seminar->price, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>

                <!-- Formulir/Tombol Pendaftaran -->
                <div class="border-top pt-4">
                    @guest
                        <div class="alert alert-warning small mb-3">
                            <i class="bi bi-info-circle me-1"></i> Anda harus masuk atau mendaftar terlebih dahulu untuk mendaftar seminar ini.
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-premium w-100 py-2.5">Masuk untuk Mendaftar</a>
                    @else
                        @if(auth()->user()->role === 'admin')
                            <button class="btn btn-secondary w-100 py-2.5" disabled>Aksi Terbatas Admin</button>
                        @else
                            @php
                                $registration = \App\Models\SeminarRegistration::where('user_id', auth()->id())
                                    ->where('seminar_id', $seminar->id)
                                    ->first();
                            @endphp

                            @if($registration)
                                <div class="alert alert-success text-center small mb-3">
                                    <i class="bi bi-check-circle me-1"></i> Anda sudah terdaftar dalam seminar ini.
                                </div>
                                <a href="{{ route('participant.registrations.show', $registration->id) }}" class="btn btn-success w-100 py-2.5">Lihat Status Pendaftaran</a>
                            @elseif(!$seminar->hasAvailableQuota())
                                <button class="btn btn-danger w-100 py-2.5" disabled><i class="bi bi-exclamation-triangle me-1"></i> Kuota Penuh</button>
                            @elseif(now()->toDateString() > $seminar->registration_deadline->toDateString())
                                <button class="btn btn-secondary w-100 py-2.5" disabled><i class="bi bi-clock-history me-1"></i> Pendaftaran Ditutup</button>
                            @else
                                <form id="registerForm" action="{{ route('participant.seminars.register', $seminar->id) }}" method="POST">
                                    @csrf
                                    <button type="button" onclick="confirmRegister()" class="btn btn-premium w-100 py-2.5">Daftar Seminar Ini</button>
                                </form>
                            @endif
                        @endif
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function confirmRegister() {
        Swal.fire({
            title: 'Konfirmasi Pendaftaran',
            text: 'Apakah Anda yakin ingin mendaftar untuk seminar "{{ $seminar->title }}"?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Daftar!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('registerForm').submit();
            }
        });
    }
</script>
@endsection

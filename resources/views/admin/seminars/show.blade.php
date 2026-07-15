@extends('layouts.admin')

@section('title', 'Detail Seminar: ' . $seminar->title)
@section('page_title', 'Detail Seminar')

@section('content')
<div class="mb-3 animate-fade-in-up">
    <a href="{{ route('admin.seminars.index') }}" class="text-decoration-none"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Seminar</a>
</div>

<div class="row g-4 animate-fade-in-up">
    <!-- Seminar Stats and Details -->
    <div class="col-lg-4">
        <div class="card card-premium shadow border-0 overflow-hidden mb-4">
            @if($seminar->poster)
                <img src="{{ asset('storage/' . $seminar->poster) }}" class="img-fluid w-100" style="max-height: 250px; object-fit: cover;" alt="Poster">
            @else
                <div class="bg-gradient text-white d-flex flex-column align-items-center justify-content-center p-5 text-center" style="min-height: 180px; background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%) !important;">
                    <i class="bi bi-calendar-event fs-1 mb-2 text-warning"></i>
                    <h5 class="fw-bold mb-0">Poster Seminar</h5>
                </div>
            @endif
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold text-dark mb-0">{{ $seminar->title }}</h4>
                </div>
                <div class="mb-3">
                    @if($seminar->status === 'draft')
                        <span class="badge badge-custom badge-draft"><i class="bi bi-file-earmark-lock me-1"></i>DRAFT</span>
                    @elseif($seminar->status === 'published')
                        <span class="badge badge-custom badge-approved"><i class="bi bi-globe me-1"></i>PUBLISHED</span>
                    @elseif($seminar->status === 'closed')
                        <span class="badge badge-custom badge-closed"><i class="bi bi-lock me-1"></i>CLOSED</span>
                    @else
                        <span class="badge badge-custom badge-cancelled"><i class="bi bi-check2-all me-1"></i>COMPLETED</span>
                    @endif
                </div>

                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2 bg-transparent border-light">
                        <span class="text-muted">Pembicara</span>
                        <strong class="text-dark">{{ $seminar->speaker }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2 bg-transparent border-light">
                        <span class="text-muted">Tanggal</span>
                        <strong class="text-dark">{{ $seminar->seminar_date->format('d M Y') }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2 bg-transparent border-light">
                        <span class="text-muted">Waktu</span>
                        <strong class="text-dark">{{ date('H:i', strtotime($seminar->start_time)) }} - {{ date('H:i', strtotime($seminar->end_time)) }} WIB</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2 bg-transparent border-light">
                        <span class="text-muted">Lokasi</span>
                        <strong class="text-dark">{{ $seminar->location }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2 bg-transparent border-light">
                        <span class="text-muted">Biaya</span>
                        <strong class="{{ $seminar->price == 0 ? 'text-success' : 'text-danger' }}">
                            {{ $seminar->price == 0 ? 'Gratis' : 'Rp ' . number_format($seminar->price, 0, ',', '.') }}
                        </strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2 bg-transparent border-light">
                        <span class="text-muted">Kuota Peserta</span>
                        <strong class="text-dark">{{ $seminar->quota }} Peserta</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2 bg-transparent border-light">
                        <span class="text-muted">Deadline Daftar</span>
                        <strong class="text-danger">{{ $seminar->registration_deadline->format('d M Y') }}</strong>
                    </li>
                </ul>

                <div class="mt-4">
                    <a href="{{ route('admin.seminars.edit', $seminar->id) }}" class="btn btn-outline-warning w-100 py-2 btn-sm fw-semibold mb-2"><i class="bi bi-pencil-square me-1"></i> Edit Kegiatan</a>
                </div>
            </div>
        </div>

        <!-- Quota Statistics Card -->
        <div class="card card-premium shadow border-0 p-4">
            <h6 class="fw-bold mb-3 text-secondary">Statistik Pendaftaran</h6>
            @php
                $approvedCount = $seminar->approvedRegistrationsCount();
                $totalCount = $seminar->registrations()->count();
                $percent = $seminar->quota > 0 ? ($approvedCount / $seminar->quota) * 100 : 0;
            @endphp
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small text-muted">Kuota Terpakai (Approved)</span>
                <span class="fw-bold">{{ $approvedCount }} / {{ $seminar->quota }}</span>
            </div>
            <div class="progress mb-4 bg-light" style="height: 10px; border-radius: 5px;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percent }}%; border-radius: 5px;" aria-valuenow="{{ $approvedCount }}" aria-valuemin="0" aria-valuemax="{{ $seminar->quota }}"></div>
            </div>

            <div class="row text-center g-3">
                <div class="col-6">
                    <div class="p-3 bg-light rounded-3">
                        <span class="small text-muted d-block">Total Masuk</span>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalCount }}</h4>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 bg-light rounded-3">
                        <span class="small text-muted d-block">Sisa Kuota</span>
                        <h4 class="fw-bold mb-0 text-primary">{{ $seminar->remainingSlots() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Registered Participants List -->
    <div class="col-lg-8">
        <div class="card card-premium shadow border-0 p-4 h-100">
            <h5 class="fw-bold mb-4 text-dark border-bottom pb-2"><i class="bi bi-people-fill text-primary me-2"></i>Daftar Peserta Terdaftar</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Daftar</th>
                            <th>Nama Peserta</th>
                            <th>Institusi</th>
                            <th>Status Bayar</th>
                            <th>Status Registrasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $reg)
                            <tr>
                                <td class="fw-bold text-primary">{{ $reg->registration_code }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $reg->user->name }}</div>
                                    <div class="text-muted small">{{ $reg->user->email }}</div>
                                </td>
                                <td>{{ $reg->user->institution ?? '-' }}</td>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-people text-muted display-4"></i>
                                    <p class="mt-3 text-muted">Belum ada peserta yang mendaftar untuk seminar ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $registrations->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

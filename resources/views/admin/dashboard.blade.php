@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard Ringkasan Statistik')

@section('content')
<!-- Stats Cards Row -->
<div class="row g-4 mb-4 animate-fade-in-up">
    <!-- Total Participants -->
    <div class="col-md-4 col-xl-2.5">
        <div class="card card-premium p-3 border-0 bg-white shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Total Peserta</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalParticipants }}</h3>
                </div>
                <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
                    <i class="bi bi-people-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Verification Users -->
    <div class="col-md-4 col-xl-2.5">
        <div class="card card-premium p-3 border-0 bg-white shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Pending Verifikasi</span>
                    <h3 class="fw-bold mb-0 text-warning">{{ $pendingParticipants }}</h3>
                </div>
                <div class="bg-warning bg-opacity-10 p-3 rounded-3 text-warning">
                    <i class="bi bi-person-exclamation fs-4"></i>
                </div>
            </div>
            <div class="mt-2 text-xs text-muted">
                <a href="{{ route('admin.accounts.index', ['status' => 'pending']) }}" class="text-decoration-none small text-warning fw-semibold">Proses Sekarang &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Total Seminars -->
    <div class="col-md-4 col-xl-2.5">
        <div class="card card-premium p-3 border-0 bg-white shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Total Seminar</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalSeminars }}</h3>
                </div>
                <div class="bg-info bg-opacity-10 p-3 rounded-3 text-info">
                    <i class="bi bi-calendar-event-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Seminars -->
    <div class="col-md-4 col-xl-2.5">
        <div class="card card-premium p-3 border-0 bg-white shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Seminar Aktif</span>
                    <h3 class="fw-bold mb-0 text-success">{{ $activeSeminars }}</h3>
                </div>
                <div class="bg-success bg-opacity-10 p-3 rounded-3 text-success">
                    <i class="bi bi-patch-check-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Registrations -->
    <div class="col-md-4 col-xl-2.5">
        <div class="card card-premium p-3 border-0 bg-white shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Total Pendaftaran</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalRegistrations }}</h3>
                </div>
                <div class="bg-secondary bg-opacity-10 p-3 rounded-3 text-secondary">
                    <i class="bi bi-journal-text-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Payment Verifications -->
    <div class="col-md-4 col-xl-2.5">
        <div class="card card-premium p-3 border-0 bg-white shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Verifikasi Bayar</span>
                    <h3 class="fw-bold mb-0 text-danger">{{ $pendingPayments }}</h3>
                </div>
                <div class="bg-danger bg-opacity-10 p-3 rounded-3 text-danger">
                    <i class="bi bi-credit-card-2-front-fill fs-4"></i>
                </div>
            </div>
            <div class="mt-2 text-xs text-muted">
                <a href="{{ route('admin.registrations.index', ['payment_status' => 'waiting_verification']) }}" class="text-decoration-none small text-danger fw-semibold">Proses Sekarang &rarr;</a>
            </div>
        </div>
    </div>
</div>

<!-- Chart & Lists Row -->
<div class="row g-4 mb-4 animate-fade-in-up" style="animation-delay: 0.15s;">
    <!-- Chart Column -->
    <div class="col-xl-8">
        <div class="card card-premium shadow border-0 p-4 h-100">
            <h5 class="fw-bold text-dark mb-4"><i class="bi bi-bar-chart-fill text-primary me-2"></i>Tren Pendaftaran (10 Hari Terakhir)</h5>
            <div style="position: relative; height: 320px; width: 100%;">
                @if(count($chartLabels) > 0)
                    <canvas id="registrationsTrendChart"></canvas>
                @else
                    <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                        <i class="bi bi-graph-up display-4"></i>
                        <p class="mt-2">Belum ada data pendaftaran dalam 10 hari terakhir untuk digambarkan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Upcoming Seminars -->
    <div class="col-xl-4">
        <div class="card card-premium shadow border-0 p-4 h-100">
            <h5 class="fw-bold text-dark mb-4"><i class="bi bi-calendar2-week-fill text-primary me-2"></i>Seminar Mendatang</h5>
            <div class="d-flex flex-column gap-3">
                @forelse($upcomingSeminars as $sem)
                    <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="text-dark d-block text-sm" style="font-size: 0.95rem;">{{ Str::limit($sem->title, 32) }}</strong>
                            <span class="text-muted small d-block"><i class="bi bi-calendar-check me-1"></i>{{ $sem->seminar_date->format('d M Y') }}</span>
                        </div>
                        <div>
                            <span class="badge badge-custom badge-approved">{{ $sem->remainingSlots() }} Slot</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-calendar-x display-6"></i>
                        <p class="mt-2 small mb-0">Tidak ada seminar mendatang dalam waktu dekat.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Recent Registrations Table Row -->
<div class="row animate-fade-in-up" style="animation-delay: 0.25s;">
    <div class="col-12">
        <div class="card card-premium shadow border-0 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history text-primary me-2"></i>Pendaftaran Terbaru</h5>
                <a href="{{ route('admin.registrations.index') }}" class="btn btn-sm btn-outline-primary fw-semibold">Lihat Semua</a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Nama Peserta</th>
                            <th>Seminar</th>
                            <th>Tanggal Daftar</th>
                            <th>Status Bayar</th>
                            <th>Status Registrasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRegistrations as $reg)
                            <tr>
                                <td class="fw-bold text-primary">{{ $reg->registration_code }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $reg->user->name }}</div>
                                    <div class="text-muted small">{{ $reg->user->email }}</div>
                                </td>
                                <td>{{ Str::limit($reg->seminar->title, 40) }}</td>
                                <td>{{ $reg->created_at->format('d M Y H:i') }} WIB</td>
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
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Belum ada data pendaftaran masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@if(count($chartLabels) > 0)
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('registrationsTrendChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Jumlah Pendaftaran',
                    data: {!! json_encode($chartValues) !!},
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#4f46e5',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection

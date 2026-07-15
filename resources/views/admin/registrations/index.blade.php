@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran & Pendaftaran')
@section('page_title', 'Verifikasi Pembayaran & Pendaftaran')

@section('content')
<div class="row">
    <div class="col-12 animate-fade-in-up">
        <!-- Filters & Search Card -->
        <div class="card card-premium shadow border-0 mb-4">
            <div class="card-body p-4">
                <form action="{{ route('admin.registrations.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted">Status Pembayaran</label>
                        <select name="payment_status" class="form-select bg-light">
                            <option value="">Semua Status</option>
                            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="waiting_verification" {{ request('payment_status') == 'waiting_verification' ? 'selected' : '' }}>Waiting Verification</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="rejected" {{ request('payment_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted">Status Pendaftaran</label>
                        <select name="registration_status" class="form-select bg-light">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('registration_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('registration_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('registration_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="cancelled" {{ request('registration_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted">Pencarian</label>
                        <div class="input-group">
                            <input type="text" name="search" class="form-control bg-light" placeholder="Nama peserta atau kode pendaftaran..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <a href="{{ route('admin.registrations.index') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card card-premium shadow border-0 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4">Kode</th>
                                <th>Peserta</th>
                                <th>Seminar & Biaya</th>
                                <th>Bukti Bayar</th>
                                <th>Status Pendaftaran</th>
                                <th>Status Pembayaran</th>
                                <th class="pe-4 text-end" style="width: 250px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registrations as $reg)
                                <tr id="row-reg-{{ $reg->id }}">
                                    <td class="ps-4 fw-bold text-primary">{{ $reg->registration_code }}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $reg->user->name }}</div>
                                        <div class="text-muted small">{{ $reg->user->email }} | {{ $reg->user->phone }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $reg->seminar->title }}</div>
                                        <div class="text-muted small">
                                            Biaya: <strong class="{{ $reg->seminar->price == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $reg->seminar->price == 0 ? 'GRATIS' : 'Rp ' . number_format($reg->seminar->price, 0, ',', '.') }}
                                            </strong>
                                        </div>
                                    </td>
                                    <td>
                                        @if($reg->payment_proof)
                                            @php
                                                $ext = pathinfo($reg->payment_proof, PATHINFO_EXTENSION);
                                                $isPdf = strtolower($ext) === 'pdf';
                                            @endphp
                                            <button type="button" 
                                                    onclick="viewPaymentProof('{{ asset('storage/' . $reg->payment_proof) }}', {{ $isPdf ? 'true' : 'false' }})" 
                                                    class="btn btn-sm btn-outline-primary fw-medium">
                                                <i class="bi bi-file-earmark-image"></i> Lihat Bukti
                                            </button>
                                            <div class="text-muted small mt-1" style="font-size: 0.75rem;">
                                                Upload: {{ $reg->payment_date ? $reg->payment_date->format('d/m/y H:i') : '-' }}
                                            </div>
                                        @else
                                            <span class="text-muted small">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td class="reg-status-cell">
                                        @if($reg->registration_status === 'pending')
                                            <span class="badge badge-custom badge-pending"><i class="bi bi-clock-history me-1"></i>PENDING</span>
                                        @elseif($reg->registration_status === 'approved')
                                            <span class="badge badge-custom badge-approved"><i class="bi bi-patch-check-fill me-1"></i>APPROVED</span>
                                        @elseif($reg->registration_status === 'rejected')
                                            <span class="badge badge-custom badge-rejected"><i class="bi bi-x-circle-fill me-1"></i>REJECTED</span>
                                        @else
                                            <span class="badge badge-custom badge-closed"><i class="bi bi-x-circle me-1"></i>CANCELLED</span>
                                        @endif
                                    </td>
                                    <td class="pay-status-cell">
                                        @if($reg->payment_status === 'unpaid')
                                            <span class="badge badge-custom badge-closed"><i class="bi bi-wallet2 me-1"></i>UNPAID</span>
                                        @elseif($reg->payment_status === 'waiting_verification')
                                            <span class="badge badge-custom badge-waiting"><i class="bi bi-hourglass-split me-1"></i>WAITING VERIFY</span>
                                        @elseif($reg->payment_status === 'paid')
                                            <span class="badge badge-custom badge-approved"><i class="bi bi-check-circle-fill me-1"></i>PAID</span>
                                        @else
                                            <span class="badge badge-custom badge-rejected"><i class="bi bi-exclamation-octagon-fill me-1"></i>REJECTED</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end action-cell">
                                        <div class="d-flex justify-content-end gap-2">
                                            @if($reg->payment_status === 'waiting_verification' || ($reg->seminar->price == 0 && $reg->registration_status === 'pending'))
                                                <button type="button" 
                                                        onclick="verifyRegistration({{ $reg->id }}, 'approve', '{{ $reg->registration_code }}')" 
                                                        class="btn btn-sm btn-success btn-approve-reg">
                                                    <i class="bi bi-check-lg me-1"></i>Setujui
                                                </button>
                                                
                                                <button type="button" 
                                                        onclick="verifyRegistration({{ $reg->id }}, 'reject', '{{ $reg->registration_code }}')" 
                                                        class="btn btn-sm btn-danger btn-reject-reg">
                                                    <i class="bi bi-x-lg me-1"></i>Tolak
                                                </button>
                                            @else
                                                <span class="text-muted small">Sudah Diproses</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="bi bi-credit-card text-muted display-4"></i>
                                        <p class="mt-3 text-muted">Tidak ada data pendaftaran ditemukan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $registrations->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Bukti Pembayaran -->
<div class="modal fade" id="paymentProofModal" tabindex="-1" aria-labelledby="paymentProofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow border-0 rounded-3">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="paymentProofModalLabel"><i class="bi bi-image me-2"></i>Bukti Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4 bg-light">
                <img id="modalPaymentProofImg" class="img-fluid rounded shadow-sm" src="" style="display:none; max-height: 500px;">
                <iframe id="modalPaymentProofPdf" src="" width="100%" height="500px" style="display:none; border:none;"></iframe>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // Inisialisasi Modal Bootstrap
    let proofModal;
    document.addEventListener('DOMContentLoaded', function() {
        proofModal = new bootstrap.Modal(document.getElementById('paymentProofModal'));
    });

    // Menampilkan bukti pembayaran di modal
    function viewPaymentProof(fileUrl, isPdf) {
        const imgEl = document.getElementById('modalPaymentProofImg');
        const pdfEl = document.getElementById('modalPaymentProofPdf');

        if (isPdf) {
            imgEl.style.display = 'none';
            pdfEl.style.display = 'block';
            pdfEl.src = fileUrl;
        } else {
            pdfEl.style.display = 'none';
            imgEl.style.display = 'block';
            imgEl.src = fileUrl;
        }

        proofModal.show();
    }

    // Fungsi verifikasi pendaftaran & pembayaran via AJAX
    function verifyRegistration(regId, action, regCode) {
        if (action === 'approve') {
            Swal.fire({
                title: 'Setujui Pendaftaran?',
                text: `Apakah Anda yakin ingin menyetujui pendaftaran ${regCode} ini?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#15803d',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    processVerification(regId, action, null);
                }
            });
        } else {
            // Tindakan Penolakan wajib memberikan catatan/alasan
            Swal.fire({
                title: 'Tolak Pembayaran / Pendaftaran?',
                text: 'Harap masukkan alasan penolakan sebagai catatan untuk peserta:',
                icon: 'warning',
                input: 'textarea',
                inputPlaceholder: 'Contoh: Bukti transfer tidak terbaca atau nominal tidak sesuai...',
                inputAttributes: {
                    'aria-label': 'Masukkan alasan penolakan bukti pembayaran di sini'
                },
                showCancelButton: true,
                confirmButtonColor: '#b91c1c',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Alasan penolakan wajib diisi!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    processVerification(regId, action, result.value);
                }
            });
        }
    }

    function processVerification(regId, action, note) {
        // Tampilkan loading spinner
        Swal.fire({
            title: 'Memproses...',
            text: 'Sedang menyimpan hasil verifikasi...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // AJAX POST Request
        fetch(`/admin/registrations/${regId}/verify`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                action: action,
                admin_note: note
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Verifikasi Berhasil',
                    text: data.message,
                    confirmButtonColor: '#4f46e5'
                });

                // Update row di tabel secara dinamis tanpa reload halaman
                const row = document.getElementById(`row-reg-${regId}`);
                const regStatusCell = row.querySelector('.reg-status-cell');
                const payStatusCell = row.querySelector('.pay-status-cell');
                const actionCell = row.querySelector('.action-cell');

                if (action === 'approve') {
                    regStatusCell.innerHTML = `<span class="badge badge-custom badge-approved"><i class="bi bi-patch-check-fill me-1"></i>APPROVED</span>`;
                    payStatusCell.innerHTML = `<span class="badge badge-custom badge-approved"><i class="bi bi-check-circle-fill me-1"></i>PAID</span>`;
                } else {
                    regStatusCell.innerHTML = `<span class="badge badge-custom badge-rejected"><i class="bi bi-x-circle-fill me-1"></i>REJECTED</span>`;
                    payStatusCell.innerHTML = `<span class="badge badge-custom badge-rejected"><i class="bi bi-exclamation-octagon-fill me-1"></i>REJECTED</span>`;
                }

                actionCell.innerHTML = `<span class="text-muted small">Sudah Diproses</span>`;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message,
                    confirmButtonColor: '#4f46e5'
                });
            }
        })
        .catch(error => {
            console.error('Verify error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan Sistem',
                text: error.message || 'Terjadi kesalahan sistem saat memproses verifikasi.',
                confirmButtonColor: '#4f46e5'
            });
        });
    }
</script>
@endsection

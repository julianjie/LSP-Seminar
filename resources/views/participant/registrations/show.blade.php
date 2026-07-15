@extends('layouts.participant')

@section('title', 'Detail Pendaftaran: ' . $registration->registration_code)

@section('content')
<div class="mb-3 animate-fade-in-up">
    <a href="{{ route('participant.dashboard') }}" class="text-decoration-none"><i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard</a>
</div>

<div class="row g-4 animate-fade-in-up">
    <!-- Pendaftaran & Seminar Info -->
    <div class="col-lg-7">
        <!-- Detail Pendaftaran Card -->
        <div class="card card-premium shadow border-0 p-4 mb-4">
            <h5 class="fw-bold mb-4 text-dark border-bottom pb-2"><i class="bi bi-info-circle-fill text-primary me-2"></i>Status Pendaftaran</h5>
            
            <div class="row mb-3">
                <div class="col-sm-5 text-muted">Kode Registrasi:</div>
                <div class="col-sm-7 fw-bold text-primary">{{ $registration->registration_code }}</div>
            </div>
            
            <div class="row mb-3">
                <div class="col-sm-5 text-muted">Tanggal Pendaftaran:</div>
                <div class="col-sm-7 fw-medium text-dark">{{ $registration->created_at->format('d F Y H:i') }} WIB</div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-5 text-muted">Status Registrasi:</div>
                <div class="col-sm-7">
                    @if($registration->registration_status === 'pending')
                        <span class="badge badge-custom badge-pending"><i class="bi bi-clock-history me-1"></i>PENDING</span>
                    @elseif($registration->registration_status === 'approved')
                        <span class="badge badge-custom badge-approved"><i class="bi bi-patch-check-fill me-1"></i>APPROVED</span>
                    @elseif($registration->registration_status === 'rejected')
                        <span class="badge badge-custom badge-rejected"><i class="bi bi-x-circle-fill me-1"></i>REJECTED</span>
                    @else
                        <span class="badge badge-custom badge-closed"><i class="bi bi-x-circle me-1"></i>CANCELLED</span>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-5 text-muted">Status Pembayaran:</div>
                <div class="col-sm-7">
                    @if($registration->payment_status === 'unpaid')
                        <span class="badge badge-custom badge-closed"><i class="bi bi-wallet2 me-1"></i>UNPAID (Belum Bayar)</span>
                    @elseif($registration->payment_status === 'waiting_verification')
                        <span class="badge badge-custom badge-waiting"><i class="bi bi-hourglass-split me-1"></i>WAITING VERIFICATION</span>
                    @elseif($registration->payment_status === 'paid')
                        <span class="badge badge-custom badge-approved"><i class="bi bi-check-circle-fill me-1"></i>PAID (Lunas)</span>
                    @else
                        <span class="badge badge-custom badge-rejected"><i class="bi bi-exclamation-octagon-fill me-1"></i>REJECTED (Ditolak)</span>
                    @endif
                </div>
            </div>

            <!-- Catatan Admin (Wajib ditampilkan jika ditolak) -->
            @if($registration->admin_note)
                <div class="alert {{ $registration->payment_status === 'rejected' ? 'alert-danger' : 'alert-info' }} mt-3 mb-0">
                    <h6 class="fw-bold mb-1"><i class="bi bi-chat-left-text-fill me-1"></i> Catatan dari Administrator:</h6>
                    <p class="mb-0 small leading-relaxed">{{ $registration->admin_note }}</p>
                </div>
            @endif
        </div>

        <!-- Detail Seminar Card -->
        <div class="card card-premium shadow border-0 p-4">
            <h5 class="fw-bold mb-4 text-dark border-bottom pb-2"><i class="bi bi-calendar-event-fill text-primary me-2"></i>Informasi Seminar</h5>
            
            <div class="row mb-3">
                <div class="col-sm-4 text-muted">Judul Seminar:</div>
                <div class="col-sm-8 fw-bold text-dark">{{ $registration->seminar->title }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-4 text-muted">Speaker / Pembicara:</div>
                <div class="col-sm-8 fw-medium text-dark">{{ $registration->seminar->speaker }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-4 text-muted">Pelaksanaan:</div>
                <div class="col-sm-8 text-dark">
                    <i class="bi bi-calendar-check me-1 text-primary"></i> {{ $registration->seminar->seminar_date->format('d F Y') }} <br>
                    <i class="bi bi-clock me-1 text-primary"></i> {{ date('H:i', strtotime($registration->seminar->start_time)) }} - {{ date('H:i', strtotime($registration->seminar->end_time)) }} WIB
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-4 text-muted">Lokasi:</div>
                <div class="col-sm-8 text-dark"><i class="bi bi-geo-alt-fill me-1 text-primary"></i> {{ $registration->seminar->location }}</div>
            </div>

            <div class="row mb-0">
                <div class="col-sm-4 text-muted">Biaya / Harga:</div>
                <div class="col-sm-8 fw-bold {{ $registration->seminar->price == 0 ? 'text-success' : 'text-danger' }}">
                    {{ $registration->seminar->price == 0 ? 'GRATIS' : 'Rp ' . number_format($registration->seminar->price, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Bukti Pembayaran -->
    <div class="col-lg-5">
        <div class="card card-premium shadow border-0 p-4 h-100">
            <h5 class="fw-bold mb-4 text-dark border-bottom pb-2"><i class="bi bi-credit-card-fill text-primary me-2"></i>Bukti Pembayaran</h5>

            @if($registration->seminar->price == 0)
                <div class="text-center py-5">
                    <div class="bg-success bg-opacity-10 text-success p-4 rounded-circle d-inline-block mb-3">
                        <i class="bi bi-check2-circle display-4"></i>
                    </div>
                    <h5 class="fw-bold">Seminar Gratis</h5>
                    <p class="text-muted small mb-0 px-3">Seminar ini gratis. Anda tidak perlu mengunggah bukti pembayaran apapun. Pendaftaran Anda terverifikasi secara otomatis.</p>
                </div>
            @else
                <!-- Detail Bukti yang Sudah Diunggah -->
                @if($registration->payment_proof)
                    <div class="mb-4 text-center">
                        <span class="text-muted d-block small mb-2">Bukti Pembayaran Terunggah:</span>
                        @php
                            $ext = pathinfo($registration->payment_proof, PATHINFO_EXTENSION);
                            $isPdf = strtolower($ext) === 'pdf';
                        @endphp
                        
                        @if($isPdf)
                            <div class="p-3 bg-light rounded border mb-3 d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-file-earmark-pdf-fill text-danger fs-3"></i>
                                <span class="fw-semibold text-dark">Bukti_Bayar.pdf</span>
                            </div>
                            <a href="{{ asset('storage/' . $registration->payment_proof) }}" class="btn btn-sm btn-outline-primary px-3" target="_blank">
                                <i class="bi bi-eye"></i> Buka PDF di Tab Baru
                            </a>
                        @else
                            <img src="{{ asset('storage/' . $registration->payment_proof) }}" class="img-thumbnail rounded shadow-sm mb-3" style="max-height: 250px; object-fit: cover;" alt="Bukti Pembayaran">
                            <br>
                            <a href="{{ asset('storage/' . $registration->payment_proof) }}" class="btn btn-sm btn-outline-primary px-3" target="_blank">
                                <i class="bi bi-zoom-in"></i> Perbesar Gambar
                            </a>
                        @endif
                    </div>
                @endif

                <!-- Form Upload / Upload Ulang -->
                @if($registration->payment_status === 'unpaid' || $registration->payment_status === 'rejected')
                    <div class="border-top pt-4">
                        <h6 class="fw-bold text-dark mb-3">
                            {{ $registration->payment_proof ? 'Unggah Ulang Bukti Pembayaran' : 'Unggah Bukti Pembayaran Baru' }}
                        </h6>
                        
                        <!-- Petunjuk Transfer -->
                        @if(!$registration->payment_proof)
                            <div class="p-3 bg-light rounded-3 mb-3 border">
                                <span class="text-muted small d-block mb-1">Silakan transfer sebesar:</span>
                                <h4 class="fw-bold text-danger mb-2">Rp {{ number_format($registration->seminar->price, 0, ',', '.') }}</h4>
                                <span class="text-muted small d-block mb-1">Ke Rekening Bank Mandiri:</span>
                                <strong class="text-dark">123-00-9876543-1 (a.n LSP UMDP)</strong>
                            </div>
                        @endif

                        <form action="{{ route('participant.registrations.upload_payment', $registration->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="payment_proof" class="form-label small fw-semibold">Pilih Berkas Bukti Transfer</label>
                                <input type="file" name="payment_proof" id="payment_proof" class="form-control bg-light @error('payment_proof') is-invalid @enderror" accept="image/jpeg,image/jpg,image/png,application/pdf" required>
                                <small class="text-muted text-xs d-block mt-1">Format yang diizinkan: JPG, JPEG, PNG, PDF. Ukuran Maks: 2MB.</small>
                                @error('payment_proof')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-premium w-100 py-2.5">
                                <i class="bi bi-cloud-arrow-up-fill me-1"></i> {{ $registration->payment_proof ? 'Kirim Ulang Bukti Bayar' : 'Kirim Bukti Pembayaran' }}
                            </button>
                        </form>
                    </div>
                @elseif($registration->payment_status === 'waiting_verification')
                    <div class="alert alert-info text-center small mb-0 mt-3">
                        <i class="bi bi-info-circle-fill me-1 fs-5 d-block mb-1 text-primary"></i>
                        Bukti pembayaran Anda sudah diunggah dan sedang dalam antrean verifikasi oleh administrator. Anda tidak dapat melakukan perubahan saat ini.
                    </div>
                @else
                    <div class="alert alert-success text-center small mb-0 mt-3">
                        <i class="bi bi-check-circle-fill me-1 fs-5 d-block mb-1 text-success"></i>
                        Pembayaran telah dikonfirmasi lunas oleh administrator. Pendaftaran seminar Anda telah aktif sepenuhnya.
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection

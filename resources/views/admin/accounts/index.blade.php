@extends('layouts.admin')

@section('title', 'Verifikasi Akun Peserta')
@section('page_title', 'Verifikasi Akun Peserta')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Filter Card -->
        <div class="card card-premium shadow border-0 mb-4">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3 p-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-semibold text-secondary"><i class="bi bi-funnel-fill me-1"></i>Filter Status:</span>
                    <a href="{{ route('admin.accounts.index') }}" class="btn btn-sm btn-outline-secondary {{ !request('status') ? 'active' : '' }}">Semua</a>
                    <a href="{{ route('admin.accounts.index', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-warning {{ request('status') == 'pending' ? 'active' : '' }}">Pending</a>
                    <a href="{{ route('admin.accounts.index', ['status' => 'approved']) }}" class="btn btn-sm btn-outline-success {{ request('status') == 'approved' ? 'active' : '' }}">Approved</a>
                    <a href="{{ route('admin.accounts.index', ['status' => 'rejected']) }}" class="btn btn-sm btn-outline-danger {{ request('status') == 'rejected' ? 'active' : '' }}">Rejected</a>
                </div>
                <div class="text-muted small">
                    Menampilkan total <strong class="text-dark">{{ $participants->total() }}</strong> akun peserta.
                </div>
            </div>
        </div>

        <!-- Accounts Table Card -->
        <div class="card card-premium shadow border-0 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="accountsTable">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4">Nama</th>
                                <th>Email</th>
                                <th>No. Telepon</th>
                                <th>Institusi</th>
                                <th>Tgl Daftar</th>
                                <th>Status</th>
                                <th class="pe-4 text-end" style="width: 250px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($participants as $participant)
                                <tr id="row-user-{{ $participant->id }}">
                                    <td class="ps-4 fw-semibold text-dark">{{ $participant->name }}</td>
                                    <td>{{ $participant->email }}</td>
                                    <td>{{ $participant->phone ?? '-' }}</td>
                                    <td>{{ $participant->institution ?? '-' }}</td>
                                    <td>{{ $participant->created_at->format('d M Y H:i') }}</td>
                                    <td class="status-cell">
                                        @if($participant->account_status === 'pending')
                                            <span class="badge badge-custom badge-pending"><i class="bi bi-clock-history me-1"></i>PENDING</span>
                                        @elseif($participant->account_status === 'approved')
                                            <span class="badge badge-custom badge-approved"><i class="bi bi-patch-check-fill me-1"></i>APPROVED</span>
                                        @else
                                            <span class="badge badge-custom badge-rejected"><i class="bi bi-x-circle-fill me-1"></i>REJECTED</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end action-cell">
                                        <div class="d-flex justify-content-end gap-2">
                                            <!-- Tombol Setujui -->
                                            <button type="button" 
                                                    onclick="updateAccountStatus({{ $participant->id }}, 'approved', '{{ $participant->name }}')" 
                                                    class="btn btn-sm btn-success btn-approve"
                                                    {{ $participant->account_status === 'approved' ? 'disabled style=display:none;' : '' }}>
                                                <i class="bi bi-check-lg me-1"></i>Setujui
                                            </button>

                                            <!-- Tombol Tolak -->
                                            <button type="button" 
                                                    onclick="updateAccountStatus({{ $participant->id }}, 'rejected', '{{ $participant->name }}')" 
                                                    class="btn btn-sm btn-danger btn-reject"
                                                    {{ $participant->account_status === 'rejected' ? 'disabled style=display:none;' : '' }}>
                                                <i class="bi bi-x-lg me-1"></i>Tolak
                                            </button>

                                            <!-- Tombol Reset ke Pending (jika sudah approved/rejected) -->
                                            <!-- Note: Opsional untuk memudahkan admin mengubah kembali ke pending jika diperlukan -->
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="bi bi-people-fill text-muted display-4"></i>
                                        <p class="mt-3 text-muted">Tidak ada data akun peserta ditemukan.</p>
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
            {{ $participants->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function updateAccountStatus(userId, status, userName) {
        const actionText = status === 'approved' ? 'menyetujui' : 'menolak';
        const actionColor = status === 'approved' ? '#15803d' : '#b91c1c';

        Swal.fire({
            title: 'Konfirmasi Verifikasi',
            text: `Apakah Anda yakin ingin ${actionText} akun peserta "${userName}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: actionColor,
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Proses!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading spinner
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang memperbarui status di database...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Jalankan AJAX Request menggunakan Fetch API
                fetch(`/admin/account-verification/${userId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Tampilkan notifikasi sukses
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                            confirmButtonColor: '#4f46e5'
                        });

                        // Update tampilan baris tabel secara dinamis tanpa reload
                        const row = document.getElementById(`row-user-${userId}`);
                        const statusCell = row.querySelector('.status-cell');
                        const approveBtn = row.querySelector('.btn-approve');
                        const rejectBtn = row.querySelector('.btn-reject');

                        if (status === 'approved') {
                            statusCell.innerHTML = `<span class="badge badge-custom badge-approved"><i class="bi bi-patch-check-fill me-1"></i>APPROVED</span>`;
                            approveBtn.style.display = 'none';
                            approveBtn.disabled = true;
                            
                            rejectBtn.style.display = 'inline-block';
                            rejectBtn.disabled = false;
                        } else {
                            statusCell.innerHTML = `<span class="badge badge-custom badge-rejected"><i class="bi bi-x-circle-fill me-1"></i>REJECTED</span>`;
                            rejectBtn.style.display = 'none';
                            rejectBtn.disabled = true;
                            
                            approveBtn.style.display = 'inline-block';
                            approveBtn.disabled = false;
                        }
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
                    console.error('AJAX Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Sistem',
                        text: error.message || 'Terjadi kesalahan saat memproses permintaan Anda ke server.',
                        confirmButtonColor: '#4f46e5'
                    });
                });
            }
        });
    }
</script>
@endsection

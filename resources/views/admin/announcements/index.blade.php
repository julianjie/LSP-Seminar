@extends('layouts.admin')

@section('title', 'Manajemen Pengumuman')
@section('page_title', 'Manajemen Pengumuman')

@section('content')
<div class="row">
    <div class="col-12 animate-fade-in-up">
        <!-- Actions Card -->
        <div class="card card-premium shadow border-0 mb-4">
            <div class="card-body d-flex align-items-center justify-content-between p-4 flex-wrap gap-3">
                <a href="{{ route('admin.announcements.create') }}" class="btn btn-premium"><i class="bi bi-megaphone me-1"></i> Buat Pengumuman Baru</a>
                <div class="text-muted small">
                    Total Pengumuman: <strong class="text-dark">{{ $announcements->total() }}</strong> item
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card card-premium shadow border-0 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4" style="width: 80px;">Gambar</th>
                                <th>Judul Pengumuman</th>
                                <th>Target Audiens</th>
                                <th>Status Aktif</th>
                                <th>Jadwal Publikasi</th>
                                <th>Dibuat Oleh</th>
                                <th class="pe-4 text-end" style="width: 200px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($announcements as $announce)
                                <tr>
                                    <td class="ps-4">
                                        @if($announce->image)
                                            <img src="{{ asset('storage/' . $announce->image) }}" class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover;" alt="Image">
                                        @else
                                            <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded border" style="width: 50px; height: 50px;">
                                                <i class="bi bi-megaphone fs-5"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $announce->title }}</div>
                                        <div class="text-muted small">{{ Str::limit(strip_tags($announce->content), 80) }}</div>
                                    </td>
                                    <td>
                                        @if($announce->target === 'all')
                                            <span class="badge bg-secondary">SEMUA PENGGUNA</span>
                                        @elseif($announce->target === 'participants')
                                            <span class="badge bg-info text-dark">PESERTA SAJA</span>
                                        @else
                                            <span class="badge bg-dark text-white">ADMIN SAJA</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($announce->is_active)
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>AKTIF</span>
                                        @else
                                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>NONAKTIF</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $announce->published_at->format('d M Y') }}</div>
                                        <div class="text-muted small">{{ $announce->published_at->format('H:i') }} WIB</div>
                                        @if($announce->published_at > now())
                                            <span class="badge bg-warning text-dark small" style="font-size: 0.7rem;">TERJADWAL</span>
                                        @endif
                                    </td>
                                    <td>{{ $announce->creator->name ?? 'System' }}</td>
                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.announcements.edit', $announce->id) }}" class="btn btn-sm btn-outline-warning" title="Ubah"><i class="bi bi-pencil"></i></a>
                                            
                                            <form id="delete-form-{{ $announce->id }}" action="{{ route('admin.announcements.destroy', $announce->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete({{ $announce->id }}, '{{ $announce->title }}')" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="bi bi-megaphone text-muted display-4"></i>
                                        <p class="mt-3 text-muted">Belum ada pengumuman dibuat.</p>
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
            {{ $announcements->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function confirmDelete(id, title) {
        Swal.fire({
            title: 'Hapus Pengumuman?',
            text: `Apakah Anda yakin ingin menghapus pengumuman "${title}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#b91c1c',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
</script>
@endsection

@extends('layouts.admin')

@section('title', 'Manajemen Seminar')
@section('page_title', 'Manajemen Seminar')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Actions Card -->
        <div class="card card-premium shadow border-0 mb-4">
            <div class="card-body d-flex align-items-center justify-content-between p-4 flex-wrap gap-3">
                <a href="{{ route('admin.seminars.create') }}" class="btn btn-premium"><i class="bi bi-calendar-plus me-1"></i> Tambah Seminar Baru</a>
                <div class="text-muted small">
                    Total Seminar: <strong class="text-dark">{{ $seminars->total() }}</strong> kegiatan
                </div>
            </div>
        </div>

        <!-- Seminars Table Card -->
        <div class="card card-premium shadow border-0 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4" style="width: 80px;">Poster</th>
                                <th>Judul Seminar</th>
                                <th>Pembicara</th>
                                <th>Tanggal</th>
                                <th>Kuota (Sisa)</th>
                                <th>Biaya</th>
                                <th>Status</th>
                                <th class="pe-4 text-end" style="width: 250px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($seminars as $seminar)
                                <tr>
                                    <td class="ps-4">
                                        @if($seminar->poster)
                                            <img src="{{ asset('storage/' . $seminar->poster) }}" class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover;" alt="Poster">
                                        @else
                                            <div class="bg-gradient text-white d-flex align-items-center justify-content-center rounded shadow-sm fw-bold text-center" style="width: 50px; height: 50px; font-size: 0.75rem; background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%) !important;">
                                                SEM
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $seminar->title }}</div>
                                        <div class="text-muted small">Loc: {{ $seminar->location }}</div>
                                    </td>
                                    <td>{{ $seminar->speaker }}</td>
                                    <td>
                                        <div>{{ $seminar->seminar_date->format('d M Y') }}</div>
                                        <div class="text-muted small">{{ date('H:i', strtotime($seminar->start_time)) }} - {{ date('H:i', strtotime($seminar->end_time)) }}</div>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $seminar->quota }}</span>
                                        <span class="text-muted small">({{ $seminar->remainingSlots() }} sisa)</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold {{ $seminar->price == 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $seminar->price == 0 ? 'GRATIS' : 'Rp ' . number_format($seminar->price, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($seminar->status === 'draft')
                                            <span class="badge badge-custom badge-draft"><i class="bi bi-file-earmark-lock me-1"></i>DRAFT</span>
                                        @elseif($seminar->status === 'published')
                                            <span class="badge badge-custom badge-approved"><i class="bi bi-globe me-1"></i>PUBLISHED</span>
                                        @elseif($seminar->status === 'closed')
                                            <span class="badge badge-custom badge-closed"><i class="bi bi-lock me-1"></i>CLOSED</span>
                                        @else
                                            <span class="badge badge-custom badge-cancelled"><i class="bi bi-check2-all me-1"></i>COMPLETED</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.seminars.show', $seminar->id) }}" class="btn btn-sm btn-outline-info" title="Detail"><i class="bi bi-eye"></i> Detail</a>
                                            <a href="{{ route('admin.seminars.edit', $seminar->id) }}" class="btn btn-sm btn-outline-warning" title="Ubah"><i class="bi bi-pencil"></i></a>
                                            
                                            <form id="delete-form-{{ $seminar->id }}" action="{{ route('admin.seminars.destroy', $seminar->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete({{ $seminar->id }}, '{{ $seminar->title }}')" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="bi bi-calendar-x text-muted display-4"></i>
                                        <p class="mt-3 text-muted">Belum ada data seminar.</p>
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
            {{ $seminars->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function confirmDelete(id, title) {
        Swal.fire({
            title: 'Hapus Seminar?',
            text: `Apakah Anda yakin ingin menghapus seminar "${title}"? Tindakan ini akan menghapus semua pendaftaran terkait secara permanen!`,
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

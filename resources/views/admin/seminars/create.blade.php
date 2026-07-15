@extends('layouts.admin')

@section('title', 'Tambah Seminar Baru')
@section('page_title', 'Tambah Seminar Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Back Link -->
        <div class="mb-3">
            <a href="{{ route('admin.seminars.index') }}" class="text-decoration-none"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Seminar</a>
        </div>

        <div class="card card-premium shadow border-0 p-4">
            <div class="card-body">
                <form action="{{ route('admin.seminars.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <h5 class="fw-bold mb-4 border-bottom pb-2 text-dark"><i class="bi bi-info-circle-fill text-primary me-2"></i> Informasi Seminar</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label fw-semibold">Judul Seminar <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control bg-light @error('title') is-invalid @enderror" placeholder="Contoh: Belajar Laravel 11" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="slug" class="form-label fw-semibold">Slug (URL) <span class="text-danger">*</span></label>
                            <input type="text" name="slug" id="slug" class="form-control bg-light @error('slug') is-invalid @enderror" placeholder="belajar-laravel-11" value="{{ old('slug') }}" required readonly>
                            <small class="text-muted text-xs">Akan dibuat otomatis berdasarkan judul.</small>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="speaker" class="form-label fw-semibold">Pembicara / Speaker <span class="text-danger">*</span></label>
                            <input type="text" name="speaker" id="speaker" class="form-control bg-light @error('speaker') is-invalid @enderror" placeholder="Contoh: Taylor Otwell" value="{{ old('speaker') }}" required>
                            @error('speaker')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label fw-semibold">Lokasi Pelaksanaan <span class="text-danger">*</span></label>
                            <input type="text" name="location" id="location" class="form-control bg-light @error('location') is-invalid @enderror" placeholder="Contoh: Lab RPL Lantai 3 / Zoom" value="{{ old('location') }}" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">Deskripsi Lengkap Seminar <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" rows="5" class="form-control bg-light @error('description') is-invalid @enderror" placeholder="Tuliskan detail, materi, syarat, dan deskripsi seminar..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <h5 class="fw-bold mb-4 border-bottom pb-2 text-dark mt-4"><i class="bi bi-clock-fill text-primary me-2"></i> Jadwal & Kapasitas</h5>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="seminar_date" class="form-label fw-semibold">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                            <input type="date" name="seminar_date" id="seminar_date" class="form-control bg-light @error('seminar_date') is-invalid @enderror" value="{{ old('seminar_date') }}" required>
                            @error('seminar_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="start_time" class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" id="start_time" class="form-control bg-light @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="end_time" class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" name="end_time" id="end_time" class="form-control bg-light @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="quota" class="form-label fw-semibold">Kuota Peserta <span class="text-danger">*</span></label>
                            <input type="number" name="quota" id="quota" class="form-control bg-light @error('quota') is-invalid @enderror" placeholder="Contoh: 100" value="{{ old('quota') }}" min="1" required>
                            @error('quota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label fw-semibold">Biaya Seminar (IDR) <span class="text-danger">*</span></label>
                            <input type="number" name="price" id="price" class="form-control bg-light @error('price') is-invalid @enderror" placeholder="Masukkan 0 jika gratis" value="{{ old('price', 0) }}" min="0" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="registration_deadline" class="form-label fw-semibold">Batas Akhir Pendaftaran <span class="text-danger">*</span></label>
                            <input type="date" name="registration_deadline" id="registration_deadline" class="form-control bg-light @error('registration_deadline') is-invalid @enderror" value="{{ old('registration_deadline') }}" required>
                            @error('registration_deadline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h5 class="fw-bold mb-4 border-bottom pb-2 text-dark mt-4"><i class="bi bi-image-fill text-primary me-2"></i> Media & Status</h5>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="poster" class="form-label fw-semibold">Poster Seminar</label>
                            <input type="file" name="poster" id="poster" class="form-control bg-light @error('poster') is-invalid @enderror" accept="image/*">
                            <small class="text-muted text-xs">Format: JPG, JPEG, PNG, WEBP. Maks: 2MB.</small>
                            @error('poster')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label fw-semibold">Status Seminar <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select bg-light @error('status') is-invalid @enderror" required>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="border-top pt-4 text-end">
                        <button type="reset" class="btn btn-outline-secondary px-4 py-2 me-2">Reset</button>
                        <button type="submit" class="btn btn-premium px-5 py-2">Simpan Seminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // Generate slug automatically based on title input
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');

    titleInput.addEventListener('input', function() {
        let titleVal = this.value;
        let slugVal = titleVal.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '') // remove special chars
            .replace(/\s+/g, '-')         // replace space with hyphens
            .replace(/-+/g, '-')          // replace multiple hyphens
            .trim();
        slugInput.value = slugVal;
    });
</script>
@endsection

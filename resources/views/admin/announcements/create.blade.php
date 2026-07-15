@extends('layouts.admin')

@section('title', 'Buat Pengumuman Baru')
@section('page_title', 'Buat Pengumuman Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 animate-fade-in-up">
        <!-- Back Link -->
        <div class="mb-3">
            <a href="{{ route('admin.announcements.index') }}" class="text-decoration-none"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Pengumuman</a>
        </div>

        <div class="card card-premium shadow border-0 p-4">
            <div class="card-body">
                <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">Judul Pengumuman <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control bg-light @error('title') is-invalid @enderror" placeholder="Tuliskan judul pengumuman..." value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label fw-semibold">Isi Pengumuman <span class="text-danger">*</span></label>
                        <textarea name="content" id="content" rows="6" class="form-control bg-light @error('content') is-invalid @enderror" placeholder="Tuliskan isi pengumuman lengkap di sini..." required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="target" class="form-label fw-semibold">Target Penerima <span class="text-danger">*</span></label>
                            <select name="target" id="target" class="form-select bg-light @error('target') is-invalid @enderror" required>
                                <option value="all" {{ old('target') == 'all' ? 'selected' : '' }}>Semua Pengguna</option>
                                <option value="participants" {{ old('target') == 'participants' ? 'selected' : '' }}>Khusus Peserta</option>
                                <option value="admins" {{ old('target') == 'admins' ? 'selected' : '' }}>Khusus Admin</option>
                            </select>
                            @error('target')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="published_at" class="form-label fw-semibold">Waktu Publikasi <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="published_at" id="published_at" class="form-control bg-light @error('published_at') is-invalid @enderror" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}" required>
                            @error('published_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label fw-semibold">Gambar Pendukung</label>
                            <input type="file" name="image" id="image" class="form-control bg-light @error('image') is-invalid @enderror" accept="image/*">
                            <small class="text-muted text-xs">Maksimal file 2MB (JPG, PNG, WEBP).</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="is_active" class="form-label fw-semibold">Status Keaktifan <span class="text-danger">*</span></label>
                            <select name="is_active" id="is_active" class="form-select bg-light @error('is_active') is-invalid @enderror" required>
                                <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif / Draft</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="border-top pt-4 text-end">
                        <button type="reset" class="btn btn-outline-secondary px-4 py-2 me-2">Reset</button>
                        <button type="submit" class="btn btn-premium px-5 py-2">Terbitkan Pengumuman</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

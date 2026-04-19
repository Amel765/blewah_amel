@extends('layouts.app')

@section('title', 'Buat Pengajuan Baru')

@section('content')
<div class="row mt-4 justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="fw-bold mb-0">Langkah 1: Nama Pengajuan</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('user.submission.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="title" class="form-label fw-bold">Judul / Nama Kasus</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Contoh: Pemilihan Bibit Musim Kemarau" required>
                        <div class="form-text small">Berikan nama yang deskriptif untuk pengajuan Anda.</div>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">Deskripsi Masalah</label>
                        <textarea class="form-control" id="description" name="description" rows="5" placeholder="Tuliskan detail kriteria atau alternatif yang anda inginkan di sini..." required></textarea>
                        <div class="form-text small">Jelaskan secara detail apa yang ingin Anda konsultasikan.</div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Lanjut ke Kelola Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

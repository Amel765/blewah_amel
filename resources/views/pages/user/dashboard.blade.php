@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold mb-0">Selamat Datang, {{ auth()->user()->name }}</h3>
                    <a href="{{ route('user.submission.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Buat Pengajuan Baru
                    </a>
                </div>
                <p class="text-muted">Kelola pengajuan perhitungan SPK Anda di sini.</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="fw-bold mb-0">Daftar Pengajuan</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3">ID</th>
                                <th class="py-3">Tanggal</th>
                                <th class="py-3">Status</th>
                                <th class="py-3">Hasil</th>
                                <th class="px-4 py-3 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($submissions as $submission)
                                <tr>
                                    <td class="px-4">{{ $submission->id }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $submission->title }}</div>
                                        <small class="text-muted">Dibuat: {{ $submission->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        @if($submission->status == 'draft')
                                            <span class="badge bg-info">Draft (Belum Dilengkapi)</span>
                                        @elseif($submission->status == 'pending')
                                            <span class="badge bg-warning">Menunggu Diproses Admin</span>
                                        @else
                                            <span class="badge bg-success">Selesai</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($submission->status == 'processed')
                                            <a href="{{ route('user.submission.show', $submission->id) }}" class="text-success fw-bold text-decoration-none">
                                                Lihat Hasil <i class="ti ti-chevron-right small"></i>
                                            </a>
                                        @else
                                            <span class="text-muted small">Sedang diproses</span>
                                        @endif
                                    </td>
                                    <td class="px-4 text-end">
                                        @if($submission->status == 'draft')
                                            <a href="{{ route('user.submission.manage_data', $submission->id) }}" class="btn btn-sm btn-primary">Lengkapi Data</a>
                                        @else
                                            <a href="{{ route('user.submission.show', $submission->id) }}" class="btn btn-sm btn-light">Detail</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted mb-3">Belum ada pengajuan.</div>
                                        <a href="{{ route('user.submission.create') }}" class="btn btn-outline-primary btn-sm">Mulai Sekarang</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

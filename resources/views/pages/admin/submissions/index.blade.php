@extends('layouts.app')

@section('title', 'Manajemen Pengajuan')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="fw-bold mb-0">Daftar Pengajuan User</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3">ID</th>
                                <th class="py-3">User</th>
                                <th class="py-3">Tanggal</th>
                                <th class="py-3">Status</th>
                                <th class="px-4 py-3 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($submissions as $submission)
                                <tr>
                                    <td class="px-4">{{ $submission->id }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $submission->user->name }}</div>
                                        <small class="text-muted">{{ $submission->user->email }}</small>
                                    </td>
                                    <td>{{ $submission->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        @if($submission->status == 'pending')
                                            <span class="badge bg-warning">Menunggu Diproses</span>
                                        @else
                                            <span class="badge bg-success">Selesai</span>
                                        @endif
                                    </td>
                                    <td class="px-4 text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('admin.submissions.show', $submission->id) }}" class="btn btn-sm btn-light">Detail</a>
                                            @if($submission->status == 'pending')
                                                <form action="{{ route('admin.submissions.auto_process', $submission->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Lakukan proses otomatis 1-klik?')">
                                                        <i class="ti ti-sparkles"></i> Proses Otomatis
                                                    </button>
                                                </form>
                                                <a href="{{ route('admin.submissions.input_result', $submission->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="ti ti-edit"></i> Input Manual
                                                </a>
                                            @endif
                                            <form action="{{ route('admin.submissions.destroy', $submission->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus perhitungan ini? Semua data terkait (kriteria, alternatif, nilai) akan ikut terhapus.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="ti ti-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Belum ada pengajuan dari user.</td>
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

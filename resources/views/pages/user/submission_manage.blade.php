@extends('layouts.app')

@section('title', 'Kelola Data Pengajuan')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-0">Langkah 2: Kelola Kriteria & Alternatif</h4>
                        <p class="text-muted mb-0">Tentukan kriteria dan alternatif untuk: <strong>{{ $submission->title }}</strong></p>
                    </div>
                    <a href="{{ route('user.submission.input_values', $submission->id) }}" class="btn btn-success px-4">
                        Lanjut ke Input Nilai <i class="ti ti-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Criteria Section -->
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Daftar Kriteria</h6>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addCriteriaModal">
                            <i class="ti ti-plus me-1"></i> Tambah
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light text-center">
                                <tr>
                                    <th>Nama</th>
                                    <th>Tipe</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($criteria as $crit)
                                    <tr>
                                        <td>{{ $crit->name }}</td>
                                        <td class="text-center">{{ ucfirst($crit->type) }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('user.submission.destroy_criteria', $crit->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm text-danger" onclick="return confirm('Hapus kriteria ini?')">
                                                    <i class="ti ti-trash fs-5"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted small">Belum ada kriteria.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Alternative Section -->
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Daftar Alternatif (Bibit)</h6>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addAlternativeModal">
                            <i class="ti ti-plus me-1"></i> Tambah
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light text-center">
                                <tr>
                                    <th>Nama Bibit</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alternatives as $alt)
                                    <tr>
                                        <td>{{ $alt->name }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('user.submission.destroy_alternative', $alt->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm text-danger" onclick="return confirm('Hapus alternatif ini?')">
                                                    <i class="ti ti-trash fs-5"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-4 text-muted small">Belum ada alternatif.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Criteria Modal -->
<div class="modal fade" id="addCriteriaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('user.submission.store_criteria', $submission->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kriteria Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kriteria</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Ketahanan Hama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe Kriteria</label>
                        <select name="type" class="form-select" required>
                            <option value="benefit">Benefit (Semakin besar semakin baik)</option>
                            <option value="cost">Cost (Semakin kecil semakin baik)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add Alternative Modal -->
<div class="modal fade" id="addAlternativeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('user.submission.store_alternative', $submission->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Alternatif Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Bibit / Alternatif</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Bibit Unggul A" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

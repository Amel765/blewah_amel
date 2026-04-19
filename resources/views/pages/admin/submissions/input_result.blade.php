@extends('layouts.app')

@section('title', 'Input Hasil Ranking Manual')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold mb-0">Input Hasil: {{ $submission->title }}</h4>
                    <span class="badge bg-warning px-3 py-2">Peninjauan Admin</span>
                </div>
                <hr>
                <div class="alert alert-info py-2 small">
                    <i class="ti ti-info-circle me-1"></i> Admin silakan masukkan hasil perankingan akhir berdasarkan analisis mandiri. 
                    @if($suggestions)
                        <br>Skor saran (CoCoSo) ditampilkan di tabel bawah sebagai referensi.
                    @endif
                </div>
            </div>
        </div>

        <form action="{{ route('admin.submissions.store_result', $submission->id) }}" method="POST">
            @csrf
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white p-3">
                    <h6 class="fw-bold mb-0">Kesimpulan / Hasil Narasi</h6>
                </div>
                <div class="card-body">
                    <textarea name="manual_text" class="form-control" rows="6" placeholder="Tuliskan kesimpulan hasil perhitungan atau saran untuk user di sini..." required></textarea>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white p-3">
                    <h6 class="fw-bold mb-0">Form Perankingan Alternatif</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th width="10%">Peringkat</th>
                                    <th>Nama Alternatif</th>
                                    <th width="20%">Skor (Qi / Nilai Akhir)</th>
                                    @if($suggestions)
                                        <th width="20%" class="text-muted small">Saran (CoCoSo)</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submission->alternatives as $index => $alt)
                                    <tr>
                                        <td>
                                            <input type="number" name="ranking[{{ $index }}][rank]" class="form-control" value="{{ $index + 1 }}" required min="1">
                                        </td>
                                        <td>
                                            <input type="text" name="ranking[{{ $index }}][name]" class="form-control" value="{{ $alt->name }}" readonly>
                                        </td>
                                        <td>
                                            <input type="number" step="0.0001" name="ranking[{{ $index }}][qi]" class="form-control" placeholder="Contoh: 1.2345" required>
                                        </td>
                                        @if($suggestions)
                                            <td class="text-muted small">
                                                @php
                                                    $suggested = collect($suggestions)->firstWhere('alternative.id', $alt->id);
                                                @endphp
                                                {{ $suggested ? number_format($suggested['qi'], 4) : '-' }}
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white p-3 text-end">
                    <a href="{{ route('admin.submissions.show', $submission->id) }}" class="btn btn-light me-2">Batal</a>
                    <button type="submit" class="btn btn-success px-4" onclick="return confirm('Simpan hasil ini dan kirim ke user?')">
                        <i class="ti ti-device-floppy me-1"></i> Simpan & Kirim Hasil
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

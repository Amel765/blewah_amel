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
                    <i class="ti ti-info-circle me-1"></i> Admin silakan masukkan hasil perankingan akhir. Skor CoCoSo tersedia sebagai referensi.
                </div>
            </div>
        </div>

        {{-- TABEL MATRIKS HIJAU (AHP) --}}
        {{-- Ganti '$ahp_results' dengan nama variabel yang benar dari Controller kamu --}}
        @if(isset($ahp_results))
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white p-3">
                <h6 class="fw-bold mb-0">Hasil Perhitungan Bobot (AHP)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="text-center text-white" style="background-color: #20c997;">
                            <tr>
                                <th>Kriteria</th>
                                @foreach($ahp_results['criteria_names'] as $name)
                                    <th>{{ strtolower($name) }}</th>
                                @endforeach
                                <th class="bg-dark">Bobot (Eigenvector)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ahp_results['matrix'] as $rowKey => $rowValues)
                            <tr>
                                <td class="text-center fw-bold bg-light">{{ strtolower($rowKey) }}</td>
                                @foreach($rowValues as $value)
                                    <td class="text-center">{{ number_format($value, 3) }}</td>
                                @endforeach
                                <td class="text-center fw-bold bg-light">{{ number_format($ahp_results['weights'][$rowKey], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer" style="background-color: #e6fffa;">
                <div class="small text-success fw-bold">
                    Lambda Max (&lambda; max): {{ number_format($ahp_results['lambda_max'] ?? 0, 4) }} <br>
                    Consistency Index (CI): {{ number_format($ahp_results['ci'] ?? 0, 4) }} <br>
                    Consistency Ratio (CR): {{ number_format($ahp_results['cr'] ?? 0, 4) }} <br>
                    <span class="text-muted small">&check; CR < 0.1 - Matriks Konsisten</span>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('admin.submissions.store_result', $submission->id) }}" method="POST">
            @csrf
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white p-3">
                    <h6 class="fw-bold mb-0">Kesimpulan / Hasil Narasi</h6>
                </div>
                <div class="card-body">
                    <textarea name="manual_text" class="form-control" rows="6" placeholder="Tuliskan kesimpulan..." required></textarea>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white p-3">
                    <h6 class="fw-bold mb-0">Form Perankingan Alternatif</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="10%" class="text-center">Rank</th>
                                    <th>Nama Alternatif</th>
                                    <th width="25%">Skor Akhir (Qi)</th>
                                    @if($suggestions)
                                        <th width="20%" class="text-muted small text-center">Saran (CoCoSo)</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submission->alternatives as $index => $alt)
                                    @php
                                        $suggested = $suggestions ? collect($suggestions)->firstWhere('alternative.id', $alt->id) : null;
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <input type="number" name="ranking[{{ $index }}][rank]" class="form-control text-center" value="{{ $index + 1 }}" required>
                                        </td>
                                        <td>
                                            <input type="text" name="ranking[{{ $index }}][name]" class="form-control bg-light" value="{{ $alt->name }}" readonly>
                                        </td>
                                        <td>
                                            <input type="number" step="0.0001" name="ranking[{{ $index }}][qi]" 
                                                   class="form-control fw-bold text-primary" 
                                                   value="{{ $suggested ? number_format($suggested['qi'], 4, '.', '') : '' }}" required>
                                        </td>
                                        @if($suggestions)
                                            <td class="text-center text-muted small">
                                                {{ $suggested ? number_format($suggested['qi'], 3) : '-' }}
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
                    <button type="submit" class="btn btn-success px-4">
                        <i class="ti ti-device-floppy me-1"></i> Simpan & Kirim
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

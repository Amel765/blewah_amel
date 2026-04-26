{{-- 1. BAGIAN TABEL MATRIKS HIJAU --}}
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
                        {{-- Mengambil nama kriteria dari array criteria --}}
                        @foreach($ahp_results['criteria'] as $crit)
                            <th>{{ strtolower($crit->name) }}</th>
                        @endforeach
                        <th class="bg-dark">Bobot (Eigenvector)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ahp_results['matrix'] as $i => $rowValues)
                    <tr>
                        <td class="text-center fw-bold bg-light">
                            {{ strtolower($ahp_results['criteria'][$i]->name) }}
                        </td>
                        @foreach($rowValues as $value)
                            {{-- DISINI KITA PAKSA JADI 2 ANGKA --}}
                            <td class="text-center">{{ number_format($value, 2) }}</td>
                        @endforeach
                        <td class="text-center fw-bold bg-light">
                            {{-- Ambil bobot berdasarkan index kriteria --}}
                            @php $critId = $ahp_results['criteria'][$i]->id; @endphp
                            {{ number_format($ahp_results['weights'][$critId]['weight'], 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer" style="background-color: #e6fffa;">
        <div class="small text-success fw-bold">
            {{-- Bagian Box Hijau di bawah tabel --}}
            Lambda Max (&lambda; max): {{ number_format($ahp_results['lambdaMax'], 2) }} <br>
            Consistency Index (CI): {{ number_format($ahp_results['ci'], 2) }} <br>
            Consistency Ratio (CR): {{ number_format($ahp_results['cr'], 2) }} <br>
            <span class="text-muted small">&check; CR < 0.1 - Matriks Konsisten</span>
        </div>
    </div>
</div>
@endif

{{-- 2. BAGIAN INPUT SKOR (QI) DI FORM --}}
{{-- Cari bagian input Qi di dalam loop alternatif --}}
<td>
    <input type="number" step="0.01" name="ranking[{{ $index }}][qi]" 
           class="form-control fw-bold text-primary" 
           value="{{ $suggested ? number_format($suggested['qi'], 2, '.', '') : '' }}" required>
</td>

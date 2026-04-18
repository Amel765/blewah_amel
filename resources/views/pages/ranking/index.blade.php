@extends('layouts.app')

@section('title', 'Hasil Ranking')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h5 class="page-title mb-4">Hasil Ranking Akhir</h5>
        
        @if($results)
        <div class="table-responsive">
          <table class="table table-bordered table-hover text-center align-middle">
            <thead class="bg-primary text-white">
              <tr>
                <th width="10%">Peringkat</th>
                <th>Nama Alternatif</th>
                <th width="15%">Si</th>
                <th width="15%">Pi</th>
                <th width="15%">Skor Akhir (Qi)</th>
                <th width="25%">Status Rekomendasi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($results as $index => $res)
              <tr class="{{ $index == 0 ? 'bg-light-success' : '' }}">
                <td>
                  @if($index == 0)
                    <i class="ti ti-trophy fs-8 text-warning"></i>
                  @else
                    <span class="fs-5 fw-bold">{{ $index + 1 }}</span>
                  @endif
                </td>
                <td class="fw-bold text-start ps-4">{{ $res['alternative']->name }}</td>
                <td>{{ number_format($res['si'], 4) }}</td>
                <td>{{ number_format($res['pi'], 4) }}</td>
                <td class="fs-5 fw-bold text-primary">{{ number_format($res['qi'], 4) }}</td>
                <td>
                  @if($index == 0)
                    <span class="badge bg-success px-3 py-2">Sangat Direkomendasikan</span>
                  @elseif($index < 3)
                    <span class="badge bg-primary px-3 py-2">Direkomendasikan</span>
                  @else
                    <span class="badge bg-secondary px-3 py-2">Alternatif Pendukung</span>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        
        <div class="mt-4 text-end">
          <button class="btn btn-outline-primary" onclick="window.print()">
            <i class="ti ti-printer me-2"></i> Cetak Laporan
          </button>
        </div>
        @else
        <div class="alert alert-warning">
          Belum ada data hasil perhitungan. Silakan lakukan <a href="{{ route('cocoso.index') }}">Perhitungan CoCoSo</a> terlebih dahulu.
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

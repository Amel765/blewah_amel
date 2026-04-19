@extends('layouts.app')

@section('title', 'Perhitungan CoCoSo')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h5 class="page-title mb-4">Perhitungan CoCoSo (Perankingan Alternatif)</h5>
        
        @if($error)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>⚠️ Error:</strong> {{ $error }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        <form action="{{ route('admin.cocoso.calculate') }}" method="POST">
          @csrf
          <div class="alert alert-success border-0 shadow-none mb-4">
            <h6 class="alert-heading fw-bold">Input Nilai Alternatif per Kriteria</h6>
            <p class="mb-0 small">Masukkan nilai performa (skala 0-100) untuk setiap bibit pada kriteria yang ada.</p>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
              <thead class="bg-light">
                <tr>
                  <th rowspan="2" class="align-middle">Alternatif</th>
                  <th colspan="{{ count($criteria) }}">Kriteria</th>
                </tr>
                <tr>
                  @foreach($criteria as $c)
                  <th>{{ $c->name }}<br><small class="fw-normal">({{ ucfirst($c->type) }})</small></th>
                  @endforeach
                </tr>
              </thead>
              <tbody>
                @if($alternatives->count() == 0)
                  <tr>
                    <td colspan="{{ count($criteria) + 1 }}" class="text-center py-4 text-muted">
                      <i class="ti ti-info-circle d-block mb-2" style="font-size: 2rem;"></i>
                      Belum ada alternatif.<br>
                      <a href="{{ route('admin.alternative.index') }}" class="btn btn-sm btn-outline-primary mt-2">Tambah Alternatif</a>
                    </td>
                  </tr>
                @else
                @foreach($alternatives as $alt)
                <tr>
                  <td class="fw-bold">{{ $alt->name }}</td>
                  @foreach($criteria as $crit)
                  <td>
                    <input type="number" step="0.01" name="score[{{ $alt->id }}][{{ $crit->id }}]" class="form-control form-control-sm text-center" value="{{ isset($savedScores[$alt->id][$crit->id]) ? $savedScores[$alt->id][$crit->id] : 0 }}">
                  </td>
                  @endforeach
                </tr>
                @endforeach
                @endif
              </tbody>
            </table>
          </div>
          <div class="text-end mt-3">
            <button type="submit" class="btn btn-success px-4" {{ count($criteria) == 0 ? 'disabled' : '' }}>Hitung Perankingan</button>
          </div>
        </form>

        @if($results)
        <hr class="my-5">
        <h6 class="fw-bold mb-3">Hasil Akhir CoCoSo</h6>
        <div class="table-responsive">
          <table class="table table-bordered table-hover text-center">
            <thead class="bg-success text-white">
              <tr>
                <th width="5%">No</th>
                <th>Alternatif</th>
                <th>Si</th>
                <th>Pi</th>
                <th>Score (Qi)</th>
                <th>Peringkat</th>
              </tr>
            </thead>
            <tbody>
              @foreach($results as $index => $res)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td class="fw-bold text-start">{{ $res['alternative']->name }}</td>
                <td>{{ number_format($res['si'], 4) }}</td>
                <td>{{ number_format($res['pi'], 4) }}</td>
                <td class="bg-light-success fw-bold">{{ number_format($res['qi'], 4) }}</td>
                <td>
                  <span class="badge {{ $index < 3 ? 'bg-success' : 'bg-secondary' }}">
                    Rank {{ $index + 1 }}
                  </span>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

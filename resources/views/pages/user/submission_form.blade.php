@extends('layouts.app')

@section('title', 'Buat Pengajuan')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4 border-bottom">
                <h4 class="fw-bold mb-0">Langkah 3: Input Perbandingan & Skor</h4>
                <p class="text-muted mb-0">Pengajuan: <strong>{{ $submission->title }}</strong></p>
            </div>
        </div>

        <form action="{{ route('user.submission.submit_values', $submission->id) }}" method="POST">
            @csrf
            
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">1. Perbandingan Kriteria (AHP)</h5>
                    <div class="alert alert-info border-0 shadow-none mb-4">
                        <p class="mb-0 small">Bandingkan kepentingan kriteria satu dengan kriteria lainnya (Skala 1-9).</p>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th width="30%">Kriteria 1</th>
                                    <th width="40%">Nilai Perbandingan</th>
                                    <th width="30%">Kriteria 2</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($criteria as $i => $c1)
                                    @foreach($criteria as $j => $c2)
                                        @if($i < $j)
                                            <tr>
                                                <td class="fw-bold">{{ $c1->name }}</td>
                                                <td>
                                                    <input type="number" step="any" min="0.1" max="9" name="comparison[{{ $c1->id }}][{{ $c2->id }}]" class="form-control form-control-sm d-inline-block w-50 text-center" value="1" required>
                                                </td>
                                                <td class="fw-bold">{{ $c2->name }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">2. Penilaian Alternatif (CoCoSo)</h5>
                    <div class="alert alert-success border-0 shadow-none mb-4">
                        <p class="mb-0 small">Masukkan nilai performa (0-100) untuk setiap bibit pada kriteria.</p>
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
                                @foreach($alternatives as $alt)
                                    <tr>
                                        <td class="fw-bold">{{ $alt->name }}</td>
                                        @foreach($criteria as $crit)
                                            <td>
                                                <input type="number" step="any" name="score[{{ $alt->id }}][{{ $crit->id }}]" class="form-control form-control-sm text-center" value="0" required>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="text-end mb-5">
                <a href="{{ route('user.dashboard') }}" class="btn btn-light px-4 me-2">Batal</a>
                <button type="submit" class="btn btn-primary px-5">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</div>
@endsection

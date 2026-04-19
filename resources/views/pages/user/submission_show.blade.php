@extends('layouts.app')

@section('title', 'Detail Pengajuan')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold mb-0">Pengajuan: {{ $submission->title }} (#{{ $submission->id }})</h4>
                    <div>
                        @if($submission->status == 'pending')
                            <span class="badge bg-warning px-3 py-2">Menunggu Admin</span>
                        @else
                            <span class="badge bg-success px-3 py-2">Selesai Diproses</span>
                        @endif
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Tanggal Pengajuan:</p>
                        <p class="fw-bold">{{ $submission->created_at->format('d F Y H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Pemohon:</p>
                        <p class="fw-bold">{{ $submission->user->name }}</p>
                    </div>
                    <div class="col-md-12 mt-3">
                        <p class="mb-1 text-muted">Deskripsi Masalah:</p>
                        <div class="p-3 bg-light rounded border">
                            {{ $submission->description ?? 'Tidak ada deskripsi.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($submission->status == 'processed' && $submission->result_data)
            <div class="card shadow-sm border-success mb-4">
                <div class="card-header bg-success text-white p-3">
                    <h5 class="mb-0 fw-bold">Hasil Perankingan (CoCoSo)</h5>
                </div>
                <div class="card-body p-4">
                    @if(isset($submission->result_data['manual_text']))
                        <div class="mb-4">
                            <h6 class="fw-bold text-success"><i class="ti ti-notes me-1"></i> Kesimpulan Admin:</h6>
                            <div class="p-3 bg-success-subtle rounded border border-success-subtle">
                                {!! nl2br(e($submission->result_data['manual_text'])) !!}
                            </div>
                        </div>
                        <hr>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-hover text-center align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="15%">Peringkat</th>
                                    <th class="text-start">Alternatif</th>
                                    @if(isset($submission->result_data['ranking'][0]['si']))
                                        <th>Si</th>
                                        <th>Pi</th>
                                    @endif
                                    <th>Skor Akhir (Qi)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submission->result_data['ranking'] as $index => $res)
                                    <tr>
                                        <td>
                                            <span class="badge {{ $index == 0 ? 'bg-warning text-dark' : 'bg-light text-dark' }} px-3">
                                                #{{ $res['rank'] ?? ($index + 1) }}
                                            </span>
                                        </td>
                                        <td class="text-start fw-bold text-dark">{{ $res['name'] }}</td>
                                        @if(isset($res['si']))
                                            <td>{{ number_format($res['si'], 4) }}</td>
                                            <td>{{ number_format($res['pi'], 4) }}</td>
                                        @endif
                                        <td class="fw-bold text-success">{{ number_format($res['qi'], 4) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light p-3">
                    <p class="mb-0 small text-muted">
                        <strong>Catatan:</strong> 
                        @if(isset($submission->result_data['manual']))
                            Hasil ini telah ditinjau dan divalidasi secara manual oleh Admin.
                        @else
                            Hasil ini dihitung otomatis menggunakan metode AHP untuk pembobotan kriteria dan CoCoSo untuk perankingan.
                        @endif
                    </p>
                </div>
            </div>
        @else
            <div class="alert alert-info py-4 text-center shadow-sm">
                <i class="ti ti-clock fs-2 d-block mb-3"></i>
                <h5>Data sedang menunggu antrian untuk diproses oleh Admin.</h5>
                <p class="mb-0">Silakan cek kembali secara berkala untuk melihat hasil perhitungan.</p>
            </div>
        @endif

        <div class="text-start mb-5">
            <a href="{{ route('user.dashboard') }}" class="btn btn-light"><i class="ti ti-arrow-left me-1"></i> Kembali ke Dashboard</a>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Data Alternatif')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h5 class="page-title mb-0">Data Alternatif</h5>
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAltModal">
        <i class="ti ti-plus me-2"></i> Tambah Alternatif
      </button>
    </div>
    
    <div class="table-responsive">
      <table class="table table-hover table-bordered">
        <thead class="bg-success text-white">
          <tr>
            <th class="text-center" width="10%">No</th>
            <th>Nama Alternatif (Varietas)</th>
            <th class="text-center" width="15%">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @if($alternatives->count() == 0)
          <tr>
            <td colspan="3" class="text-center text-muted py-4">Belum ada alternatif</td>
          </tr>
          @else
          @foreach($alternatives as $index => $item)
          <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td class="fw-medium">{{ $item->name }}</td>
            <td class="text-center">
              <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editAltModal{{ $item->id }}">
                <i class="ti ti-edit"></i>
              </button>
              <form action="{{ route('alternative.destroy', $item->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus alternatif ini?')">
                  <i class="ti ti-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Add -->
<div class="modal fade" id="addAltModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('alternative.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Alternatif</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Alternatif</label>
            <input type="text" name="name" class="form-control" required placeholder="Contoh: Golden Aroma">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit for each alternative -->
@foreach($alternatives as $item)
<div class="modal fade" id="editAltModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('alternative.update', $item->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Alternatif</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Alternatif</label>
            <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endforeach
@endsection

@push('styles')
<style>
    .bg-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important; }
</style>
@endpush

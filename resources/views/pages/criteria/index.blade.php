@extends('layouts.app')

@section('title', 'Data Kriteria')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h5 class="page-title mb-0">Data Kriteria</h5>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCriteriaModal">
        <i class="ti ti-plus me-2"></i> Tambah Kriteria
      </button>
    </div>
    
    <div class="table-responsive">
      <table class="table table-hover table-bordered">
        <thead class="bg-primary text-white">
          <tr>
            <th class="text-center" width="10%">No</th>
            <th>Nama Kriteria</th>
            <th class="text-center" width="20%">Tipe</th>
            <th class="text-center" width="15%">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @if($criteria->count() == 0)
          <tr>
            <td colspan="4" class="text-center text-muted py-4">Belum ada kriteria</td>
          </tr>
          @else
          @foreach($criteria as $index => $item)
          <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td class="fw-medium">{{ $item->name }}</td>
            <td class="text-center">
              <span class="badge {{ $item->type == 'benefit' ? 'bg-success' : 'bg-warning' }}">
                {{ ucfirst($item->type) }}
              </span>
            </td>
            <td class="text-center">
              <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editCriteriaModal{{ $item->id }}">
                <i class="ti ti-edit"></i>
              </button>
              <form action="{{ route('criteria.destroy', $item->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus kriteria ini?')">
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
<div class="modal fade" id="addCriteriaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('criteria.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Kriteria</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Kriteria</label>
            <input type="text" name="name" class="form-control" required placeholder="Contoh: Produktivitas">
          </div>
          <div class="mb-3">
            <label class="form-label">Tipe Kriteria</label>
            <select name="type" class="form-select" required>
              <option value="benefit">Benefit</option>
              <option value="cost">Cost</option>
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

<!-- Modal Edit for each criteria -->
@foreach($criteria as $item)
<div class="modal fade" id="editCriteriaModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('criteria.update', $item->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Kriteria</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Kriteria</label>
            <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Tipe Kriteria</label>
            <select name="type" class="form-select" required>
              <option value="benefit" {{ $item->type == 'benefit' ? 'selected' : '' }}>Benefit</option>
              <option value="cost" {{ $item->type == 'cost' ? 'selected' : '' }}>Cost</option>
            </select>
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
    .bg-primary { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important; }
</style>
@endpush

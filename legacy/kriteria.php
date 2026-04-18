<?php
require_once 'includes/functions.php';
// check_login();

$pageTitle = "Data Kriteria - Spk AhpCocoso";
$currentPage = "kriteria";

ob_start();
?>
<style>
    .page-title { font-weight: 700; color: #11998e; }
    .card { border-radius: 15px; border: none; }
    .btn-primary { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: none; border-radius: 10px; }
    .btn-primary:hover { background: linear-gradient(135deg, #0f8a80 0%, #32d972 100%); }
    .table thead th { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; border: none; }
    .table { border-radius: 10px; overflow: hidden; }
</style>
<?php
$extraStyles = ob_get_clean();

include_once 'includes/header.php';
include_once 'includes/sidebar.php';
?>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h5 class="page-title mb-4">Data Kriteria</h5>
          <button class="btn btn-primary mb-4"><i class="ti ti-plus me-2"></i> Tambah Kriteria</button>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Kode</th>
                  <th>Nama Kriteria</th>
                  <th>Bobot</th>
                  <th>Jenis</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr><td>1</td><td>C1</td><td>Produktivitas</td><td>-</td><td><span class="badge bg-success">Benefit</span></td><td><button class="btn btn-sm btn-warning me-1"><i class="ti ti-edit"></i></button><button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button></td></tr>
                <tr><td>2</td><td>C2</td><td>Ketahanan terhadap hama</td><td>-</td><td><span class="badge bg-success">Benefit</span></td><td><button class="btn btn-sm btn-warning me-1"><i class="ti ti-edit"></i></button><button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button></td></tr>
                <tr><td>3</td><td>C3</td><td>Umur panen</td><td>-</td><td><span class="badge bg-danger">Cost</span></td><td><button class="btn btn-sm btn-warning me-1"><i class="ti ti-edit"></i></button><button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button></td></tr>
                <tr><td>4</td><td>C4</td><td>Kualitas buah</td><td>-</td><td><span class="badge bg-success">Benefit</span></td><td><button class="btn btn-sm btn-warning me-1"><i class="ti ti-edit"></i></button><button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button></td></tr>
                <tr><td>5</td><td>C5</td><td>Daya adaptasi lahan</td><td>-</td><td><span class="badge bg-success">Benefit</span></td><td><button class="btn btn-sm btn-warning me-1"><i class="ti ti-edit"></i></button><button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once 'includes/footer.php'; ?>

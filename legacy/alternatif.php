<?php
require_once 'includes/functions.php';
// check_login();

$pageTitle = "Data Alternatif - Spk AhpCocoso";
$currentPage = "alternatif";

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
          <h5 class="page-title mb-4">Data Alternatif</h5>
          <button class="btn btn-primary mb-4"><i class="ti ti-plus me-2"></i> Tambah Alternatif</button>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Kode</th>
                  <th>Nama Alternatif</th>
                  <th>Deskripsi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr><td>1</td><td>A1</td><td>Bibit Blewah Golden Aroma</td><td>Varietas unggul dengan aroma harum</td><td><button class="btn btn-sm btn-warning me-1"><i class="ti ti-edit"></i></button><button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button></td></tr>
                <tr><td>2</td><td>A2</td><td>Bibit Blewah Varietas Aruna</td><td>Varietas lokal adaptif</td><td><button class="btn btn-sm btn-warning me-1"><i class="ti ti-edit"></i></button><button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button></td></tr>
                <tr><td>3</td><td>A3</td><td>Bibit Blewah Sweet Net</td><td>Varietas manis dan tahan lama</td><td><button class="btn btn-sm btn-warning me-1"><i class="ti ti-edit"></i></button><button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button></td></tr>
                <tr><td>4</td><td>A4</td><td>Bibit Blewah King Blewah</td><td>Varietas premium ukuran besar</td><td><button class="btn btn-sm btn-warning me-1"><i class="ti ti-edit"></i></button><button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button></td></tr>
                <tr><td>5</td><td>A5</td><td>Bibit Blewah Rangipo</td><td>Varietas impor berkualitas tinggi</td><td><button class="btn btn-sm btn-warning me-1"><i class="ti ti-edit"></i></button><button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once 'includes/footer.php'; ?>

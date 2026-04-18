<?php
require_once 'includes/functions.php';
// check_login();

$pageTitle = "Data Penilaian - Spk AhpCocoso";
$currentPage = "nilai";

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
          <h5 class="page-title mb-4">Data Penilaian</h5>
          <button class="btn btn-primary mb-4"><i class="ti ti-plus me-2"></i> Tambah Penilaian</button>
          <div class="table-responsive">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th class="text-center align-middle">Alternatif</th>
                  <th class="text-center">C1<br>Produktivitas</th>
                  <th class="text-center">C2<br>Ket. Hama</th>
                  <th class="text-center">C3<br>Umur Panen</th>
                  <th class="text-center">C4<br>Kualitas</th>
                  <th class="text-center">C5<br>Adaptasi</th>
                  <th class="text-center align-middle">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr><td class="fw-medium">A1 - Golden Aroma</td><td class="text-center">85</td><td class="text-center">80</td><td class="text-center">75</td><td class="text-center">90</td><td class="text-center">82</td><td class="text-center"><button class="btn btn-sm btn-warning me-1"><i class="ti ti-edit"></i></button><button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button></td></tr>
                <tr><td class="fw-medium">A2 - Varietas Aruna</td><td class="text-center">78</td><td class="text-center">85</td><td class="text-center">70</td><td class="text-center">75</td><td class="text-center">88</td><td class="text-center"><button class="btn btn-sm btn-warning me-1"><i class="ti ti-edit"></i></button><button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button></td></tr>
                <tr><td class="fw-medium">A3 - Sweet Net</td><td class="text-center">90</td><td class="text-center">75</td><td class="text-center">80</td><td class="text-center">85</td><td class="text-center">78</td><td class="text-center"><button class="btn btn-sm btn-warning me-1"><i class="ti ti-edit"></i></button><button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button></td></tr>
                <tr><td class="fw-medium">A4 - King Blewah</td><td class="text-center">82</td><td class="text-center">70</td><td class="text-center">85</td><td class="text-center">80</td><td class="text-center">75</td><td class="text-center"><button class="btn btn-sm btn-warning me-1"><i class="ti ti-edit"></i></button><button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button></td></tr>
                <tr><td class="fw-medium">A5 - Rangipo</td><td class="text-center">75</td><td class="text-center">88</td><td class="text-center">65</td><td class="text-center">78</td><td class="text-center">90</td><td class="text-center"><button class="btn btn-sm btn-warning me-1"><i class="ti ti-edit"></i></button><button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once 'includes/footer.php'; ?>

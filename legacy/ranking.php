<?php
require_once 'includes/functions.php';
// check_login();

$pageTitle = "Hasil Ranking - Spk AhpCocoso";
$currentPage = "ranking";

ob_start();
?>
<style>
    .page-title { font-weight: 700; color: #11998e; }
    .card { border-radius: 15px; border: none; }
    .btn-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: none; border-radius: 10px; }
    .btn-success:hover { background: linear-gradient(135deg, #0f8a80 0%, #32d972 100%); }
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
      <div class="card">
        <div class="card-body">
          <h5 class="card-title fw-semibold">Hasil Ranking Akhir</h5>
          <p class="mb-4 card-subtitle">Hasil perangkingan kombinasi metode AHP & CoCoSo</p>
          <button class="btn btn-success mb-3"><i class="ti ti-download"></i> Export PDF</button>

          <div class="alert alert-info mb-4">
            <strong>Penjelasan:</strong> Hasil akhir diperoleh dari kombinasi bobot AHP dan perhitungan CoCoSo. Skor AHP dinormalisasi ke skala 0-1 dan digabungkan dengan skor CoCoSo untuk mendapatkan hasil akhir.
          </div>

          <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
              <thead>
                <tr>
                  <th>Peringkat</th>
                  <th>Kode</th>
                  <th>Nama Alternatif</th>
                  <th>Skor AHP</th>
                  <th>Skor CoCoSo</th>
                  <th>Skor Akhir</th>
                  <th>Rekomendasi</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><span class="badge bg-success">1</span></td>
                  <td>A1</td>
                  <td>Bibit Blewah Golden Aroma</td>
                  <td>83.62</td>
                  <td>0.215</td>
                  <td><strong>0.699</strong></td>
                  <td><span class="badge bg-primary">Sangat Direkomendasikan</span></td>
                </tr>
                <tr>
                  <td><span class="badge bg-info">2</span></td>
                  <td>A3</td>
                  <td>Bibit Blewah Sweet Net</td>
                  <td>83.37</td>
                  <td>0.216</td>
                  <td><strong>0.694</strong></td>
                  <td><span class="badge bg-primary">Sangat Direkomendasikan</span></td>
                </tr>
                <tr>
                  <td><span class="badge bg-warning">3</span></td>
                  <td>A5</td>
                  <td>Bibit Blewah Rangipo</td>
                  <td>79.52</td>
                  <td>0.186</td>
                  <td><strong>0.573</strong></td>
                  <td><span class="badge bg-secondary">Direkomendasikan</span></td>
                </tr>
                <tr>
                  <td><span class="badge bg-secondary">4</span></td>
                  <td>A2</td>
                  <td>Bibit Blewah Varietas Aruna</td>
                  <td>79.57</td>
                  <td>0.187</td>
                  <td><strong>0.572</strong></td>
                  <td><span class="badge bg-secondary">Direkomendasikan</span></td>
                </tr>
                <tr>
                  <td><span class="badge bg-dark">5</span></td>
                  <td>A4</td>
                  <td>Bibit Blewah King Blewah</td>
                  <td>78.25</td>
                  <td>0.184</td>
                  <td><strong>0.560</strong></td>
                  <td><span class="badge bg-light text-dark">Cukup</span></td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="row mt-4">
            <div class="col-md-6">
              <div class="card bg-light">
                <div class="card-body">
                  <h6 class="card-title">Hasil AHP</h6>
                  <ol class="mb-0">
                    <li>A1 - Golden Aroma (83.62)</li>
                    <li>A3 - Sweet Net (83.37)</li>
                    <li>A2 - Varietas Aruna (79.57)</li>
                    <li>A5 - Rangipo (79.52)</li>
                    <li>A4 - King Blewah (78.25)</li>
                  </ol>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card bg-light">
                <div class="card-body">
                  <h6 class="card-title">Hasil CoCoSo</h6>
                  <ol class="mb-0">
                    <li>A5 - Rangipo (0.186)</li>
                    <li>A3 - Sweet Net (0.216)</li>
                    <li>A1 - Golden Aroma (0.215)</li>
                    <li>A2 - Varietas Aruna (0.187)</li>
                    <li>A4 - King Blewah (0.184)</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>

          <div class="alert alert-success mt-4">
            <h5 class="alert-heading">Kesimpulan</h5>
            <p class="mb-2"><strong>Bibit Blewah Golden Aroma</strong> merupakan varietas terbaik dengan skor akhir 0.699, diikuti oleh <strong>Bibit Blewah Sweet Net</strong> dengan skor 0.694.</p>
            <p class="mb-0">Kedua varietas ini sangat direkomendasikan untuk dikembangkan karena memiliki kombinasi produktivitas tinggi, kualitas buah baik, dan daya adaptasi yang optimal.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once 'includes/footer.php'; ?>

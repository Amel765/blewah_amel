<?php
require_once 'includes/functions.php';
// check_login();

$pageTitle = "Perhitungan AHP - Spk AhpCocoso";
$currentPage = "ahp";

ob_start();
?>
<style>
    .page-title { font-weight: 700; color: #11998e; }
    .card { border-radius: 15px; border: none; }
    .btn-primary { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: none; border-radius: 10px; }
    .btn-primary:hover { background: linear-gradient(135deg, #0f8a80 0%, #32d972 100%); }
</style>
<?php
$extraStyles = ob_get_clean();

ob_start();
?>
  <script>
    function hitungAHP() {
      document.getElementById('hasilAHP').style.display = 'block';
    }
  </script>
<?php
$extraScripts = ob_get_clean();

include_once 'includes/header.php';
include_once 'includes/sidebar.php';
?>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title fw-semibold">Perhitungan AHP</h5>
          <p class="mb-4 card-subtitle">Analytic Hierarchy Process - Menghitung bobot kriteria berdasarkan perbandingan pairwise</p>
          
          <div class="alert alert-primary mb-4">
            <h6 class="alert-heading">Metode AHP</h6>
            <p class="mb-0">Lakukan perbandingan berpasangan antar kriteria untuk menentukan bobot. Gunakan skala 1-9 dimana:</p>
            <ul class="mb-0 mt-2">
              <li>1 = Sama penting</li>
              <li>3 = Sedikit lebih penting</li>
              <li>5 = Lebih penting</li>
              <li>7 = Sangat lebih penting</li>
              <li>9 = Extrem penting</li>
            </ul>
          </div>

          <button class="btn btn-primary mb-3" onclick="hitungAHP()"><i class="ti ti-calculator"></i> Hitung Bobot AHP</button>

          <div id="hasilAHP" style="display: none;">
            <h6 class="mt-4 mb-3">1. Matriks Perbandingan Berpasangan</h6>
            <div class="table-responsive">
              <table class="table table-bordered table-sm text-center">
                <thead>
                  <tr>
                    <th>Kriteria</th>
                    <th>C1<br>Produktivitas</th>
                    <th>C2<br>Ket. Hama</th>
                    <th>C3<br>Umur Panen</th>
                    <th>C4<br>Kualitas</th>
                    <th>C5<br>Adaptasi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><strong>C1 - Produktivitas</strong></td>
                    <td>1</td>
                    <td>3</td>
                    <td>5</td>
                    <td>2</td>
                    <td>4</td>
                  </tr>
                  <tr>
                    <td><strong>C2 - Ket. Hama</strong></td>
                    <td>0.333</td>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>2</td>
                  </tr>
                  <tr>
                    <td><strong>C3 - Umur Panen</strong></td>
                    <td>0.200</td>
                    <td>0.500</td>
                    <td>1</td>
                    <td>0.333</td>
                    <td>0.500</td>
                  </tr>
                  <tr>
                    <td><strong>C4 - Kualitas</strong></td>
                    <td>0.500</td>
                    <td>0.333</td>
                    <td>3</td>
                    <td>1</td>
                    <td>2</td>
                  </tr>
                  <tr>
                    <td><strong>C5 - Adaptasi</strong></td>
                    <td>0.250</td>
                    <td>0.500</td>
                    <td>2</td>
                    <td>0.500</td>
                    <td>1</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <h6 class="mt-4 mb-3">2. Normalisasi & Bobot Kriteria</h6>
            <div class="table-responsive">
              <table class="table table-bordered table-sm text-center">
                <thead>
                  <tr>
                    <th>Kriteria</th>
                    <th>Jumlah Kolom</th>
                    <th>Bobot (Weight)</th>
                    <th>Priority Rank</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>C1 - Produktivitas</td>
                    <td>15.283</td>
                    <td>0.382</td>
                    <td><span class="badge bg-primary">1</span></td>
                  </tr>
                  <tr>
                    <td>C2 - Ketahanan Hama</td>
                    <td>7.833</td>
                    <td>0.236</td>
                    <td><span class="badge bg-secondary">2</span></td>
                  </tr>
                  <tr>
                    <td>C3 - Umur Panen</td>
                    <td>3.533</td>
                    <td>0.081</td>
                    <td><span class="badge bg-info">5</span></td>
                  </tr>
                  <tr>
                    <td>C4 - Kualitas Buah</td>
                    <td>8.167</td>
                    <td>0.189</td>
                    <td><span class="badge bg-warning">3</span></td>
                  </tr>
                  <tr>
                    <td>C5 - Daya Adaptasi</td>
                    <td>5.750</td>
                    <td>0.112</td>
                    <td><span class="badge bg-dark">4</span></td>
                  </tr>
                </tbody>
              </table>
            </div>

            <h6 class="mt-4 mb-3">3. Konsistensi</h6>
            <div class="row">
              <div class="col-md-4">
                <div class="alert alert-success">
                  <strong>Lambda Max (λ):</strong> 5.234
                </div>
              </div>
              <div class="col-md-4">
                <div class="alert alert-warning">
                  <strong>CI:</strong> 0.058
                </div>
              </div>
              <div class="col-md-4">
                <div class="alert alert-info">
                  <strong>CR:</strong> 0.052 (&lt; 0.1 ✓ Konsisten)
                </div>
              </div>
            </div>

            <h6 class="mt-4 mb-3">4. Nilai Alternatif per Kriteria</h6>
            <div class="table-responsive">
              <table class="table table-bordered table-sm text-center">
                <thead>
                  <tr>
                    <th>Alternatif</th>
                    <th>C1<br>Produktivitas</th>
                    <th>C2<br>Ket. Hama</th>
                    <th>C3<br>Umur Panen</th>
                    <th>C4<br>Kualitas</th>
                    <th>C5<br>Adaptasi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr><td>A1 - Golden Aroma</td><td>85</td><td>80</td><td>75</td><td>90</td><td>82</td></tr>
                  <tr><td>A2 - Varietas Aruna</td><td>78</td><td>85</td><td>70</td><td>75</td><td>88</td></tr>
                  <tr><td>A3 - Sweet Net</td><td>90</td><td>75</td><td>80</td><td>85</td><td>78</td></tr>
                  <tr><td>A4 - King Blewah</td><td>82</td><td>70</td><td>85</td><td>80</td><td>75</td></tr>
                  <tr><td>A5 - Rangipo</td><td>75</td><td>88</td><td>65</td><td>78</td><td>90</td></tr>
                </tbody>
              </table>
            </div>

            <h6 class="mt-4 mb-3">5. Perhitungan Nilai Alternatif</h6>
            <div class="table-responsive">
              <table class="table table-bordered table-sm text-center">
                <thead>
                  <tr>
                    <th>Alternatif</th>
                    <th>C1 (0.382)</th>
                    <th>C2 (0.236)</th>
                    <th>C3 (0.081)</th>
                    <th>C4 (0.189)</th>
                    <th>C5 (0.112)</th>
                    <th><strong>Nilai Total</strong></th>
                  </tr>
                </thead>
                <tbody>
                  <tr><td>A1 - Golden Aroma</td><td>32.47</td><td>18.88</td><td>6.08</td><td>17.01</td><td>9.18</td><td><strong>83.62</strong></td></tr>
                  <tr><td>A2 - Varietas Aruna</td><td>29.80</td><td>20.06</td><td>5.67</td><td>14.18</td><td>9.86</td><td><strong>79.57</strong></td></tr>
                  <tr><td>A3 - Sweet Net</td><td>34.38</td><td>17.70</td><td>6.48</td><td>16.07</td><td>8.74</td><td><strong>83.37</strong></td></tr>
                  <tr><td>A4 - King Blewah</td><td>31.32</td><td>16.52</td><td>6.89</td><td>15.12</td><td>8.40</td><td><strong>78.25</strong></td></tr>
                  <tr><td>A5 - Rangipo</td><td>28.65</td><td>20.78</td><td>5.27</td><td>14.74</td><td>10.08</td><td><strong>79.52</strong></td></tr>
                </tbody>
              </table>
            </div>

            <div class="alert alert-success mt-4">
              <h5 class="alert-heading">Hasil Perankingan AHP</h5>
              <ol class="mb-0">
                <li><strong>A1 - Bibit Blewah Golden Aroma</strong> : 83.62</li>
                <li><strong>A3 - Bibit Blewah Sweet Net</strong> : 83.37</li>
                <li><strong>A2 - Bibit Blewah Varietas Aruna</strong> : 79.57</li>
                <li><strong>A5 - Bibit Blewah Rangipo</strong> : 79.52</li>
                <li><strong>A4 - Bibit Blewah King Blewah</strong> : 78.25</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once 'includes/footer.php'; ?>

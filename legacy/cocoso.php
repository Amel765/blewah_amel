<?php
require_once 'includes/functions.php';
// check_login();

$pageTitle = "Perhitungan CoCoSo - Spk AhpCocoso";
$currentPage = "cocoso";

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
    function hitungCoCoSo() {
      document.getElementById('hasilCoCoSo').style.display = 'block';
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
          <h5 class="card-title fw-semibold">Perhitungan CoCoSo</h5>
          <p class="mb-4 card-subtitle">Combined Compromise Solution - Perangkingan alternatif menggunakan kombinasi 3 strategi</p>
          
          <div class="alert alert-primary mb-4">
            <h6 class="alert-heading">Tentang Metode CoCoSo</h6>
            <p class="mb-0">CoCoSo menggabungkan 3 strategi:</p>
            <ul class="mb-0 mt-2">
              <li><strong>Si (Strategy Index)</strong>: Menjumlahkan nilai normalisasi terbobot</li>
              <li><strong>Pi (Proximity Index)</strong>: Mengukur kedekatan dengan solusi ideal</li>
              <li><strong>Qi (Final Score)</strong>: Kombinasi dari Si dan Pi dengan bobot seimbang</li>
            </ul>
          </div>

          <button class="btn btn-primary mb-3" onclick="hitungCoCoSo()"><i class="ti ti-calculator"></i> Hitung CoCoSo</button>

          <div id="hasilCoCoSo" style="display: none;">
            <h6 class="mt-4 mb-3">1. Matriks Keputusan (Data Original)</h6>
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

            <h6 class="mt-4 mb-3">2. Normalisasi Matriks (Min-Max)</h6>
            <div class="table-responsive">
              <table class="table table-bordered table-sm text-center">
                <thead>
                  <tr>
                    <th>Alternatif</th>
                    <th>C1</th>
                    <th>C2</th>
                    <th>C3</th>
                    <th>C4</th>
                    <th>C5</th>
                  </tr>
                </thead>
                <tbody>
                  <tr><td>A1 - Golden Aroma</td><td>0.667</td><td>0.556</td><td>0.500</td><td>1.000</td><td>0.680</td></tr>
                  <tr><td>A2 - Varietas Aruna</td><td>0.200</td><td>0.833</td><td>0.250</td><td>0.000</td><td>0.920</td></tr>
                  <tr><td>A3 - Sweet Net</td><td>1.000</td><td>0.278</td><td>0.750</td><td>0.667</td><td>0.520</td></tr>
                  <tr><td>A4 - King Blewah</td><td>0.467</td><td>0.000</td><td>1.000</td><td>0.333</td><td>0.400</td></tr>
                  <tr><td>A5 - Rangipo</td><td>0.000</td><td>1.000</td><td>0.000</td><td>0.200</td><td>1.000</td></tr>
                </tbody>
              </table>
            </div>

            <h6 class="mt-4 mb-3">3. Normalisasi Terbobot (Bobot AHP: C1=0.382, C2=0.236, C3=0.081, C4=0.189, C5=0.112)</h6>
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
                  </tr>
                </thead>
                <tbody>
                  <tr><td>A1 - Golden Aroma</td><td>0.255</td><td>0.131</td><td>0.041</td><td>0.189</td><td>0.076</td></tr>
                  <tr><td>A2 - Varietas Aruna</td><td>0.076</td><td>0.197</td><td>0.020</td><td>0.000</td><td>0.103</td></tr>
                  <tr><td>A3 - Sweet Net</td><td>0.382</td><td>0.066</td><td>0.061</td><td>0.126</td><td>0.058</td></tr>
                  <tr><td>A4 - King Blewah</td><td>0.178</td><td>0.000</td><td>0.081</td><td>0.063</td><td>0.045</td></tr>
                  <tr><td>A5 - Rangipo</td><td>0.000</td><td>0.236</td><td>0.000</td><td>0.038</td><td>0.112</td></tr>
                </tbody>
              </table>
            </div>

            <h6 class="mt-4 mb-3">4. Perhitungan Si, Pi, dan Qi</h6>
            <div class="table-responsive">
              <table class="table table-bordered table-sm text-center">
                <thead>
                  <tr>
                    <th>Alternatif</th>
                    <th>Si</th>
                    <th>Si'</th>
                    <th>Pi</th>
                    <th>Pi'</th>
                    <th>Qi</th>
                    <th>Peringkat</th>
                  </tr>
                </thead>
                <tbody>
                  <tr><td>A1 - Golden Aroma</td><td>0.692</td><td>0.233</td><td>0.591</td><td>0.198</td><td><strong>0.215</strong></td><td><span class="badge bg-primary">3</span></td></tr>
                  <tr><td>A2 - Varietas Aruna</td><td>0.396</td><td>0.133</td><td>0.716</td><td>0.240</td><td><strong>0.187</strong></td><td><span class="badge bg-secondary">4</span></td></tr>
                  <tr><td>A3 - Sweet Net</td><td>0.693</td><td>0.233</td><td>0.590</td><td>0.198</td><td><strong>0.216</strong></td><td><span class="badge bg-success">2</span></td></tr>
                  <tr><td>A4 - King Blewah</td><td>0.367</td><td>0.124</td><td>0.732</td><td>0.245</td><td><strong>0.184</strong></td><td><span class="badge bg-warning">5</span></td></tr>
                  <tr><td>A5 - Rangipo</td><td>0.386</td><td>0.130</td><td>0.722</td><td>0.242</td><td><strong>0.186</strong></td><td><span class="badge bg-info">1</span></td></tr>
                </tbody>
              </table>
            </div>

            <div class="alert alert-success mt-4">
              <h5 class="alert-heading">Hasil Perankingan CoCoSo</h5>
              <ol class="mb-0">
                <li><strong>A5 - Bibit Blewah Rangipo</strong> : 0.186 (Rank 1)</li>
                <li><strong>A3 - Bibit Blewah Sweet Net</strong> : 0.216 (Rank 2)</li>
                <li><strong>A1 - Bibit Blewah Golden Aroma</strong> : 0.215 (Rank 3)</li>
                <li><strong>A2 - Varietas Aruna</strong> : 0.187 (Rank 4)</li>
                <li><strong>A4 - King Blewah</strong> : 0.184 (Rank 5)</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once 'includes/footer.php'; ?>

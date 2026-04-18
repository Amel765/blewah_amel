<?php
require_once 'includes/functions.php';
// check_login(); // Uncomment this once login logic is ready

$pageTitle = "Spk AhpCocoso - Dashboard";
$currentPage = "index";

ob_start();
?>
<style>
    .hero-section {
      background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
      border-radius: 20px;
      position: relative;
      overflow: hidden;
    }
    .hero-section::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -20%;
      width: 300px;
      height: 300px;
      background: rgba(255,255,255,0.1);
      border-radius: 50%;
    }
    .hero-section::after {
      content: '';
      position: absolute;
      bottom: -30%;
      left: 10%;
      width: 200px;
      height: 200px;
      background: rgba(255,255,255,0.05);
      border-radius: 50%;
    }
    
    .stat-card {
      border-radius: 20px;
      transition: all 0.3s ease;
      border: none;
    }
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    }
    .stat-icon {
      width: 70px;
      height: 70px;
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .cta-card {
      border-radius: 20px;
      background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    .cta-btn {
      background: white;
      color: #11998e;
      border-radius: 12px;
      font-weight: 600;
      padding: 12px 30px;
      transition: all 0.3s ease;
    }
    .cta-btn:hover {
      transform: scale(1.05);
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .counter {
      font-size: 2.5rem;
      font-weight: 700;
      background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    
    .feature-box {
      border-radius: 16px;
      padding: 20px;
      background: white;
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
      transition: all 0.3s ease;
    }
    .feature-box:hover {
      transform: translateY(-3px);
    }
    .feature-box:hover h6 { color: #11998e; }
</style>
<?php
$extraStyles = ob_get_clean();

ob_start();
?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const counters = document.querySelectorAll('.counter');
      const duration = 1500;
      counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-count'));
        const increment = target / (duration / 16);
        let current = 0;
        const updateCounter = () => {
          current += increment;
          if (current < target) {
            counter.textContent = Math.ceil(current);
            requestAnimationFrame(updateCounter);
          } else {
            counter.textContent = target;
          }
        };
        setTimeout(updateCounter, 500);
      });
    });
  </script>
<?php
$extraScripts = ob_get_clean();

include_once 'includes/header.php';
include_once 'includes/sidebar.php';
?>

<div class="container-fluid">
  <div class="row mt-4">
    <div class="col-12">
      <div class="card hero-section shadow-lg border-0">
        <div class="card-body p-5 text-white position-relative">
          <div class="row align-items-center">
            <div class="col-lg-8">
              <h1 class="fw-bold mb-3 display-4" style="text-shadow: 2px 2px 10px rgba(0,0,0,0.3);">Spk AhpCocoso</h1>
              <p class="fs-5 mb-3 opacity-90">Sistem Pendukung Keputusan Pemilihan Bibit Blewah</p>
              <div class="d-flex flex-wrap gap-2 mt-4">
                <span class="badge bg-white bg-opacity-25 px-4 py-2 fs-6">AHP</span>
                <span class="badge bg-white bg-opacity-25 px-4 py-2 fs-6">CoCoSo</span>
                <span class="badge bg-white bg-opacity-25 px-4 py-2 fs-6">Multi-Kriteria</span>
              </div>
            </div>
            <div class="col-lg-4 text-end d-none d-lg-block">
              <i class="ti ti-plant-2" style="font-size: 150px; opacity: 0.15;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="row mt-4 g-4">
    <div class="col-md-6">
      <div class="card stat-card shadow-sm h-100">
        <div class="card-body p-4">
          <div class="d-flex align-items-center">
            <div class="stat-icon bg-success-subtle">
              <i class="ti ti-list-check text-success fs-1"></i>
            </div>
            <div class="ms-4">
              <p class="text-muted mb-1 text-uppercase" style="font-size: 0.75rem; font-weight: 600; letter-spacing: 1px;">Jumlah Kriteria</p>
              <h2 class="counter mb-0" data-count="5">0</h2>
              <p class="text-muted small mb-0">Kriteria Penilaian</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card stat-card shadow-sm h-100">
        <div class="card-body p-4">
          <div class="d-flex align-items-center">
            <div class="stat-icon bg-info-subtle">
              <i class="ti ti-leaf text-info fs-1"></i>
            </div>
            <div class="ms-4">
              <p class="text-muted mb-1 text-uppercase" style="font-size: 0.75rem; font-weight: 600; letter-spacing: 1px;">Jumlah Alternatif</p>
              <h2 class="counter mb-0" data-count="5">0</h2>
              <p class="text-muted small mb-0">Varietas Bibit</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="row mt-4 g-4">
    <div class="col-md-4">
      <a href="kriteria.php" class="text-decoration-none">
        <div class="feature-box" style="cursor: pointer;">
          <div class="d-flex align-items-center mb-3">
            <div class="bg-success rounded-circle p-2 me-3">
              <i class="ti ti-list-check text-white"></i>
            </div>
            <h6 class="mb-0 fw-bold text-dark">5 Kriteria</h6>
          </div>
          <p class="text-muted small mb-0">Produktivitas, Ketahanan Hama, Umur Panen, Kualitas Buah, Daya Adaptasi</p>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a href="alternatif.php" class="text-decoration-none">
        <div class="feature-box" style="cursor: pointer;">
          <div class="d-flex align-items-center mb-3">
            <div class="bg-info rounded-circle p-2 me-3">
              <i class="ti ti-leaf text-white"></i>
            </div>
            <h6 class="mb-0 fw-bold text-dark">5 Alternatif</h6>
          </div>
          <p class="text-muted small mb-0">Golden Aroma, Aruna, Sweet Net, King Blewah, Rangipo</p>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a href="nilai.php" class="text-decoration-none">
        <div class="feature-box" style="cursor: pointer;">
          <div class="d-flex align-items-center mb-3">
            <div class="bg-warning rounded-circle p-2 me-3">
              <i class="ti ti-calculator text-white"></i>
            </div>
            <h6 class="mb-0 fw-bold text-dark">Data Penilaian</h6>
          </div>
          <p class="text-muted small mb-0">Nilai setiap alternatif berdasarkan kriteria penilaian</p>
        </div>
      </a>
    </div>
  </div>
  
  <div class="row mt-4">
    <div class="col-12">
      <div class="card cta-card shadow-lg border-0">
        <div class="card-body p-5">
          <div class="row align-items-center">
            <div class="col-lg-8">
              <h4 class="fw-bold text-white mb-2">Mulai Perhitungan SPK</h4>
              <p class="text-white mb-0 opacity-75">Proses perhitungan menggunakan metode AHP dan CoCoSo untuk mendapatkan rekomendasi terbaik</p>
            </div>
            <div class="col-lg-4 text-end">
              <a href="ahp.php" class="cta-btn text-decoration-none">
                <i class="ti ti-calculator me-2"></i> Mulai Sekarang
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="row mt-4 mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
        <div class="card-body p-4 text-center">
          <p class="mb-1 text-muted"><strong>Spk AhpCocoso</strong> &copy; <?php echo date('Y'); ?></p>
          <p class="mb-0 text-muted small">Sistem Pendukung Keputusan Pemilihan Bibit Blewah</p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once 'includes/footer.php'; ?>

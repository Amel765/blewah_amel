<?php
require_once 'includes/functions.php';
// check_login();

$pageTitle = "Profile - Spk AhpCocoso";
$currentPage = "profile";

ob_start();
?>
<style>
    .page-title { font-weight: 700; color: #11998e; }
    .card { border-radius: 15px; border: none; }
    .btn-primary { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: none; border-radius: 10px; }
    .btn-primary:hover { background: linear-gradient(135deg, #0f8a80 0%, #32d972 100%); }
    .profile-avatar {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 48px;
      color: white;
    }
    .form-control { border-radius: 10px; border: 2px solid #e9ecef; padding: 12px; }
    .form-control:focus { border-color: #38ef7d; box-shadow: 0 0 0 4px rgba(56, 239, 125, 0.1); }
</style>
<?php
$extraStyles = ob_get_clean();

include_once 'includes/header.php';
include_once 'includes/sidebar.php';
?>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-body p-5">
          <div class="text-center mb-4">
            <div class="profile-avatar mx-auto mb-3">
              <i class="ti ti-user"></i>
            </div>
            <h4 class="fw-bold">Profile User</h4>
            <p class="text-muted">Kelola informasi profile Anda</p>
          </div>
          <form>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-medium">Username</label>
                <input type="text" class="form-control" value="admin" readonly>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-medium">Nama Lengkap</label>
                <input type="text" class="form-control" value="Administrator">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-medium">Email</label>
                <input type="email" class="form-control" value="admin@spk-ahpcocoso.com">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-medium">No. Telepon</label>
                <input type="text" class="form-control" value="081234567890">
              </div>
              <div class="col-md-12 mb-4">
                <label class="form-label fw-medium">Alamat</label>
                <textarea class="form-control" rows="3">Jl. Raya Blewah No. 123, Indonesia</textarea>
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary px-5 py-2">
                <i class="ti ti-device-floppy me-2"></i> Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once 'includes/footer.php'; ?>

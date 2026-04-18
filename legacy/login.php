<?php
require_once 'includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Basic logic for demonstration (clean code structure)
    // In a real app, this would check against the database
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['user_id'] = 1;
        $_SESSION['username'] = 'admin';
        header("Location: index.php");
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Spk AhpCocoso</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { font-family: 'Poppins', sans-serif; }
    .login-wrapper {
      background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
      min-height: 100vh;
      position: relative;
      overflow: hidden;
    }
    .login-wrapper::before {
      content: '';
      position: absolute;
      top: -10%;
      right: -10%;
      width: 400px;
      height: 400px;
      background: rgba(255,255,255,0.1);
      border-radius: 50%;
    }
    .login-wrapper::after {
      content: '';
      position: absolute;
      bottom: -20%;
      left: -10%;
      width: 500px;
      height: 500px;
      background: rgba(255,255,255,0.05);
      border-radius: 50%;
    }
    .login-card {
      border-radius: 25px;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255,255,255,0.2);
    }
    .form-control {
      border-radius: 12px;
      padding: 12px 15px;
      border: 2px solid #e9ecef;
      transition: all 0.3s ease;
    }
    .form-control:focus {
      border-color: #38ef7d;
      box-shadow: 0 0 0 4px rgba(56, 239, 125, 0.1);
    }
    .btn-login {
      background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
      border-radius: 12px;
      padding: 14px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 30px rgba(17, 153, 142, 0.4);
    }
  </style>
</head>

<body>
  <div class="page-wrapper" id="main-wrapper">
    <div class="login-wrapper d-flex align-items-center justify-content-center min-vh-100 position-relative">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-7 col-lg-5 col-xl-4">
            <div class="card login-card shadow-lg border-0 bg-white bg-opacity-95">
              <div class="card-body p-5">
                <div class="text-center mb-4">
                  <img src="assets/images/logos/dark-logo.svg?v=1" width="160" alt="SPK" class="mb-3">
                  <p class="text-muted small">Sistem Pendukung Keputusan Pemilihan Bibit Blewah</p>
                </div>
                
                <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                  <div class="mb-3">
                    <label class="form-label fw-medium">Username</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0">
                        <i class="ti ti-user"></i>
                      </span>
                      <input type="text" class="form-control border-start-0" name="username" id="username" placeholder="Masukkan username" required>
                    </div>
                  </div>
                  <div class="mb-4">
                    <label class="form-label fw-medium">Password</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0">
                        <i class="ti ti-lock"></i>
                      </span>
                      <input type="password" class="form-control border-start-0" name="password" id="password" placeholder="Masukkan password" required>
                    </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                      <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked">
                      <label class="form-check-label text-dark small" for="flexCheckChecked">Ingat saya</label>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary w-100 btn-login text-white">
                    <i class="ti ti-login me-2"></i> MASUK
                  </button>
                </form>
                <div class="text-center mt-4">
                  <p class="text-muted small mb-0">Gunakan akun untuk mengakses sistem</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

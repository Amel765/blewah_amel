    <aside class="left-sidebar">
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="index.php" class="text-nowrap logo-img">
            <img src="assets/images/logos/dark-logo.svg?v=1" width="180" alt="SPK" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">DASHBOARD</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link <?php echo ($currentPage == 'index') ? 'active' : ''; ?>" href="index.php" aria-expanded="false">
                <span><i class="ti ti-layout-dashboard"></i></span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">MASTER DATA</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link <?php echo ($currentPage == 'kriteria') ? 'active' : ''; ?>" href="kriteria.php" aria-expanded="false">
                <span><i class="ti ti-list-check"></i></span>
                <span class="hide-menu">Data Kriteria</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link <?php echo ($currentPage == 'alternatif') ? 'active' : ''; ?>" href="alternatif.php" aria-expanded="false">
                <span><i class="ti ti-leaf"></i></span>
                <span class="hide-menu">Data Alternatif</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link <?php echo ($currentPage == 'nilai') ? 'active' : ''; ?>" href="nilai.php" aria-expanded="false">
                <span><i class="ti ti-table"></i></span>
                <span class="hide-menu">Data Penilaian</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">PROSES</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link <?php echo ($currentPage == 'ahp') ? 'active' : ''; ?>" href="ahp.php" aria-expanded="false">
                <span><i class="ti ti-calculator"></i></span>
                <span class="hide-menu">Perhitungan AHP</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link <?php echo ($currentPage == 'cocoso') ? 'active' : ''; ?>" href="cocoso.php" aria-expanded="false">
                <span><i class="ti ti-math-function"></i></span>
                <span class="hide-menu">Perhitungan CoCoSo</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">HASIL</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link <?php echo ($currentPage == 'ranking') ? 'active' : ''; ?>" href="ranking.php" aria-expanded="false">
                <span><i class="ti ti-trophy"></i></span>
                <span class="hide-menu">Hasil Ranking</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">USER</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link <?php echo ($currentPage == 'user') ? 'active' : ''; ?>" href="user.php" aria-expanded="false">
                <span><i class="ti ti-users"></i></span>
                <span class="hide-menu">Data User</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link <?php echo ($currentPage == 'profile') ? 'active' : ''; ?>" href="profile.php" aria-expanded="false">
                <span><i class="ti ti-user"></i></span>
                <span class="hide-menu">Profile</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">KELUAR</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="logout.php" aria-expanded="false">
                <span><i class="ti ti-logout"></i></span>
                <span class="hide-menu">Logout</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </aside>
    <div class="body-wrapper">
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown">
                   <img src="assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="profile.php" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">Profile</p>
                    </a>
                    <a href="logout.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>

<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo isset($pageTitle) ? $pageTitle : 'Spk AhpCocoso'; ?></title>
  <link rel="shortcut icon" type="image/svg+xml" href="assets/images/logos/dark-logo.svg?v=2" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { font-family: 'Poppins', sans-serif; }
    body { background-color: #f4f6f9; }
    
    /* Sidebar active/hover - Green theme */
    .sidebar-link:hover, .sidebar-link.active {
      background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
      color: white !important;
    }
    .sidebar-link:hover .ti, .sidebar-link.active .ti {
      color: white !important;
    }

    /* Page specific styles can be added in pages or via $extraStyles */
    <?php echo isset($extraStyles) ? $extraStyles : ''; ?>
  </style>
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">

<?php
$imgPrefix = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false || strpos($_SERVER['PHP_SELF'], '/farmer/') !== false || strpos($_SERVER['PHP_SELF'], '/user/') !== false || strpos($_SERVER['PHP_SELF'], '/auth/') !== false) ? '../' : '';
?>

<!-- Bootstrap + Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top shadow-sm" style="padding-top: 1rem; padding-bottom: 1rem;">
  <div class="container-fluid px-5">


    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="<?= $imgPrefix ?>index.php">
       <span class="fw-bold">DrukAgro</span>
    </a>

    <!-- Toggle -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Links -->
    <div class="collapse navbar-collapse" id="mainNavbar">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
   <li class="nav-item"><a class="nav-link fw-bold" href="<?= $imgPrefix ?>index.php#about">About Us</a></li>
  <li class="nav-item"><a class="nav-link fw-bold" href="<?= $imgPrefix ?>index.php#contact">Contact Us</a></li>
</ul>


      <!-- Right-side Buttons -->
      <div class="d-flex align-items-center gap-2">
        <?php if (isset($_SESSION['user_id'])): ?>
          <?php if ($_SESSION['role'] === 'user'): ?>
            <a href="<?= $imgPrefix ?>user/dashboard.php" class="btn btn-outline-light me-2">
              <i class="bi bi-house-door me-1"></i>User Dashboard
            </a>
            <a href="<?= $imgPrefix ?>user/cart.php" class="btn btn-light">
              <i class="bi bi-cart3"></i>
            </a>
          <?php elseif ($_SESSION['role'] === 'farmer'): ?>
            <a href="<?= $imgPrefix ?>farmer/dashboard.php" class="btn btn-outline-light">
              <i class="bi bi-box-seam me-1"></i>Farmer Panel
            </a>
          <?php elseif ($_SESSION['role'] === 'admin'): ?>
            <a href="<?= $imgPrefix ?>admin/dashboard.php" class="btn btn-outline-light">
              <i class="bi bi-shield-lock me-1"></i>Admin Panel
            </a>
          <?php endif; ?>
          <a href="<?= $imgPrefix ?>logout.php" class="btn btn-light">Logout</a>
        <?php else: ?>
          <a href="<?= $imgPrefix ?>auth/login.php" class="btn btn-outline-light">Login</a>
          <a href="<?= $imgPrefix ?>auth/register.php" class="btn btn-light">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

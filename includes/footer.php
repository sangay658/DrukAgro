<?php
$imgPrefix = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false || strpos($_SERVER['PHP_SELF'], '/farmer/') !== false || strpos($_SERVER['PHP_SELF'], '/user/') !== false || strpos($_SERVER['PHP_SELF'], '/auth/') !== false) ? '../' : '';
?>

<footer class="bg-dark text-white pt-4 mt-5">
  <div class="container">
    <div class="row text-center text-md-start">
      <!-- Contact Info -->
      <div class="col-md-4 mb-4">
        <h5 class="text-uppercase fw-bold mb-3">Contact Us</h5>
        <p><i class="bi bi-telephone-fill me-2"></i>+975-12345678</p>
        <p><i class="bi bi-envelope-fill me-2"></i>support@drukagro.com</p>
        <p><i class="bi bi-geo-alt-fill me-2"></i>Thimphu, Bhutan</p>
      </div>

      <!-- Quick Links -->
      <div class="col-md-4 mb-4">
        <h5 class="text-uppercase fw-bold mb-3">Quick Links</h5>
        <ul class="list-unstyled">
          <li><a href="<?= $imgPrefix ?>index.php#about" class="text-white text-decoration-none">About Us</a></li>
          <li><a href="<?= $imgPrefix ?>index.php#contact" class="text-white text-decoration-none">Contact Us</a></li>
          <li><a href="<?= $imgPrefix ?>auth/login.php" class="text-white text-decoration-none">Login</a></li>
        </ul>
      </div>

      <!-- Developers -->
      <div class="col-md-4 mb-4 text-center text-md-start">
        <h5 class="text-uppercase fw-bold mb-3">Developed By</h5>
        <p class="small">Karma Choden, Karma Choki, Sangay Lhamo</p>
        <p class="small mb-0">MIT122 - Interactive Web Design & Development</p>
      </div>
    </div>
    <div class="text-center mt-3 border-top border-secondary pt-3">
      <small>&copy; <?= date("Y") ?> Druk Agro. All rights reserved.</small>
    </div>
  </div>
</footer>

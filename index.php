
<?php include("includes/db.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Druk Agro - Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php include("includes/header.php"); ?>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      scroll-behavior: smooth;
    }
    .hero {
  position: relative;
  background: url('assets/images/hero.jpg') no-repeat center center/cover;
  height: 60vh;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  text-align: center;
  text-shadow: 0 2px 5px rgba(0, 0, 0, 0.6);
 }


.hero::before {
  content: "";
  position: absolute;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.4); /* Dark overlay */
  z-index: 1;
}

.hero > div {
  position: relative;
  z-index: 2;
}

    .hero h1 {
      font-size: 3rem;
    }
    .hero-btn {
      margin-top: 20px;
    }
    .section-title {
   
  margin-bottom: 30px;
  text-align: center;
  font-weight: bold;
  color: #198754;
}

    .contact-info i {
      color: #198754;
    }
    [id] {
  scroll-margin-top: 100px; /* adjust based on your navbar height */
}

  </style>
</head>
<body>

<!-- Hero Section -->
<div class="hero text-center">
  <div>
    <h1>Fresh From Farms</h1>
    <a href="#items" class="btn btn-light btn-lg hero-btn">
      <i class="bi bi-bag-fill me-2"></i>Shop Now
    </a>
  </div>
</div>
<!-- Decorative Divider -->
<div class="container">
  <hr class="my-5" style="border: none; height: 4px; background: linear-gradient(to right, #198754 0%, #ffffff 50%, #198754 100%); border-radius: 5px;">
</div>

<!-- Product Listing -->
<div class="container py-5" id="items">
  <h2 class="section-title">Our Vegetables</h2>
  <div class="row g-4">
    <?php
    $veg = $conn->query("SELECT v.*, u.name AS farmer_name FROM vegetables v JOIN users u ON v.farmer_id = u.id");
    while ($row = $veg->fetch_assoc()):
    ?>
    <div class="col-md-4">
      <div class="card shadow-sm">
        <img src="assets/images/<?= $row['image'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
          <p class="card-text">
            <b>Price:</b> Nu.<?= $row['price'] ?>/kg<br>
            <b>Available:</b> <?= $row['quantity'] ?> kg<br>
            <small class="text-muted">By <?= $row['farmer_name'] ?></small>
          </p>
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'user'): ?>
            <form method="post" action="user/cart.php" class="d-flex">
              <input type="hidden" name="veg_id" value="<?= $row['id'] ?>">
              <input type="number" name="quantity" min="1" max="<?= $row['quantity'] ?>" value="1" class="form-control me-2" required>
              <button class="btn btn-success"><i class="bi bi-cart-plus me-1"></i>Add</button>
            </form>
          <?php else: ?>
            <a href="auth/login.php" class="btn btn-outline-primary w-100">Login to Purchase</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</div>
<!-- Fading Divider -->
<div class="container">
  <hr style="
    height: 3px;
    border: none;
    background: linear-gradient(to right, rgba(25,135,84,0), rgba(25,135,84,0.5), rgba(25,135,84,0));
    margin: 40px 0;
    border-radius: 10px;
  ">
</div>

<!-- About Us -->
<div class="container py-5" id="about" style="background-color: #f8fdf8; border-radius: 10px;">
  <h2 class="section-title">About Us</h2>
  <p class="text-center px-5">
    Druk Agro is a platform dedicated to supporting Bhutanese farmers by connecting them directly with customers.
    Our mission is to eliminate middlemen, provide fresh vegetables at fair prices, and promote sustainable local agriculture.
  </p>
</div>
<!-- Fading Divider -->
<div class="container">
  <hr style="
    height: 3px;
    border: none;
    background: linear-gradient(to right, rgba(25,135,84,0), rgba(25,135,84,0.5), rgba(25,135,84,0));
    margin: 40px 0;
    border-radius: 10px;
  ">
</div>

<!-- Contact Us -->
<div class="container py-5" id="contact">
  <h2 class="section-title">Contact Us</h2>
  <div class="row text-center contact-info">
    <div class="col-md-4 mb-3">
      <i class="bi bi-telephone-fill fs-4"></i>
      <p class="mb-0">+975-12345678</p>
    </div>
    <div class="col-md-4 mb-3">
      <i class="bi bi-envelope-fill fs-4"></i>
      <p class="mb-0">support@drukagro.com</p>
    </div>
    <div class="col-md-4 mb-3">
      <i class="bi bi-geo-alt-fill fs-4"></i>
      <p class="mb-0">Thimphu, Bhutan</p>
    </div>
  </div>
</div>

<?php include("includes/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

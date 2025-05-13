<?php
include("../includes/db.php");

// Only allow users to access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
  header("Location: ../auth/login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard - Druk Agro</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php include("../includes/header.php"); ?>
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .veg-card {
      transition: 0.3s;
    }
    .veg-card:hover {
      transform: scale(1.02);
      box-shadow: 0 0 12px rgba(0,0,0,0.15);
    }
    html, body {
  height: 100%;
  margin: 0;
  display: flex;
  flex-direction: column;
}

main {
  flex: 1;
}

  </style>
</head>
<body>
<main class="container py-5">
   <h3 class="text-center text-success mb-4">Welcome, <?= $_SESSION['role'] ?>! Browse Fresh Vegetables</h3>

  <div class="row g-4">
    <?php
    $veg = $conn->query("SELECT v.*, u.name AS farmer_name FROM vegetables v JOIN users u ON v.farmer_id = u.id");
    while ($row = $veg->fetch_assoc()):
    ?>
    <div class="col-md-4">
      <div class="card veg-card">
        <img src="../assets/images/<?= $row['image'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
          <p class="card-text">
            <b>Price:</b> Nu.<?= $row['price'] ?>/kg<br>
            <b>Stock:</b> <?= $row['quantity'] ?> kg<br>
            <small class="text-muted">Farmer: <?= $row['farmer_name'] ?></small>
          </p>
          <form method="post" action="cart.php" class="d-flex">
  <input type="hidden" name="veg_id" value="<?= $row['id'] ?>">
  <input type="number" name="quantity" min="1" max="<?= $row['quantity'] ?>" value="1" class="form-control me-2" required>
  <button type="submit" name="add" class="btn btn-success">
    <i class="bi bi-cart-plus me-1"></i>Add
  </button>
</form>

        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
     </main>
<?php include("../includes/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

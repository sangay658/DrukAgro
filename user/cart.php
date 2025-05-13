<?php
include("../includes/db.php");
 

// Ensure user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
  header("Location: ../auth/login.php");
  exit;
}

// Handle add to cart logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['veg_id'], $_POST['quantity'])) {
  $user_id = $_SESSION['user_id'];
  $veg_id = intval($_POST['veg_id']);
  $qty = intval($_POST['quantity']);

  // Check if already in cart — update quantity if so
  $check = $conn->query("SELECT id, quantity FROM cart WHERE user_id=$user_id AND veg_id=$veg_id");
  if ($check->num_rows > 0) {
    $existing = $check->fetch_assoc();
    $newQty = $existing['quantity'] + $qty;
    $conn->query("UPDATE cart SET quantity = $newQty WHERE id = {$existing['id']}");
  } else {
    $conn->query("INSERT INTO cart (user_id, veg_id, quantity) VALUES ($user_id, $veg_id, $qty)");
  }

  // Redirect back to dashboard
  header("Location: dashboard.php?cart=added");
  exit;
}

// Handle item removal
if (isset($_GET['remove'])) {
  $cart_id = intval($_GET['remove']);
  $conn->query("DELETE FROM cart WHERE id = $cart_id AND user_id = {$_SESSION['user_id']}");
}

// Fetch cart items
$user_id = $_SESSION['user_id'];
$sql = "SELECT c.id AS cart_id, v.name, v.price, c.quantity, v.image
        FROM cart c
        JOIN vegetables v ON c.veg_id = v.id
        WHERE c.user_id = $user_id";
$cartItems = $conn->query($sql);

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Cart - Druk Agro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; font-family: Arial, sans-serif; }
    .cart-item img { height: 80px; width: 80px; object-fit: cover; }
    .cart-summary { font-size: 1.1rem; }
  </style>
</head>
<body>

<?php include("../includes/header.php"); ?>

<div class="container mt-5">
  <h3 class="mb-4 text-success text-center">Your Cart</h3>
  <?php if ($cartItems->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-success">
          <tr>
            <th>Item</th>
            <th>Price (Nu.)</th>
            <th>Quantity</th>
            <th>Total (Nu.)</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php while($item = $cartItems->fetch_assoc()): 
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
          ?>
          <tr>
            <td class="cart-item d-flex align-items-center">
              <img src="../assets/images/<?= $item['image'] ?>" class="me-3 rounded">
              <?= htmlspecialchars($item['name']) ?>
            </td>
            <td><?= $item['price'] ?></td>
            <td><?= $item['quantity'] ?></td>
            <td><?= number_format($subtotal, 2) ?></td>
            <td>
              <a href="?remove=<?= $item['cart_id'] ?>" class="btn btn-sm btn-danger">Remove</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <div class="text-end cart-summary">
      <strong>Grand Total: Nu. <?= number_format($total, 2) ?></strong>
    </div>
    <div class="text-end mt-3">
      <a href="purchase.php" class="btn btn-success">Proceed to Purchase</a>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">Your cart is empty.</div>
  <?php endif; ?>
</div>

<?php include("../includes/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php
include("../includes/db.php");

// Redirect if not logged in as user
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = false;
$totalCost = 0;

// Fetch cart items
$cartItems = $conn->query("SELECT c.*, v.farmer_id, v.price, v.quantity AS stock FROM cart c 
                           JOIN vegetables v ON c.veg_id = v.id 
                           WHERE c.user_id = $user_id");

// Process purchase
if ($cartItems->num_rows > 0) {
    while ($item = $cartItems->fetch_assoc()) {
        $veg_id = $item['veg_id'];
        $farmer_id = $item['farmer_id'];
        $qty = $item['quantity'];
        $price = $item['price'];
        $stock = $item['stock'];
        $total = $qty * $price;
        $totalCost += $total;

        if ($qty <= $stock) {
            // Insert order
            $stmt = $conn->prepare("INSERT INTO orders (user_id, veg_id, farmer_id, quantity, total_price) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiidd", $user_id, $veg_id, $farmer_id, $qty, $total);
            $stmt->execute();

            // Reduce stock
            $conn->query("UPDATE vegetables SET quantity = quantity - $qty WHERE id = $veg_id");
        }
    }

    // Empty the cart
    $conn->query("DELETE FROM cart WHERE user_id = $user_id");

    $success = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Purchase - Druk Agro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; font-family: Arial, sans-serif; }
    .receipt { max-width: 500px; margin: 80px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
  </style>
</head>
<body>

<?php include("../includes/header.php"); ?>

<div class="container">
  <?php if ($success): ?>
    <div class="receipt text-center">
      <h3 class="text-success">Purchase Successful!</h3>
      <p class="lead">Thank you for your order.</p>
      <hr>
      <p><strong>Total Paid:</strong> Nu. <?= number_format($totalCost, 2) ?></p>
      <a href="dashboard.php" class="btn btn-success mt-3">Back to Dashboard</a>
    </div>
  <?php else: ?>
    <div class="alert alert-warning text-center mt-5">Your cart is empty or stock is insufficient.</div>
  <?php endif; ?>
</div>

<?php include("../includes/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

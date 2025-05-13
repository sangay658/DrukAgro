<?php
include("../includes/db.php");

// Redirect if not logged in as farmer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'farmer') {
  header("Location: ../auth/login.php");
  exit;
}

$farmer_id = $_SESSION['user_id'];
$message = '';

// Add vegetable
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];

  $imageName = $_FILES['image']['name'];
  $imageTmp = $_FILES['image']['tmp_name'];
  $imagePath = "../assets/images/" . basename($imageName);
  move_uploaded_file($imageTmp, $imagePath);

  $stmt = $conn->prepare("INSERT INTO vegetables (farmer_id, name, price, quantity, image) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("isdss", $farmer_id, $name, $price, $quantity, $imageName);
  $stmt->execute();
  $message = "Vegetable added successfully!";
}

// Delete item
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $conn->query("DELETE FROM vegetables WHERE id = $id AND farmer_id = $farmer_id");
  $message = "Item deleted.";
}

// Get listed vegetables
$vegs = $conn->query("SELECT * FROM vegetables WHERE farmer_id = $farmer_id");

// Get orders received
$orders = $conn->query("
  SELECT o.*, u.name AS buyer_name, v.name AS veg_name 
  FROM orders o
  JOIN users u ON o.user_id = u.id
  JOIN vegetables v ON o.veg_id = v.id
  WHERE o.farmer_id = $farmer_id
  ORDER BY o.order_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Farmer Dashboard - Druk Agro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f5f5f5; font-family: Arial, sans-serif; }
    .container { max-width: 1100px; }
    .form-section, .table-section { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 5px rgba(0,0,0,0.1); }
    .order-table { margin-top: 40px; }
  </style>
</head>
<body>

<?php include("../includes/header.php"); ?>

<div class="container py-5">
  <h2 class="mb-4 text-success text-center">Farmer Dashboard</h2>
  
  <?php if ($message): ?>
    <div class="alert alert-info"><?= $message ?></div>
  <?php endif; ?>

  <!-- Add Vegetable Form -->
  <div class="form-section mb-4">
    <h5>Add New Vegetable</h5>
    <form method="post" enctype="multipart/form-data">
      <div class="row g-3">
        <div class="col-md-4">
          <input type="text" name="name" placeholder="Name" class="form-control" required>
        </div>
        <div class="col-md-2">
          <input type="number" name="price" placeholder="Price (Nu.)" class="form-control" required step="0.01">
        </div>
        <div class="col-md-2">
          <input type="number" name="quantity" placeholder="Quantity (kg)" class="form-control" required>
        </div>
        <div class="col-md-3">
          <input type="file" name="image" accept="image/*" class="form-control" required>
        </div>
        <div class="col-md-1">
          <button class="btn btn-success w-100" type="submit" name="add">Add</button>
        </div>
      </div>
    </form>
  </div>

  <!-- Your Items -->
  <div class="table-section">
    <h5 class="mb-3">Your Vegetable Listings</h5>
    <table class="table table-bordered align-middle">
      <thead class="table-success">
        <tr>
          <th>Image</th>
          <th>Name</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while($veg = $vegs->fetch_assoc()): ?>
        <tr>
          <td><img src="../assets/images/<?= $veg['image'] ?>" style="width: 60px; height: 60px; object-fit: cover;"></td>
          <td><?= htmlspecialchars($veg['name']) ?></td>
          <td>Nu. <?= $veg['price'] ?>/kg</td>
          <td><?= $veg['quantity'] ?> kg</td>
          <td>
            <a href="?delete=<?= $veg['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')">Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!-- Orders Received -->
  <div class="table-section order-table">
    <h5 class="mb-3">Orders Received</h5>
    <?php if ($orders->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-success">
            <tr>
              <th>Buyer</th>
              <th>Vegetable</th>
              <th>Quantity (kg)</th>
              <th>Total Price (Nu.)</th>
              <th>Order Date</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $orders->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['buyer_name']) ?></td>
              <td><?= htmlspecialchars($row['veg_name']) ?></td>
              <td><?= $row['quantity'] ?></td>
              <td><?= number_format($row['total_price'], 2) ?></td>
              <td><?= date("d M Y, h:i A", strtotime($row['order_date'])) ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info text-center">No orders have been received yet.</div>
    <?php endif; ?>
  </div>
</div>

<?php include("../includes/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

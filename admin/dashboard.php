<?php
include("../includes/db.php");

// Redirect if not admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

$message = '';

// Handle user deletion
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $conn->query("DELETE FROM users WHERE id = $id");
  $message = "User deleted successfully.";
}

// Fetch all users except current admin
$users = $conn->query("SELECT * FROM users WHERE id != {$_SESSION['user_id']}");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Druk Agro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; font-family: Arial, sans-serif; }
    .admin-table { background: white; padding: 20px; border-radius: 10px; }
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

<?php include("../includes/header.php"); ?>
<main class="container py-5">

<div class="container py-5">
  <h3 class="text-success mb-4 text-center">Admin Dashboard - Manage Users</h3>

  <?php if ($message): ?>
    <div class="alert alert-info"><?= $message ?></div>
  <?php endif; ?>

  <?php if ($users->num_rows > 0): ?>
    <div class="table-responsive admin-table shadow-sm">
      <table class="table table-bordered align-middle">
        <thead class="table-success">
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($user = $users->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td class="text-capitalize"><?= $user['role'] ?></td>
            <td>
              <a href="?delete=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this user?')">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-warning text-center">No users found.</div>
  <?php endif; ?>
</div>
</main>
 
<?php include("../includes/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

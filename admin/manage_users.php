<?php
include('../config/db.php');
session_start();

// ✅ Restrict access to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

// ✅ Fetch all users
$sql = "SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users | Admin</title>
<style>
body {
  font-family: 'Segoe UI', sans-serif;
  background: #f5f6fa;
  margin: 0;
}
header {
  background: #007bff;
  color: white;
  padding: 20px;
  text-align: center;
}
.container {
  width: 90%;
  margin: 40px auto;
  background: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
table {
  width: 100%;
  border-collapse: collapse;
}
th, td {
  border: 1px solid #ccc;
  padding: 10px;
  text-align: left;
}
th {
  background: #007bff;
  color: white;
}
tr:nth-child(even) {
  background: #f9f9f9;
}
.action-buttons {
  display: flex;
  gap: 8px;
}
.action-buttons a {
  text-decoration: none;
  background: #e3ebf7;
  color: #333;
  padding: 6px 10px;
  border-radius: 6px;
  transition: 0.2s;
}
.action-buttons a:hover {
  background: #cddaf3;
  transform: scale(1.05);
}
.add-btn {
  display: inline-block;
  background: #007bff;
  color: white;
  padding: 10px 15px;
  border-radius: 6px;
  text-decoration: none;
  margin-bottom: 15px;
}
</style>
</head>

<body>
<header>
  <h1>Manage Users</h1>
</header>

<div class="container">
  <a href="dashboard.php" class="add-btn">⬅ Back to Dashboard</a>

  <table>
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Role</th>
      <th>Created At</th>
      <th>Actions</th>
    </tr>

    <?php while ($user = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($user['name']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= htmlspecialchars($user['role']) ?></td>
        <td><?= htmlspecialchars($user['created_at']) ?></td>
        <td>
          <div class="action-buttons">
            <a href="edit_user.php?id=<?= $user['id'] ?>">Edit</a>
            <?php if ($user['id'] != $_SESSION['user_id']): ?>
              <a href="delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('Delete this user?');">Delete</a>
            <?php endif; ?>
          </div>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

</body>
</html>

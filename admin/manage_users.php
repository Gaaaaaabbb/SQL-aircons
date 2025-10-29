<?php
include('../config/db.php');
session_start();

//  Restrict access to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

//  Fetch all users
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


* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: "Inter", "Segoe UI", sans-serif;
  background-color: #f9fafb;
  color: #111827;
  line-height: 1.6;
}

/*  Header  */
header {
  background: #ffffff;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 48px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

header h1 {
  font-size: 24px;
  font-weight: 700;
  color: #1f2937;
}

header h1::before {
  content: "SQL ";
  color: #3b82f6;
}

/*  Logout Button  */
.logout-btn {
  background: #ef4444;
  color: #fff;
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 500;
  font-size: 15px;
  text-decoration: none;
  transition: background 0.2s ease, transform 0.1s ease;
}

.logout-btn:hover {
  background: #dc2626;
  transform: scale(1.05);
}

.mng-usr {
  text-align: center;
  margin-top: 40px;
  font-size: 28px;
  font-weight: 600;
  color: #1f2937;
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
  background: #3b82f6;
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
.back-btn {
  display: inline-block;
  background: #3b82f6;
  color: white;
  padding: 10px 15px;
  border-radius: 6px;
  text-decoration: none;
  margin-bottom: 15px;
}

.back-btn:hover {
  background: #2563eb;
}
</style>
</head>

<body>
<header>
  <h1>Aircons</h1>
  <a href="../public/index.php" class="logout-btn">Logout</a>
</header>

<h1 class="mng-usr"> Manage Users </h1>

<div class="container">
  <a href="dashboard.php" class="back-btn"> Back to Dashboard</a>

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

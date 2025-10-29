<?php
include('../config/db.php');
session_start();

// Only admins can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

//Check if `id` is in URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_users.php");
    exit;
}

$user_id = $_GET['id'];

//  Fetch the user from DB
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

//  Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $role  = $_POST['role'];

    if (!empty($name) && !empty($email) && !empty($role)) {
        $update = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $update->bind_param("sssi", $name, $email, $role, $user_id);
        $update->execute();

        header("Location: manage_users.php");
        exit;
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit User | Admin</title>
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
  background: white;
  width: 500px;
  margin: 40px auto;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
label {
  display: block;
  margin-top: 15px;
  font-weight: bold;
}
input[type="text"],
input[type="email"],
select {
  width: 100%;
  padding: 10px;
  margin-top: 8px;
  border: 1px solid #ccc;
  border-radius: 6px;
}
input[type="submit"] {
  background: #007bff;
  color: white;
  border: none;
  cursor: pointer;
  padding: 10px;
  width: 100%;
  margin-top: 20px;
  border-radius: 6px;
}
input[type="submit"]:hover {
  background: #0056b3;
}
.back {
  text-decoration: none;
  color: #007bff;
  display: inline-block;
  margin-bottom: 20px;
}
.error {
  color: red;
  margin-bottom: 10px;
}
</style>
</head>

<body>
<header>
  <h1>Edit User</h1>
</header>

<div class="container">
  <a href="manage_users.php" class="back">⬅ Back to Users</a>

  <?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form method="POST">
    <label for="name">Full Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

    <label for="email">Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

    <label for="role">Role:</label>
    <select name="role" required>
      <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
      <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
    </select>

    <input type="submit" value="Update User">
  </form>
</div>

</body>
</html>

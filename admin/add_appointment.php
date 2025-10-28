<?php
include('../config/db.php');
session_start();

// ✅ Restrict access to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

// ✅ Fetch all users and services for dropdowns
$users = $conn->query("SELECT id, name FROM users ORDER BY name ASC");
$services = $conn->query("SELECT id, name FROM services ORDER BY name ASC");

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $service_id = $_POST['service_id'];
    $date = $_POST['appointment_date'];
    $status = $_POST['status'];

    if (!empty($user_id) && !empty($service_id) && !empty($date)) {
        $stmt = $conn->prepare("
            INSERT INTO appointments (user_id, service_id, appointment_date, status)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("iiss", $user_id, $service_id, $date, $status);
        $stmt->execute();

        header("Location: manage_appointments.php");
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
<title>Add Appointment | Admin</title>
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
select, input[type="date"], input[type="submit"] {
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
  margin-top: 20px;
}
input[type="submit"]:hover {
  background: #0056b3;
}
.error {
  color: red;
  margin-bottom: 10px;
}
.back {
  text-decoration: none;
  color: #007bff;
  display: inline-block;
  margin-bottom: 20px;
}
</style>
</head>

<body>
<header>
  <h1>Add Appointment</h1>
</header>

<div class="container">
  <a href="manage_appointments.php" class="back">⬅ Back to Appointments</a>

  <?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form method="POST">
    <label for="user_id">Select Customer:</label>
    <select name="user_id" required>
      <option value="">-- Choose Customer --</option>
      <?php while ($u = $users->fetch_assoc()): ?>
        <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
      <?php endwhile; ?>
    </select>

    <label for="service_id">Select Service:</label>
    <select name="service_id" required>
      <option value="">-- Choose Service --</option>
      <?php while ($s = $services->fetch_assoc()): ?>
        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
      <?php endwhile; ?>
    </select>

    <label for="appointment_date">Appointment Date:</label>
    <input type="date" name="appointment_date" required>

    <label for="status">Status:</label>
    <select name="status" required>
      <option value="pending">Pending</option>
      <option value="completed">Completed</option>
      <option value="cancelled">Cancelled</option>
    </select>

    <input type="submit" value="Add Appointment">
  </form>
</div>

</body>
</html>

<?php
include('../config/db.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}
?>
<?php

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include '../config/db.php'; // adjust path if needed
?>




<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | SQL Aircons</title>
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
  padding: 40px;
}
.card {
  background: white;
  padding: 20px;
  border-radius: 12px;
  margin-bottom: 20px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
a {
  display: inline-block;
  margin-right: 10px;
  background: #007bff;
  color: white;
  padding: 10px 20px;
  border-radius: 6px;
  text-decoration: none;
}
a:hover {
  background: #0056b3;
}
</style>
</head>
<body>
<header>
  <h1>Admin Dashboard</h1>
</header>
<div class="container">
  <div class="card">
    <h2>Manage Sections</h2>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_appointments.php">Manage Appointments</a>
    <a href="manage_billing.php">Manage Billing</a>
    <a href="analytics.php" class="admin-btn">View Analytics</a>

  </div>

  <div class="card">
    <a href="../public/logout.php">Logout</a>
  </div>
</div>
</body>
</html>
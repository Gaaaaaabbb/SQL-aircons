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

include '../config/db.php'; 
?>




<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | SQL Aircons</title>
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

/*  Header */
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

/* Logout Button  */
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

/*  Container*/
.container {
  max-width: 1000px;
  margin: 60px auto 40px;
  padding: 0 20px;
}

/*  Welcome Card  */
.card {
  background: #ffffff;
  padding: 40px;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  text-align: center;
  margin-bottom: 30px;
}

.card h2 {
  font-size: 26px;
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 10px;
}

.card p {
  font-size: 16px;
  color: #4b5563;
}

/* Navigation Section */
.nav-card {
  background: #ffffff;
  padding: 40px 50px;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 20px;
}

.nav-card a {
  background: #3b82f6;
  color: #ffffff;
  padding: 14px 28px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 500;
  font-size: 16px;
  transition: background 0.2s ease, transform 0.1s ease;
}

.nav-card a:hover {
  background: #2563eb;
  transform: scale(1.05);
}

/*  Responsive  */
@media (max-width: 768px) {
  header {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }

  .container {
    padding: 0 12px;
  }

  .nav-card {
    flex-direction: column;
    align-items: stretch;
  }

  .nav-card a {
    text-align: center;
    width: 100%;
  }
}


</style>
</head>
<body>
<header>
  <h1>Aircons</h1>
  <a href="../public/index.php" class="logout-btn">Logout</a>
</header>

<div class="container">
  <div class="card">
    <h2>Welcome to the Admin Dashboard</h2>
    <p>Use the options below to manage your system.</p>
  </div>

  <div class="nav-card">
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_appointments.php">Manage Appointments</a>
    <a href="manage_billing.php">Manage Billing</a>
    <a href="analytics.php" class="admin-btn">View Analytics</a>
  </div>
</div>

</div>
</body>
</html>
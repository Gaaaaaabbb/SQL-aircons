<?php
include('../includes/auth.php'); // protect the page
include('../config/db.php');

// Get user info (optional)
$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT name FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query);
$username = $user['name'] ?? 'User';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SQL Aircons | Dashboard</title>
  
</head>

<body>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f1f2f3ff;
      color: black;

      margin: 0;
      padding: 0;
    }
    header {
      padding: 40px 20px;
      background: #f1f2f3ff;
    }
    .first-word-title {
      text-align: left;
      color: #5ba1e6ff;
    }

    .second-word-title{
      text-align: left;
      color: black;
    }
    .nav-links {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 50px;
    }

    a {
      color: black;
      background: hsla(214, 93%, 47%, 0.20);
      padding: 19px 30px;
      border-radius: 12px;
      text-decoration: none;
      transition: 0.3s;
      font-size: 1.1em;
    }
    a:hover {
      background: rgba(44, 129, 239, 0.4);
    }
    footer {
      margin-top: 80px;
      font-size: 0.9em;
      opacity: 0.8;
    }
  </style>

  <header>
    <h1> <span class="first-word-title" >SQL </span> <span class="second-word-title">Aircons </span> </h1>
      <h1>Services</h1>
  </header>


  <div class="nav-links">
    <a href="Home.php"> Home</a>
    <a href="services.php">Appointments</a>
    <a href="billing.php"> Billing</a>
  </div>
</body>

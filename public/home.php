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
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #0078d7, #00b4d8);
      color: white;
      text-align: center;
      margin: 0;
      padding: 0;
    }
    header {
      padding: 40px 20px;
      background: rgba(0, 0, 0, 0.2);
    }
    h1 {
      margin: 10px 0;
    }
    .nav-links {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 50px;
    }
    a {
      color: white;
      background: rgba(255, 255, 255, 0.2);
      padding: 15px 30px;
      border-radius: 12px;
      text-decoration: none;
      transition: 0.3s;
      font-size: 1.1em;
    }
    a:hover {
      background: rgba(255, 255, 255, 0.4);
    }
    footer {
      margin-top: 80px;
      font-size: 0.9em;
      opacity: 0.8;
    }
  </style>
</head>
<body>

  <header>
    <h1>Welcome back, <?php echo htmlspecialchars($username); ?> 👋</h1>
    <p>Manage your air conditioning services with ease.</p>
  </header>

  <div class="nav-links">
    <a href="services.php"> View Services</a>
    <a href="appointments.php"> My Appointments</a>
    <a href="billing.php"> Billing</a>
    <a href="../admin/dashboard.php"> Admin Dashboard</a>
    <a href="logout.php"> Logout</a>
  </div>

  <footer>
    <p>© <?php echo date("Y"); ?> SQL Aircons. All rights reserved.</p>
  </footer>

</body>
</html>

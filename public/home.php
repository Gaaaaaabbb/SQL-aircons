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
      background: #f8fafc;
      color: #111827;
      margin: 0;
      padding: 0;
    }

    header {
      background: white;
      padding: 20px 50px;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    h1 {
      margin: 0;
    }

    .first-word-title {
      color: #2563eb;
    }

    .second-word-title {
      color: #111827;
    }

    .slogan {
      font-size: 18px;
      color: #6b7280;
      margin: 0;
    }

    nav {
      display: flex;
      justify-content: center;
      background: white;
      padding: 15px 0;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
      gap: 30px;
    }

    nav a {
      color: #111827;
      text-decoration: none;
      font-size: 16px;
      font-weight: 500;
      padding: 10px 20px;
      border-radius: 10px;
      transition: 0.3s ease;
    }

    nav a:hover {
      background: #2563eb;
      color: white;
    }

    main {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-top: 60px;
    }

    footer {
      text-align: center;
      font-size: 0.9em;
      color: #6b7280;
      margin-top: 80px;
      padding-bottom: 30px;
    }

    @media (max-width: 768px) {
      header, nav, main {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

  <header>
    <h1><span class="first-word-title">SQL </span><span class="second-word-title">Aircons</span></h1>
    <h2>Welcome back, <?php echo htmlspecialchars($username); ?> 👋</h2>
    <p class="slogan">Manage your air conditioning services with ease.</p>
  </header>

  <nav>
    <a href="services.php">Services</a>
    <a href="appointments.php">My Appointments</a>
    <a href="billing.php">Billing</a>
    <a href="logout.php">Logout</a>
  </nav>

  <main>
    <!-- Removed dashboard card -->
  </main>

  <footer>
    <p>© <?php echo date("Y"); ?> SQL Aircons. All rights reserved.</p>
  </footer>

</body>
</html>

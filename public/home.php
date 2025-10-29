<?php
include('../includes/auth.php');
include('../config/db.php');

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
      overflow-x: hidden;
    }

    header {
      background: white;
      padding: 25px 50px;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
      text-align: center;
      animation: fadeDown 0.8s ease forwards;
    }

    h1 {
      margin: 0;
      font-size: 36px;
    }

    .first-word-title {
      color: #2563eb;
    }

    .second-word-title {
      color: #111827;
    }

    nav {
      display: flex;
      justify-content: center;
      background: white;
      padding: 15px 0;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
      gap: 25px;
      opacity: 0;
      transform: translateY(-10px);
      animation: fadeUp 0.9s ease forwards;
      animation-delay: 0.3s;
    }

    nav a {
      color: #111827;
      text-decoration: none;
      font-size: 17px;
      font-weight: 500;
      padding: 10px 25px;
      border-radius: 10px;
      transition: 0.3s ease;
    }

    nav a:hover {
      background: #2563eb;
      color: white;
      transform: scale(1.05);
    }

    main {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 70vh;
      text-align: center;
      opacity: 0;
      transform: translateY(20px);
      animation: fadeUp 1s ease forwards;
      animation-delay: 0.6s;
    }

    .welcome-text {
      font-size: 26px;
      font-weight: 600;
      color: #111827;
      margin-bottom: 10px;
    }

    .slogan {
      font-size: 18px;
      color: #6b7280;
    }

    footer {
      text-align: center;
      font-size: 0.9em;
      color: #6b7280;
      margin-top: 40px;
      padding-bottom: 30px;
      opacity: 0;
      animation: fadeIn 1s ease forwards;
      animation-delay: 1s;
    }

    /* ✨ Animations */
    @keyframes fadeDown {
      0% { opacity: 0; transform: translateY(-20px); }
      100% { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeUp {
      0% { opacity: 0; transform: translateY(20px); }
      100% { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @media (max-width: 768px) {
      header, nav, main {
        padding: 20px;
      }

      h1 {
        font-size: 30px;
      }

      .welcome-text {
        font-size: 22px;
      }

      .slogan {
        font-size: 16px;
      }
    }
  </style>
</head>
<body>

  <header>
    <h1><span class="first-word-title">SQL </span><span class="second-word-title">Aircons</span></h1>
  </header>

  <nav>
    <a href="services.php">Services</a>
    <a href="appointments.php">My Appointments</a>
    <a href="billing.php">Billing</a>
    <a href="logout.php">Logout</a>
  </nav>

  <main>
    <div class="welcome-section">
      <p class="welcome-text">Welcome back, <?php echo htmlspecialchars($username); ?></p>
      <p class="slogan">Manage your air conditioning services with ease.</p>
    </div>
  </main>

  <footer>
    <p>© <?php echo date("Y"); ?> SQL Aircons. All rights reserved.</p>
  </footer>

</body>
</html>

<?php
include('../includes/auth.php');
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

// Fetch all available services
$sql = "SELECT * FROM services";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SQL Aircons | Services</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f8fafc;
      color: #111827;
      margin: 0;
      padding: 0;
      overflow-x: hidden;
      animation: fadeIn 0.7s ease;
    }

    header {
      background: white;
      padding: 25px 50px;
      box-shadow: 0 1px 6px rgba(0, 0, 0, 0.1);
      text-align: center;
      animation: slideDown 0.8s ease;
    }

    .first-word-title { color: #2563eb; }
    .second-word-title { color: #111827; }

    h1 { margin: 0; font-size: 32px; }

    /* --- NAVIGATION --- */
    .nav-links {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 25px;
      background: white;
      padding: 15px 0;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
      animation: fadeInUp 1s ease;
    }

    .nav-links a {
      color: #111827;
      text-decoration: none;
      font-size: 17px;
      font-weight: 500;
      padding: 10px 25px;
      border-radius: 10px;
      transition: 0.3s;
    }

    .nav-links a:hover,
    .nav-links a.active {
      background: #2563eb;
      color: white;
      transform: translateY(-3px);
    }

    main {
      width: 90%;
      margin: 50px auto;
      text-align: center;
      animation: fadeInUp 0.9s ease;
    }

    h2 {
      font-size: 26px;
      color: #1f2937;
      margin-bottom: 25px;
    }

    .service-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 25px;
      margin-top: 30px;
    }

    .service-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
      padding: 25px;
      text-align: center;
      transition: 0.3s ease;
      animation: fadeInUp 1s ease;
    }

    .service-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.12);
    }

    .service-card h3 {
      color: #111827;
      margin-bottom: 10px;
      font-size: 20px;
    }

    .service-card p {
      color: #374151;
      margin-bottom: 15px;
      font-size: 16px;
    }

    form label {
      font-size: 14px;
      color: #6b7280;
      display: block;
      margin-bottom: 5px;
    }

    form input[type="datetime-local"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      margin-bottom: 10px;
      font-size: 14px;
      transition: 0.2s;
    }

    form input[type="datetime-local"]:focus {
      border-color: #2563eb;
      outline: none;
      box-shadow: 0 0 5px rgba(37, 99, 235, 0.3);
    }

    button {
      background-color: #2563eb;
      color: white;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 500;
      transition: 0.3s ease;
    }

    button:hover {
      background-color: #1e40af;
      transform: scale(1.05);
    }

    footer {
      text-align: center;
      font-size: 0.9em;
      color: #6b7280;
      margin-top: 60px;
      padding-bottom: 30px;
      animation: fadeIn 1s ease;
    }

    /* --- Animations --- */
    @keyframes fadeIn {
      from { opacity: 0; } to { opacity: 1; }
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideDown {
      from { transform: translateY(-20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    @media (max-width: 600px) {
      header { padding: 20px; }
      .nav-links { flex-wrap: wrap; gap: 15px; }
    }
  </style>
</head>

<body>
  <header>
    <h1><span class="first-word-title">SQL</span> <span class="second-word-title">Aircons</span></h1>
  </header>

  <div class="nav-links">
    <a href="home.php" class="<?= basename($_SERVER['PHP_SELF']) == 'home.php' ? 'active' : '' ?>">Home</a>
    <a href="services.php" class="<?= basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : '' ?>">Services</a>
    <a href="appointments.php" class="<?= basename($_SERVER['PHP_SELF']) == 'appointments.php' ? 'active' : '' ?>">My Appointments</a>
    <a href="billing.php" class="<?= basename($_SERVER['PHP_SELF']) == 'billing.php' ? 'active' : '' ?>">Billing</a>
  </div>

  <main>
    <h2>Choose a Service and Schedule</h2>

    <div class="service-container">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="service-card">
          <h3><?= htmlspecialchars($row['name']) ?></h3>
          <p>₱<?= number_format($row['price'], 2) ?></p>
          <form method="POST" action="avail_service.php">
            <input type="hidden" name="service_id" value="<?= $row['id'] ?>">
            <label>Select Date & Time:</label>
            <input type="datetime-local" name="appointment_datetime" required>
            <button type="submit">Avail</button>
          </form>
        </div>
      <?php endwhile; ?>
    </div>
  </main>

  <footer>
    <p>© <?= date('Y') ?> SQL Aircons. All Rights Reserved.</p>
  </footer>
</body>
</html>

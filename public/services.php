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
    }

    header {
      background: white;
      padding: 20px 50px;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .first-word-title {
      color: #2563eb;
    }

    .second-word-title {
      color: #111827;
    }

    h1 {
      margin: 0;
    }

    .nav-links {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 30px;
      background: white;
      padding: 15px 0;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .nav-links a {
      color: #111827;
      text-decoration: none;
      font-size: 16px;
      font-weight: 500;
      padding: 10px 20px;
      border-radius: 10px;
      transition: 0.3s ease;
    }

    .nav-links a:hover {
      background: #2563eb;
      color: white;
    }

    h2 {
      text-align: center;
      margin-top: 40px;
      font-size: 28px;
      color: #1f2937;
    }

    .service-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 25px;
      max-width: 1000px;
      margin: 40px auto;
      padding: 0 20px;
    }

    .service-card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      padding: 25px;
      text-align: center;
      transition: 0.3s ease;
    }

    .service-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
    }

    .service-card h3 {
      color: #111827;
      margin-bottom: 8px;
      font-size: 20px;
    }

    .service-card p {
      font-size: 1.1em;
      color: #374151;
      margin-bottom: 15px;
    }

    form label {
      font-size: 14px;
      color: #6b7280;
    }

    form input[type="datetime-local"] {
      margin: 10px 0;
      padding: 10px;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      width: 100%;
      font-size: 14px;
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
    }

    footer {
      text-align: center;
      font-size: 0.9em;
      color: #6b7280;
      margin-top: 80px;
      padding-bottom: 30px;
    }

    @media (max-width: 600px) {
      header {
        padding: 20px;
      }
    }
  </style>
</head>

<body>
  <header>
    <h1><span class="first-word-title">SQL</span> <span class="second-word-title">Aircons</span></h1>
    <h2>Services</h2>
  </header>

  <div class="nav-links">
    <a href="home.php">Home</a>
    <a href="appointments.php">My Appointments</a>
    <a href="billing.php">Billing</a>
  </div>

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

  <footer>
    <p>© 2025 SQL Aircons. All Rights Reserved.</p>
  </footer>
</body>
</html>

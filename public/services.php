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
      background: #f1f2f3ff;
      color: black;
      margin: 0;
      padding: 0;
    }

    button {
      background-color: #008CBA;
      color: white;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
      border-radius: 5px;
    }

    header {
      padding: 40px 20px;
      background: #f1f2f3ff;
    }

    .first-word-title {
      text-align: left;
      color: #5ba1e6ff;
    }

    .second-word-title {
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

    .service-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin-top: 30px;
    }

    .service-card {
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      width: 280px;
      text-align: center;
    }

    .service-card h3 {
      margin-bottom: 10px;
    }

    .service-card p {
      font-size: 1.1em;
      margin-bottom: 15px;
    }

    form input[type="datetime-local"] {
      margin-bottom: 10px;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 90%;
    }

    footer {
      margin-top: 80px;
      font-size: 0.9em;
      opacity: 0.8;
      text-align: center;
    }
  </style>
</head>

<body>
  <header>
    <h1><span class="first-word-title">SQL</span> <span class="second-word-title">Aircons</span></h1>
    <h1>Services</h1>
  </header>

  <div class="nav-links">
    <a href="home.php">Home</a>
    <a href="appointments.php">Appointments</a>
    <a href="billing.php">Billing</a>
  </div>

  <h2 style="text-align:center;">Choose a Service and Schedule</h2>

  <div class="service-container">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="service-card">
        <h3><?= htmlspecialchars($row['name']) ?></h3>
        <p>₱<?= number_format($row['price'], 2) ?></p>
        <form method="POST" action="avail_service.php">
          <input type="hidden" name="service_id" value="<?= $row['id'] ?>">
          <label>Select Date & Time:</label><br>
          <input type="datetime-local" name="appointment_datetime" required><br>
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

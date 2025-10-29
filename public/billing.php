<?php
include('../includes/auth.php');
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Always show all bills by this user
$sql = "
    SELECT b.*, s.name AS service_name
    FROM billing b
    JOIN services s ON b.service_id = s.id
    WHERE b.user_id = ?
    ORDER BY b.created_at DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$billings = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SQL Aircons | Billing</title>
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

    .nav-links a.active {
      background: #2563eb;
      color: white;
    }

    h2 {
      text-align: center;
      margin-top: 40px;
      font-size: 28px;
      color: #1f2937;
    }

    table {
      width: 80%;
      margin: 40px auto;
      border-collapse: collapse;
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    th, td {
      padding: 15px;
      text-align: left;
    }

    th {
      background-color: #2563eb;
      color: white;
    }

    tr:nth-child(even) {
      background: #f9fafb;
    }

    .pending {
      color: #f59e0b;
      font-weight: bold;
    }

    .paid {
      color: #10b981;
      font-weight: bold;
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
    <h2>Billing</h2>
  </header>

  <div class="nav-links">
    <a href="home.php">Home</a>
    <a href="services.php">Services</a>
    <a href="appointments.php">My Appointments</a>
    <a href="billing.php" class="active">Billing</a>
  </div>

  <h2>Your Billing Summary</h2>

  <table>
    <tr>
      <th>Service</th>
      <th>Amount</th>
      <th>Status</th>
      <th>Payment Method</th>
      <th>Date</th>
    </tr>

    <?php if (empty($billings)): ?>
      <tr>
        <td colspan="5" style="text-align:center;">No billing records found.</td>
      </tr>
    <?php else: ?>
      <?php foreach ($billings as $bill): ?>
        <tr>
          <td><?= htmlspecialchars($bill['service_name']) ?></td>
          <td>₱<?= number_format($bill['total_amount'], 2) ?></td>
          <td>
            <?php if (isset($bill['status']) && strtolower($bill['status']) === 'paid'): ?>
              <span class="paid">Paid</span>
            <?php else: ?>
              <span class="pending">Pending</span>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($bill['payment_method'] ?? 'Cash') ?></td>
          <td><?= htmlspecialchars($bill['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </table>

  <footer>
    <p>© 2025 SQL Aircons. All Rights Reserved.</p>
  </footer>
</body>
</html>

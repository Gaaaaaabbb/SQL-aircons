<?php
include('../includes/auth.php');
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch username
$user_query = $conn->prepare("SELECT name FROM users WHERE id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();
$username = $user['name'] ?? 'User';
$user_query->close();

// Fetch billing records
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
      animation: fadeIn 0.7s ease;
    }

    header {
      background: white;
      padding: 25px 50px;
      box-shadow: 0 1px 6px rgba(0, 0, 0, 0.1);
      text-align: center;
      animation: slideDown 0.8s ease;
    }

    .first-word-title {
      color: #2563eb;
    }

    .second-word-title {
      color: #111827;
    }

    h1 {
      margin: 0;
      font-size: 32px;
    }

    .welcome-text {
      font-size: 18px;
      color: #6b7280;
      margin-top: 8px;
    }

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

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
      animation: fadeInUp 1.1s ease;
    }

    th, td {
      padding: 15px 18px;
      border-bottom: 1px solid #e5e7eb;
      text-align: center;
      font-size: 15px;
    }

    th {
      background: #2563eb;
      color: white;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    tr:hover {
      background-color: #f3f4f6;
      transition: 0.2s;
    }

    .pending { color: #d97706; font-weight: 600; }
    .paid { color: #059669; font-weight: 600; }

    footer {
      text-align: center;
      font-size: 0.9em;
      color: #6b7280;
      margin-top: 60px;
      padding-bottom: 30px;
    }

    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes slideDown {
      from {
        transform: translateY(-20px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }
  </style>
</head>

<body>
  <header>
    <h1><span class="first-word-title">SQL</span> <span class="second-word-title">Aircons</span></h1>
  </header>

  <div class="nav-links">
    <a href="home.php">Home</a>
    <a href="services.php">Services</a>
    <a href="appointments.php">My Appointments</a>
    <a href="billing.php" class="active">Billing</a>
  </div>

  <main>
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
        <tr><td colspan="5" style="padding: 25px;">No billing records found.</td></tr>
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
  </main>

  <footer>
    <p>© <?= date('Y') ?> SQL Aircons. All Rights Reserved.</p>
  </footer>
</body>
</html>

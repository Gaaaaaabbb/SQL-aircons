<?php
include('../includes/auth.php');
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

// Get user info
$query = mysqli_query($conn, "SELECT name FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query);
$username = $user['name'] ?? 'User';

// Fetch appointments and billing info
$sql = "
  SELECT 
    a.id AS appointment_id,
    s.name AS service_name,
    a.appointment_date,
    a.status AS appointment_status,
    b.amount,
    b.status AS billing_status,
    b.created_at AS billing_date
  FROM appointments a
  JOIN services s ON a.service_id = s.id
  LEFT JOIN billing b ON a.id = b.appointment_id
  WHERE a.user_id = ?
  ORDER BY a.appointment_date DESC
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL Prepare failed: " . $conn->error . "<br><br>Query: " . $sql);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$appointments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SQL Aircons | Appointments</title>
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

    table {
      width: 90%;
      margin: 40px auto;
      border-collapse: collapse;
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    th, td {
      padding: 14px 16px;
      border-bottom: 1px solid #e5e7eb;
      text-align: left;
      font-size: 15px;
    }

    th {
      background: #2563eb;
      color: white;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-size: 14px;
    }

    tr:hover {
      background-color: #f9fafb;
    }

    .pending { color: #d97706; font-weight: bold; }
    .completed { color: #059669; font-weight: bold; }
    .cancelled { color: #dc2626; font-weight: bold; }

    .action-buttons {
      display: flex;
      gap: 6px;
      justify-content: center;
      align-items: center;
    }

    .action-buttons a {
      text-decoration: none;
      background-color: #e3ebf7;
      color: #333;
      padding: 6px 10px;
      border-radius: 6px;
      font-size: 14px;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      transition: 0.2s;
    }

    .action-buttons a:hover {
      background-color: #cddaf3;
      transform: scale(1.05);
    }

    footer {
      text-align: center;
      font-size: 0.9em;
      color: #6b7280;
      margin-top: 80px;
      padding-bottom: 30px;
    }
  </style>
</head>

<body>
  <header>
    <h1><span class="first-word-title">SQL</span> <span class="second-word-title">Aircons</span></h1>
    <h2>Appointments</h2>
  </header>

  <div class="nav-links">
    <a href="home.php">Home</a>
    <a href="services.php">Services</a>
    <a href="billing.php">Billing</a>
  </div>

  <h2>Your Appointments</h2>

  <table>
    <tr>
      <th>Service</th>
      <th>Date</th>
      <th>Status</th>
      <th>Billing Amount</th>
      <th>Billing Status</th>
      <th>Payment</th>
      <th>Action</th>
    </tr>

    <?php if (empty($appointments)): ?>
      <tr><td colspan="7" style="text-align:center; padding: 20px;">No appointments yet.</td></tr>
    <?php else: ?>
      <?php foreach ($appointments as $a): ?>
        <tr>
          <td><?= htmlspecialchars($a['service_name']) ?></td>
          <td><?= htmlspecialchars($a['appointment_date']) ?></td>
          <td class="<?= htmlspecialchars($a['appointment_status']) ?>">
            <?= ucfirst(htmlspecialchars($a['appointment_status'])) ?>
          </td>
          <td>₱<?= number_format($a['amount'] ?? 0, 2) ?></td>
          <td class="<?= htmlspecialchars($a['billing_status'] ?? 'pending') ?>">
            <?= ucfirst(htmlspecialchars($a['billing_status'] ?? 'Pending')) ?>
          </td>
          <td>Cash Only</td>
          <td>
            <div class="action-buttons">
              <a href="edit_appointment.php?id=<?= $a['appointment_id'] ?>" title="Edit">✏️</a>
              <a href="delete_appointment.php?id=<?= $a['appointment_id'] ?>" onclick="return confirm('Are you sure?');" title="Delete">🗑️</a>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </table>

  <footer>
    <p>© 2025 SQL Aircons. All Rights Reserved.</p>
  </footer>
</body>
</html>

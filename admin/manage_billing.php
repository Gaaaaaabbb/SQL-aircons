<?php
include('../config/db.php');
session_start();

// Restrict access to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

//  Handle status update
if (isset($_GET['mark_paid'])) {
    $billing_id = $_GET['mark_paid'];
    $conn->query("UPDATE billing SET status = 'paid' WHERE id = '$billing_id'");
    header("Location: manage_billing.php");
    exit;
}

//  Fetch all billing records
$sql = "
  SELECT 
    b.id AS billing_id,
    u.name AS customer_name,
    s.name AS service_name,
    a.appointment_date,
    b.amount,
    b.status,
    b.created_at
  FROM billing b
  JOIN appointments a ON b.appointment_id = a.id
  JOIN users u ON a.user_id = u.id
  JOIN services s ON a.service_id = s.id
  ORDER BY b.created_at DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Billing | Admin</title>
<style>
body {
  font-family: 'Segoe UI', sans-serif;
  background: #f5f6fa;
  margin: 0;
}
header {
  background: #007bff;
  color: white;
  padding: 20px;
  text-align: center;
}
.container {
  width: 90%;
  margin: 40px auto;
  background: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
table {
  width: 100%;
  border-collapse: collapse;
}
th, td {
  border: 1px solid #ccc;
  padding: 10px;
  text-align: left;
}
th {
  background: #007bff;
  color: white;
}
tr:nth-child(even) {
  background: #f9f9f9;
}
.action-buttons a {
  text-decoration: none;
  background: #e3ebf7;
  color: #333;
  padding: 6px 10px;
  border-radius: 6px;
  transition: 0.2s;
}
.action-buttons a:hover {
  background: #cddaf3;
  transform: scale(1.05);
}
.status-paid { color: green; font-weight: bold; }
.status-pending { color: orange; font-weight: bold; }
</style>
</head>

<body>
<header>
  <h1>Manage Billing</h1>
</header>

<div class="container">
  <a href="dashboard.php" class="add-btn">⬅ Back to Dashboard</a>

  <table>
    <tr>
      <th>Customer</th>
      <th>Service</th>
      <th>Date</th>
      <th>Amount</th>
      <th>Status</th>
      <th>Action</th>
    </tr>

    <?php while ($b = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($b['customer_name']) ?></td>
        <td><?= htmlspecialchars($b['service_name']) ?></td>
        <td><?= htmlspecialchars($b['appointment_date']) ?></td>
        <td>₱<?= number_format($b['amount'], 2) ?></td>
        <td class="status-<?= strtolower($b['status']) ?>">
          <?= ucfirst($b['status']) ?>
        </td>
        <td>
          <?php if ($b['status'] !== 'paid'): ?>
            <a href="?mark_paid=<?= $b['billing_id'] ?>" onclick="return confirm('Mark this bill as paid?');"> Mark as Paid</a>
          <?php else: ?>
            ✅ Paid
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

</body>
</html>

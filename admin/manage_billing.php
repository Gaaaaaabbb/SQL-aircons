<?php
include('../config/db.php');
session_start();

// ✅ Restrict access to admins only
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
<title>Admin | Manage Billing</title>
<style>
/* ===========================
   Admin Dashboard – SQL Aircons Unified Style
   =========================== */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: "Inter", "Segoe UI", sans-serif;
  background-color: #f9fafb;
  color: #111827;
  line-height: 1.6;
}

/* ---------- Header ---------- */
header {
  background: #ffffff;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 48px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

header h1 {
  font-size: 24px;
  font-weight: 700;
  color: #1f2937;
}

header h1::before {
  content: "SQL ";
  color: #3b82f6;
}

/* ---------- Logout Button ---------- */
.logout-btn {
  background: #ef4444;
  color: #fff;
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 500;
  font-size: 15px;
  text-decoration: none;
  transition: background 0.2s ease, transform 0.1s ease;
}

.logout-btn:hover {
  background: #dc2626;
  transform: scale(1.05);
}

/* ---------- Page Title ---------- */
.page-title {
  text-align: center;
  margin-top: 40px;
  font-size: 28px;
  font-weight: 600;
  color: #1f2937;
}

/* ---------- Action Bar ---------- */
.action-bar {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  margin: 20px 40px;
}

.back-btn {
  display: inline-block;
  background: #3b82f6;
  color: white;
  padding: 10px 15px;
  border-radius: 6px;
  text-decoration: none;
  font-weight: 500;
  transition: background 0.2s ease;
}

.back-btn:hover {
  background: #2563eb;
}

/* ---------- Container & Table ---------- */
.container {
  padding: 30px;
}

table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

th, td {
  padding: 12px 15px;
  border: 1px solid #ddd;
  text-align: left;
}

th {
  background: #007bff;
  color: white;
}

tr:nth-child(even) { background: #f9f9f9; }

/* ---------- Status Colors ---------- */
.status-paid {
  color: #16a34a;
  font-weight: 600;
}

.status-pending {
  color: #f59e0b;
  font-weight: 600;
}

/* ---------- Action Links ---------- */
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
</style>
</head>

<body>
<header>
  <h1>Aircons</h1>
  <a href="../public/logout.php" class="logout-btn">Logout</a>
</header>

<h1 class="page-title">Manage Billing</h1>

<div class="action-bar">
  <a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

<div class="container">
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
        <td class="action-buttons">
          <?php if ($b['status'] !== 'paid'): ?>
            <a href="?mark_paid=<?= $b['billing_id'] ?>" onclick="return confirm('Mark this bill as paid?');">Mark as Paid</a>
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
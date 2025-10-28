<?php
include('../config/db.php');
session_start();

use Dompdf\Dompdf;
// ✅ Restrict to admin users only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

// ✅ Get analytics data
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'customer'")->fetch_assoc()['total'];
$total_appointments = $conn->query("SELECT COUNT(*) AS total FROM appointments")->fetch_assoc()['total'];
$completed_appointments = $conn->query("SELECT COUNT(*) AS total FROM appointments WHERE status = 'completed'")->fetch_assoc()['total'];
$pending_appointments = $conn->query("SELECT COUNT(*) AS total FROM appointments WHERE status = 'pending'")->fetch_assoc()['total'];
$cancelled_appointments = $conn->query("SELECT COUNT(*) AS total FROM appointments WHERE status = 'cancelled'")->fetch_assoc()['total'];

$total_revenue = $conn->query("
    SELECT SUM(b.amount) AS total 
    FROM billing b 
    WHERE b.status = 'paid'
")->fetch_assoc()['total'] ?? 0;

// ✅ PDF Export
if (isset($_GET['download']) && $_GET['download'] === 'pdf') {
    require('../vendor/autoload.php');

    $dompdf = new Dompdf();

    $html = "
    <h1 style='text-align:center;'>SQL Aircons - Analytics Report</h1>
    <p style='text-align:center;'>Generated on " . date('F j, Y, g:i a') . "</p>
    <hr>
    <h3>📊 Summary</h3>
    <ul>
        <li><strong>Total Customers:</strong> {$total_users}</li>
        <li><strong>Total Appointments:</strong> {$total_appointments}</li>
        <li><strong>Completed:</strong> {$completed_appointments}</li>
        <li><strong>Pending:</strong> {$pending_appointments}</li>
        <li><strong>Cancelled:</strong> {$cancelled_appointments}</li>
        <li><strong>Total Revenue:</strong> ₱" . number_format($total_revenue, 2) . "</li>
    </ul>
    ";

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("SQL_Aircons_Analytics_Report.pdf", ["Attachment" => true]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Analytics | Admin</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
  max-width: 1000px;
  margin: 30px auto;
  background: white;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
h2 {
  color: #007bff;
  margin-bottom: 20px;
}
.stats {
  list-style: none;
  padding: 0;
}
.stats li {
  background: #f0f2f5;
  margin: 10px 0;
  padding: 12px;
  border-radius: 6px;
  font-size: 16px;
}
button {
  background: #007bff;
  color: white;
  border: none;
  padding: 12px 18px;
  border-radius: 6px;
  cursor: pointer;
  margin-top: 20px;
}
button:hover {
  background: #0056b3;
}
.chart-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-around;
  margin-top: 30px;
  gap: 40px;
}
canvas {
  background: #fff;
  border-radius: 10px;
  padding: 15px;
  width: 400px !important;
  height: 300px !important;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
</style>
</head>
<body>

<header>
  <h1>Admin Analytics Dashboard</h1>
</header>

<div class="container">
  <a href="dashboard.php" class="back">⬅ Back</a>

<div class="container">
  <h2>📈 Summary Overview</h2>
  <ul class="stats">
    <li><strong>Total Appointments:</strong> <?= $total_appointments ?></li>
    <li><strong>Completed:</strong> <?= $completed_appointments ?></li>
    <li><strong>Pending:</strong> <?= $pending_appointments ?></li>
    <li><strong>Cancelled:</strong> <?= $cancelled_appointments ?></li>
    <li><strong>Total Revenue:</strong> ₱<?= number_format($total_revenue, 2) ?></li>
  </ul>

  <div class="chart-container">
    <canvas id="appointmentChart"></canvas>
    <canvas id="revenueChart"></canvas>
  </div>

  <form method="GET" action="">
    <input type="hidden" name="download" value="pdf">
    <button type="submit">⬇ Download PDF Report</button>
  </form>
</div>

<script>
// ✅ Appointment Status Chart
const ctx1 = document.getElementById('appointmentChart');
new Chart(ctx1, {
  type: 'pie',
  data: {
    labels: ['Completed', 'Pending', 'Cancelled'],
    datasets: [{
      data: [<?= $completed_appointments ?>, <?= $pending_appointments ?>, <?= $cancelled_appointments ?>],
      backgroundColor: ['#28a745', '#ffc107', '#dc3545']
    }]
  },
  options: {
    plugins: {
      title: {
        display: true,
        text: 'Appointment Status Distribution'
      }
    }
  }
});

// ✅ Revenue Overview Chart
const ctx2 = document.getElementById('revenueChart');
new Chart(ctx2, {
  type: 'bar',
  data: {
    labels: ['Revenue'],
    datasets: [{
      label: '₱ Total Income',
      data: [<?= $total_revenue ?>],
      backgroundColor: ['#007bff']
    }]
  },
  options: {
    plugins: {
      title: {
        display: true,
        text: 'Total Revenue'
      }
    },
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});
</script>

</body>
</html>

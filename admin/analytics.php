<?php
include('../config/db.php');
session_start();

use Dompdf\Dompdf;
//  Restrict to admin users only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

//  Get analytics data
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

//  PDF Export
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
<title>Admin | Analytics Dashboard</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>

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

/*  Header */
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

/*  Logout Button  */
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

/* Page Title  */
.page-title {
  text-align: center;
  margin-top: 40px;
  font-size: 28px;
  font-weight: 600;
  color: #1f2937;
}

/*  Action Bar */
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

/* Container  */
.container {
  width: 90%;
  max-width: 1000px;
  margin: 30px auto;
  background: white;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

/*  Stats List */
.stats {
  list-style: none;
  padding: 0;
}

.stats li {
  background: #f3f4f6;
  margin: 10px 0;
  padding: 12px;
  border-radius: 6px;
  font-size: 16px;
}

/* Chart Section  */
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

/*Buttons */
button {
  background: #3b82f6;
  color: white;
  border: none;
  padding: 12px 18px;
  border-radius: 6px;
  cursor: pointer;
  margin-top: 20px;
  font-weight: 500;
  transition: background 0.2s ease;
}

button:hover {
  background: #2563eb;
}
</style>
</head>
<body>

<header>
  <h1>Aircons</h1>
  <a href="../public/index.php" class="logout-btn">Logout</a>
</header>

<h1 class="page-title">Admin Analytics Dashboard</h1>

<div class="action-bar">
  <a href="dashboard.php" class="back-btn"> Back to Dashboard</a>
</div>

<div class="container">
  <h2 style="color:#3b82f6; margin-bottom:20px;">📈 Summary Overview</h2>
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
    <button type="submit">Download PDF Report</button>
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
      backgroundColor: ['#16a34a', '#f59e0b', '#dc2626']
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
      backgroundColor: ['#3b82f6']
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


<?php
include('../config/db.php');
session_start();

//  Check admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

//  Fetch all appointments with user & service info
$sql = "
    SELECT 
        a.id AS appointment_id,
        u.name AS customer_name,
        s.name AS service_name,
        a.appointment_date,
        a.status AS appointment_status
    FROM appointments a
    JOIN users u ON a.user_id = u.id
    JOIN services s ON a.service_id = s.id
    ORDER BY a.appointment_date DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin | Manage Appointments</title>
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

.mng-appt {
  text-align: center;
  margin-top: 40px;
  font-size: 28px;
  font-weight: 600;
  color: #1f2937;
}

.container {
  padding: 30px;
}

.action-bar {
  display: flex;
  justify-content: space-between;
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
  margin-bottom: 15px;
}

.back-btn:hover {
  background: #2563eb;
}
.add {
  background-color: #16a34a;
  color: white;
  padding: 10px 20px;
  border-radius: 6px;
  text-decoration: none;
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
.actions a {
  padding: 6px 12px;
  border-radius: 6px;
  margin: 0 3px;
}
.edit { background: #e3ebf7; }
.delete { background: #f8d7da; color: #721c24; }
.add {
  display: inline-block;
  margin-bottom: 15px;
  padding: 8px 15px;
  background: #28a745;
  color: white;
  border-radius: 6px;
}
</style>
</head>
<body>
<header>
  <h1>Aircons</h1>
  <a href="../public/index.php" class="logout-btn">Logout</a>
  
</header>
<h1 class="mng-appt"> Manage Appointments </h1>

<div class="action-bar">
  <a href="dashboard.php" class="back-btn"> Back to Dashboard</a> <br>
  <a href="add_appointment.php" class="add">  Add Appointment</a>
</div>
<div class="container">
  <table>
    <tr>
      <th>ID</th>
      <th>Customer</th>
      <th>Service</th>
      <th>Date</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['appointment_id'] ?></td>
          <td><?= htmlspecialchars($row['customer_name']) ?></td>
          <td><?= htmlspecialchars($row['service_name']) ?></td>
          <td><?= htmlspecialchars($row['appointment_date']) ?></td>
          <td><?= ucfirst($row['appointment_status']) ?></td>
          <td class="actions">
            <a class="edit" href="edit_appointment.php?id=<?= $row['appointment_id'] ?>">Edit</a>
            <a class="delete" href="delete_appointment.php?id=<?= $row['appointment_id'] ?>" onclick="return confirm('Delete this appointment?');">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="6" style="text-align:center;">No appointments found.</td></tr>
    <?php endif; ?>
  </table>
</div>

</body>
</html>

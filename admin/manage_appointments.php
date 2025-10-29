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
  padding: 30px;
}
a {
  text-decoration: none;
  color: #007bff;
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
  <h1>Manage Appointments</h1>
</header>

<div class="container">
  <a href="dashboard.php">⬅ Back to Dashboard</a><br><br>
  <a href="add_appointment.php" class="add">Add Appointment</a>

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

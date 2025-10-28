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

// Debug check
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
      background: #f1f2f3ff;
      color: black;
      margin: 0;
      padding: 0;
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
    table {
      width: 90%;
      margin: 40px auto;
      border-collapse: collapse;
      background: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
    tr:nth-child(even) {
      background: #f9f9f9;
    }
    .pending { color: orange; font-weight: bold; }
    .completed { color: green; font-weight: bold; }
    .cancelled { color: red; font-weight: bold; }

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

.action-buttons a i {
    font-size: 14px;
}
  </style>
</head>

<body>
  <header>
    <h1><span class="first-word-title">SQL</span> <span class="second-word-title">Aircons</span></h1>
    <h1>Appointments</h1>
  </header>

  <div class="nav-links">
    <a href="home.php">Home</a>
    <a href="services.php">Services</a>
    <a href="billing.php">Billing</a>
  </div>

  <table>
    <tr>
      <th>Service</th>
      <th>Date</th>
      <th>Appointment Status</th>
      <th>Billing Amount</th>
      <th>Billing Status</th>
      <th>Payment Method</th>
    </tr>

    <?php if (empty($appointments)): ?>
      <tr><td colspan="6" style="text-align:center;">No appointments yet.</td></tr>
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

</body>
</html>

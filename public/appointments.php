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

// Fetch appointments
$sql = "
  SELECT 
    a.id AS appointment_id,
    s.name AS service_name,
    a.appointment_date,
    a.appointment_time,
    a.status
  FROM appointments a
  JOIN services s ON a.service_id = s.id
  WHERE a.user_id = ?
  ORDER BY a.appointment_date DESC
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
  die("SQL Prepare failed: " . $conn->error);
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SQL Aircons | My Appointments</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f8fafc;
      color: #111827;
      margin: 0;
      padding: 0;
      animation: fadeIn 0.6s ease-in-out;
    }

    header {
      background: white;
      padding: 25px 50px;
      box-shadow: 0 1px 6px rgba(0, 0, 0, 0.1);
      text-align: center;
      animation: slideDown 0.8s ease;
    }

    h1 {
      margin: 0;
      font-size: 32px;
    }

    .first-word-title {
      color: #2563eb;
    }

    .second-word-title {
      color: #111827;
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

    .nav-links a:hover {
      background: #2563eb;
      color: white;
      transform: translateY(-3px);
    }

    .nav-links a.active {
      background: #2563eb;
      color: white;
      font-weight: 600;
      transform: translateY(-3px);
    }

    main {
      width: 90%;
      margin: 60px auto;
      text-align: center;
      animation: fadeInUp 0.8s ease;
    }

    .welcome-text {
      font-size: 20px;
      color: #374151;
      margin-bottom: 40px;
      animation: fadeIn 1.2s ease;
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
      animation: fadeInUp 0.8s ease;
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
    .completed { color: #059669; font-weight: 600; }
    .cancelled { color: #dc2626; font-weight: 600; }

    .action-buttons {
      display: flex;
      gap: 8px;
      justify-content: center;
    }

    .action-buttons a {
      text-decoration: none;
      background-color: #e3ebf7;
      color: #333;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 14px;
      transition: 0.3s;
    }

    .action-buttons a:hover {
      background-color: #2563eb;
      color: white;
      transform: scale(1.05);
    }

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
    <a href="appointments.php" class="active">My Appointments</a>
    <a href="billing.php">Billing</a>
  </div>

  <main>

    <h2>My Appointments</h2>

    <table>
      <tr>
        <th>Service</th>
        <th>Date</th>
        <th>Time</th>
        <th>Status</th>
        <th>Action</th>
      </tr>

      <?php if (empty($appointments)): ?>
        <tr><td colspan="5" style="padding: 25px;">No appointments yet.</td></tr>
      <?php else: ?>
        <?php foreach ($appointments as $a): ?>
          <tr>
            <td><?= htmlspecialchars($a['service_name']) ?></td>
            <td><?= htmlspecialchars($a['appointment_date']) ?></td>
            <td><?= htmlspecialchars($a['appointment_time']) ?></td>
            <td class="<?= htmlspecialchars($a['status']) ?>">
              <?= ucfirst(htmlspecialchars($a['status'])) ?>
            </td>
            <td>
              <div class="action-buttons">
                <a href="edit_appointment.php?id=<?= $a['appointment_id'] ?>">✏️ Edit</a>
                <a href="delete_appointment.php?id=<?= $a['appointment_id'] ?>" onclick="return confirm('Are you sure you want to delete this appointment?');">🗑️ Delete</a>
              </div>
            </td>
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

<?php
include('../includes/auth.php');
include('../config/db.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: No appointment ID provided.");
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch appointment details
if (isset($_GET['id'])) {
    $appointment_id = (int)$_GET['id'];

    $stmt = $conn->prepare("
        SELECT a.*, s.name AS service_name 
        FROM appointments a
        JOIN services s ON a.service_id = s.id
        WHERE a.id = ? AND a.user_id = ?
    ");
    $stmt->bind_param("ii", $appointment_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $appointment = $result->fetch_assoc();
    $stmt->close();

    if (!$appointment) {
        die("Appointment not found or unauthorized access.");
    }
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_date = $_POST['appointment_date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE appointments SET appointment_date = ?, status = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssii", $new_date, $status, $appointment_id, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: appointments.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Appointment | SQL Aircons</title>
    <style>
        body { font-family: Arial; background: #f7f7f7; padding: 40px; }
        .form-container {
            background: white; padding: 20px; border-radius: 10px; width: 400px;
            margin: auto; box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        input, select, button {
            width: 100%; padding: 10px; margin-top: 10px;
            border-radius: 5px; border: 1px solid #ccc;
        }
        button {
            background: #007bff; color: white; cursor: pointer;
        }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Appointment</h2>
    <form method="POST">
        <label>Service</label>
        <input type="text" value="<?= htmlspecialchars($appointment['service_name']) ?>" disabled>

        <label>Appointment Date</label>
        <input type="datetime-local" name="appointment_date" value="<?= htmlspecialchars($appointment['appointment_date']) ?>" required>

        <label>Status</label>
        <select name="status">
            <option value="pending" <?= $appointment['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="completed" <?= $appointment['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
            <option value="cancelled" <?= $appointment['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>

        <button type="submit">Update</button>
    </form>
</div>

</body>
</html>

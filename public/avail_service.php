<?php
include('../includes/auth.php');
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['service_id']) && !empty($_POST['appointment_datetime'])) {
    $service_id = (int)$_POST['service_id'];
    $appointment_datetime = $_POST['appointment_datetime'];

    // Split into date and time
    $appointment_date = date('Y-m-d', strtotime($appointment_datetime));
    $appointment_time = date('H:i:s', strtotime($appointment_datetime));

    // Step 1: Fetch the service price
    $stmt = $conn->prepare("SELECT price FROM services WHERE id = ?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();
    $stmt->close();

    if (!$service) {
        die("Error: Service not found.");
    }

    $amount = $service['price'];

    // Step 2: Create appointment with both date and time
    $stmt = $conn->prepare("INSERT INTO appointments (user_id, service_id, appointment_date, appointment_time, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->bind_param("iiss", $user_id, $service_id, $appointment_date, $appointment_time);
    $stmt->execute();
    $appointment_id = $stmt->insert_id;
    $stmt->close();

    // Step 3: Create billing record
    $stmt = $conn->prepare("INSERT INTO billing (user_id, service_id, appointment_id, amount, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->bind_param("iiid", $user_id, $service_id, $appointment_id, $amount);
    $stmt->execute();
    $stmt->close();

    header("Location: billing.php");
    exit;

} else {
    echo "<p>Error: Missing service or appointment date.</p>";
}
?>
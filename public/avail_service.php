<?php
include('../includes/auth.php');
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if service_id and appointment date are provided
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['service_id']) && !empty($_POST['appointment_datetime'])) {
    $service_id = (int)$_POST['service_id'];
    $appointment_date = $_POST['appointment_datetime'] ?? '';

    //  Step 1: Fetch the service price
    $stmt = $conn->prepare("SELECT price FROM services WHERE id = ?");
    if (!$stmt) {
        die("SQL Error (Step 1): " . $conn->error);
    }
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();
    $stmt->close();

    if (!$service) {
    
        die("Error: Service not found.");
    }

    $amount = $service['price'];

    //  Step 2: Create appointment
    $stmt = $conn->prepare("INSERT INTO appointments (user_id, service_id, appointment_date, status) VALUES (?, ?, ?, 'pending')");
    if (!$stmt) {
        die("SQL Error (Step 2): " . $conn->error);
    }
    $stmt->bind_param("iis", $user_id, $service_id, $appointment_date);
    $stmt->execute();
    $appointment_id = $stmt->insert_id;
    $stmt->close();

    //  Step 3: Create billing record
    $stmt = $conn->prepare("INSERT INTO billing (user_id, service_id, appointment_id, amount, status) VALUES (?, ?, ?, ?, 'pending')");
    if (!$stmt) {
        die("SQL Error (Step 3): " . $conn->error);
    }
    $stmt->bind_param("iiid", $user_id, $service_id, $appointment_id, $amount);
    $stmt->execute();
    $billing_id = $stmt->insert_id;
    $stmt->close();

    //  Store last billing ID for billing page reference
    $_SESSION['last_billing_id'] = $billing_id;

    header("Location: billing.php");
    exit;

} else {
    echo "<p>Error: Missing service or appointment date.</p>";
}
?>


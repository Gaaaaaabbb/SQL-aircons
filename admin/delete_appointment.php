<?php
include('../config/db.php');
session_start();

// Restrict access to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

// Check if appointment ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_appointments.php");
    exit;
}

$appointment_id = $_GET['id'];

// Delete appointment securely
$stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();

header("Location: manage_appointments.php?msg=Appointment+deleted+successfully");
exit;
?>

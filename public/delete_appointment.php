<?php
include('../includes/auth.php');
include('../config/db.php');

if (!isset($_GET['id'])) {
    die("Error: Missing appointment ID.");
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: appointments.php");
exit;
?>

<?php
include('../includes/auth.php');
include('../config/db.php');

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

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

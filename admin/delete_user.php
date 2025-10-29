<?php
include('../config/db.php');
session_start();

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

// Check if `id` is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_users.php");
    exit;
}

$user_id = intval($_GET['id']);
$current_user_id = $_SESSION['user_id'];

// Prevent admin from deleting themselves
if ($user_id === $current_user_id) {
    header("Location: manage_users.php?error=cannot_delete_self");
    exit;
}

// Delete the user from the database
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Redirect back to manage users
header("Location: manage_users.php?msg=user_deleted");
exit;
?>

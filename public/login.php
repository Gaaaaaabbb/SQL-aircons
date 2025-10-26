<?php
session_start();
include('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            echo "<p>Invalid password.</p>";
        }
    } else {
        echo "<p> No user found with that email.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login - SQL Aircons</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <h2>Login</h2>
  <form method="POST" action="">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
  </form>
  <p>Don't have an account? <a href="register.php">Register</a></p>
</body>
</html>

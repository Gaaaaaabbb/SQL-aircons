<?php
include('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'customer'; // default role

    $sql = "INSERT INTO users (name, email, password, role)
            VALUES ('$name', '$email', '$password', '$role')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful! You can now log in.'); window.location='login.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register - SQL Aircons</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <h2>Register Account</h2>
  <form method="POST" action="">
    <input type="text" name="name" placeholder="Full Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Register</button>
  </form>
  <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>
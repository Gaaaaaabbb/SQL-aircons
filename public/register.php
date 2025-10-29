<?php
include('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'customer'; // default role

    $sql = "INSERT INTO users (name, email, password, role, created_at)
            VALUES ('$name', '$email', '$password', '$role', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful! You can now log in.'); window.location='login.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SQL Aircons | Register</title>
  <style>
    /* General Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Inter", Arial, sans-serif;
}

/* Background */
body {
  background-color: #f9fafb;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
}

/* Container */
.login-container {
  background-color: #fff;
  padding: 60px 80px;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

/* Logo and Header */
.login-box h1 {
  font-size: 28px;
  font-weight: 600;
  margin-bottom: 20px;
  color: #1f2937;
}

.login-box .brand {
  color: #3b82f6; /* Blue tone for SQL */
}

/* Sub-header */
.login-box h2 {
  font-size: 42px;
  font-weight: 700;
  margin-bottom: 30px;
  color: #111827;
}

/* Form */
form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

/* Inputs */
input {
  width: 320px;
  padding: 14px 16px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 16px;
  color: #111827;
  background-color: #f9fafb;
  transition: border 0.2s;
}

input:focus {
  outline: none;
  border-color: #3b82f6;
  background-color: #fff;
}

/* Button */
button {
  background-color: #3b82f6;
  color: white;
  padding: 14px;
  font-size: 16px;
  font-weight: 500;
  border: none;
  border-radius: 8px;
  cursor: pointer;

}

button:hover {
  background-color: #2563eb;
}

/* Forgot Password */
.forgot-password {
  display: block;
  margin-top: 10px;
  font-size: 14px;
  color: #374151;
  text-align: center;
  text-decoration: none;
  transition: color 0.2s;
}

.forgot-password:hover {
  color: #1d4ed8;
}

  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-box">
      <h1><span class="brand">SQL</span> Aircons</h1>
      <h2>Register</h2>
      <form method="POST" action="">
        <input type="text" name="name" placeholder="Full Name" required>       
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit">Register</button>
      </form>
    </div>
  </div>
</body>
</html>
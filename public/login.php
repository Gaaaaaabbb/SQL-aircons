<?php
session_start();
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        //verification
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: home.php");
            }
        } else {
            echo "<script> alert ('Invalid Password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('No user found with that email.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SQL Aircons | Log in</title>
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
  overflow: hidden;
}

/* Container */
.login-container {
  background-color: #fff;
  padding: 60px 80px;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  opacity: 0;
  transform: translateY(30px) scale(0.95);
  animation: fadeInUp 0.8s ease forwards;
}

/* Logo and Header */
.login-box h1 {
  font-size: 28px;
  font-weight: 600;
  margin-bottom: 20px;
  color: #1f2937;
  opacity: 0;
  transform: translateY(10px);
  animation: fadeInUp 0.6s ease forwards;
  animation-delay: 0.2s;
}

.login-box .brand {
  color: #3b82f6; /* SQL Blue */
}

/* Sub-header */
.login-box h2 {
  font-size: 42px;
  font-weight: 700;
  margin-bottom: 30px;
  color: #111827;
  opacity: 0;
  transform: translateY(10px);
  animation: fadeInUp 0.6s ease forwards;
  animation-delay: 0.3s;
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
  transition: all 0.2s;
  opacity: 0;
  transform: translateX(-20px);
  animation: slideInFromLeft 0.6s ease forwards;
}

input:nth-of-type(1) {
  animation-delay: 0.4s;
}

input:nth-of-type(2) {
  animation-delay: 0.5s;
}

input:focus {
  outline: none;
  border-color: #3b82f6;
  background-color: #fff;
  transform: translateX(0) scale(1.02);
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
  transition: all 0.3s ease;
  opacity: 0;
  transform: translateY(10px);
  animation: fadeInUp 0.6s ease forwards;
  animation-delay: 0.6s;
}

button:hover {
  background-color: #2563eb;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

button:active {
  transform: translateY(0);
}

/* Register link */
form + p {
  margin-top: 20px;
  text-align: center;
  font-size: 14px;
  color: #6b7280;
  opacity: 0;
  animation: fadeIn 0.6s ease forwards;
  animation-delay: 0.7s;
}

form + p a {
  color: #3b82f6;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.2s;
}

form + p a:hover {
  color: #2563eb;
  text-decoration: underline;
}

/* Animations */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

@keyframes slideInFromLeft {
  from {
    opacity: 0;
    transform: translateX(-20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Responsive */
@media (max-width: 480px) {
  .login-container {
    padding: 40px 30px;
  }

  input {
    width: 100%;
  }

  .login-box h2 {
    font-size: 32px;
  }
}

  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-box">
      <h1><span class="brand">SQL</span> Aircons</h1>
      <h2>Log in</h2>
      <form method="POST" action="">
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name= "password" placeholder="Password" required />
        <button type="submit">Log in</button> <br>

      </form>
      <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
  </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SQL Aircons | Welcome</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #00b4d8, #0078d7);
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
      text-align: center;
    }

    h1 {
      font-size: 2.5em;
      margin-bottom: 10px;
    }

    p {
      font-size: 1.1em;
      margin-bottom: 30px;
    }

    .buttons {
      display: flex;
      gap: 20px;
    }

    a {
      text-decoration: none;
      color: white;
      background: rgba(255, 255, 255, 0.2);
      padding: 15px 40px;
      border-radius: 12px;
      font-size: 1.1em;
      transition: 0.3s;
    }

    a:hover {
      background: rgba(255, 255, 255, 0.4);
      transform: translateY(-2px);
    }

    footer {
      position: absolute;
      bottom: 20px;
      font-size: 0.9em;
      opacity: 0.8;
    }
  </style>
</head>
<body>

  <h1>Welcome to SQL Aircons </h1>
  <p>Your trusted partner for air conditioning installation, maintenance, and cleaning services.</p>

  <div class="buttons">
    <a href="login.php"> Log In</a>
    <a href="register.php"> Register</a>
  </div>

  <footer>© <?php echo date('Y'); ?> SQL Aircons. All Rights Reserved.</footer>

</body>
</html>

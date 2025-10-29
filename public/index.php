<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SQL Aircons | Welcome</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f9fafb;
      color: #111827;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      text-align: center;
      animation: fadeIn 0.8s ease;
    }

    header {
      position: absolute;
      top: 30px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 1.6em;
      font-weight: bold;
      animation: slideDown 0.6s ease;
    }

    .logo-first {
      color: #2563eb;
    }

    main {
      max-width: 700px;
      margin-top: 60px;
      animation: fadeUp 0.8s ease;
    }

    h1 {
      font-size: 3em;
      font-weight: 800;
      color: #111827;
      margin-bottom: 20px;
      line-height: 1.2;
    }

    p {
      font-size: 1.1em;
      color: #4b5563;
      margin-bottom: 40px;
    }

    .buttons {
      display: flex;
      justify-content: center;
      gap: 20px;
    }

    a {
      text-decoration: none;
      font-weight: 600;
      padding: 14px 38px;
      border-radius: 10px;
      transition: 0.3s ease;
      font-size: 1em;
    }

    .btn-login {
      background: #2563eb;
      color: white;
    }

    .btn-login:hover {
      background: #1e4ed8;
      transform: translateY(-3px);
    }

    .btn-register {
      background: white;
      color: #111827;
      border: 2px solid #e5e7eb;
    }

    .btn-register:hover {
      background: #f3f4f6;
      transform: translateY(-3px);
    }

    footer {
      position: absolute;
      bottom: 25px;
      font-size: 0.9em;
      color: #6b7280;
    }

    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideDown {
      from { transform: translateY(-20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    @media (max-width: 600px) {
      h1 {
        font-size: 2.2em;
      }

      .buttons {
        flex-direction: column;
        gap: 15px;
      }

      a {
        width: 200px;
        display: inline-block;
      }
    }
  </style>
</head>
<body>
  <header>
    <span class="logo-first">SQL</span> Aircons
  </header>

  <main>
    <h1>Your Trusted Aircon Partner</h1>
    <p>Professional aircon services for your home and business.</p>

    <div class="buttons">
      <a href="login.php" class="btn-login">Login</a>
      <a href="register.php" class="btn-register">Register</a>
    </div>
  </main>

  <footer>© <?php echo date('Y'); ?> SQL Aircons. All Rights Reserved.</footer>
</body>
</html>

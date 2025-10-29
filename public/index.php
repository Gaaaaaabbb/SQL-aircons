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
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      animation: fadeIn 0.8s ease;
    }

    header {
      padding: 40px 60px;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 42px;
      font-weight: bold;
      animation: slideDown 0.6s ease;
    }

    .logo-first {
      color: #2563eb;
    }

    main {
      flex: 1;
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      padding: 60px 80px;
      gap: 80px;
      animation: fadeUp 0.8s ease;
    }

    .dashboard-text {
      flex: 1;
      max-width: 600px;
    }

    .dashboard-text h1 {
      font-size: 68px;
      font-weight: 800;
      color: #1f2937;
      margin-bottom: 30px;
      line-height: 1.1;
    }

    .dashboard-text p {
      font-size: 24px;
      color: #4b5563;
      margin-bottom: 50px;
      line-height: 1.6;
    }

    .dashboard-cntnr {
      flex: 1;
      align-items: flex-start;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: -20px;
    }

    .dashboard-cntnr img {
      width: 100%;
      max-width: 500px;
      height: auto;
      filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.08));
    }

    .buttons {
      display: flex;
      gap: 20px;
    }

    a {
      text-decoration: none;
      font-weight: 600;
      padding: 18px 45px;
      border-radius: 12px;
      transition: all 0.3s ease;
      font-size: 18px;
    }

    .btn-login {
      background: #2563eb;
      color: white;
    }

    .btn-login:hover {
      background: #1e4ed8;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
    }

    .btn-register {
      background: white;
      color: #111827;
      border: 2px solid #e5e7eb;
    }

    .btn-register:hover {
      background: #f3f4f6;
      border-color: #d1d5db;
      transform: translateY(-2px);
    }

    footer {
      padding: 30px 60px;
      text-align: center;
      font-size: 14px;
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
      from { transform: translateX(-50%) translateY(-20px); opacity: 0; }
      to { transform: translateX(-50%) translateY(0); opacity: 1; }
    }

    @media (max-width: 1024px) {
      main {
        flex-direction: column;
        text-align: center;
        padding: 40px;
      }

      .dashboard-text {
        max-width: 100%;
      }

      .dashboard-text h1 {
        font-size: 48px;
      }

      .dashboard-text p {
        font-size: 20px;
      }

      .buttons {
        justify-content: center;
      }

      header {
        padding: 30px 40px;
        font-size: 36px;
      }
    }

    @media (max-width: 600px) {
      .dashboard-text h1 {
        font-size: 36px;
      }

      .dashboard-text p {
        font-size: 18px;
      }

      .buttons {
        flex-direction: column;
        gap: 15px;
        width: 100%;
      }

      a {
        width: 100%;
        text-align: center;
      }

      header {
        font-size: 32px;
        padding: 20px 30px;
      }

      main {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>
  <header>
    <span class="logo-first">SQL</span> Aircons
  </header>

  <main>
    <div class="dashboard-text">
      <h1>Your Trusted Aircon Partner</h1>
      <p>Professional aircon services for your home and business.</p>
      <div class="buttons">
        <a href="login.php" class="btn-login">Login</a>
        <a href="register.php" class="btn-register">Register</a>
      </div>
    </div>

    <div class="dashboard-cntnr">
      <img src="aircon.png" alt="aircon">
    </div>
  </main>

  <footer>© <?php echo date('Y'); ?> SQL Aircons. All Rights Reserved.</footer>
</body>
</html>
<?php
include('../includes/auth.php');
include('../config/db.php');

$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT name FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query);
$username = $user['name'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SQL Aircons | Dashboard</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f8fafc;
      color: #111827;
      margin: 0;
      padding: 0;
      overflow-x: hidden;
    }

    header {
      background: white;
      padding: 25px 50px;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
      text-align: center;
      animation: fadeDown 0.8s ease forwards;
    }

    h1 {
      margin: 0;
      font-size: 36px;
    }

    .first-word-title {
      color: #2563eb;
    }

    .second-word-title {
      color: #111827;
    }

    nav {
      display: flex;
      justify-content: center;
      background: white;
      padding: 15px 0;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
      gap: 25px;
      opacity: 0;
      transform: translateY(-10px);
      animation: fadeUp 0.9s ease forwards;
      animation-delay: 0.3s;
    }

    nav a {
      color: #111827;
      text-decoration: none;
      font-size: 17px;
      font-weight: 500;
      padding: 10px 25px;
      border-radius: 10px;
      transition: 0.3s ease;
    }

    nav a:hover {
      background: #2563eb;
      color: white;
      transform: scale(1.05);
    }

    main {
      max-width: 1200px;
      margin: 0 auto;
      padding: 60px 20px;
      opacity: 0;
      transform: translateY(20px);
      animation: fadeUp 1s ease forwards;
      animation-delay: 0.6s;
    }

    .welcome-section {
      text-align: center;
      margin-bottom: 60px;
      background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
      border-radius: 20px;
      padding: 50px 30px;
      position: relative;
      overflow: hidden;
    }

    .welcome-section::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -10%;
      width: 300px;
      height: 300px;
      background: radial-gradient(circle, rgba(37, 99, 235, 0.1) 0%, transparent 70%);
      border-radius: 50%;
    }

    .welcome-section::after {
      content: '';
      position: absolute;
      bottom: -50%;
      left: -10%;
      width: 300px;
      height: 300px;
      background: radial-gradient(circle, rgba(37, 99, 235, 0.08) 0%, transparent 70%);
      border-radius: 50%;
    }

    .welcome-content {
      position: relative;
      z-index: 1;
    }

    .welcome-text {
      font-size: 36px;
      font-weight: 700;
      background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 15px;
      line-height: 1.2;
    }

    .slogan {
      font-size: 19px;
      color: #4b5563;
      font-weight: 500;
    }

    /* Quick Action Cards */
    .cards-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 25px;
      margin-bottom: 60px;
    }

    .card {
      background: white;
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
      cursor: pointer;
      text-decoration: none;
      color: inherit;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      opacity: 0;
      animation: fadeUp 0.8s ease forwards;
    }

    .card:nth-child(1) { animation-delay: 0.8s; }
    .card:nth-child(2) { animation-delay: 0.9s; }
    .card:nth-child(3) { animation-delay: 1s; }
    .card:nth-child(4) { animation-delay: 1.1s; }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 8px 24px rgba(37, 99, 235, 0.15);
    }

    .card-icon {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
      font-size: 28px;
    }

    .card-title {
      font-size: 20px;
      font-weight: 600;
      color: #111827;
      margin-bottom: 10px;
    }

    .card-description {
      font-size: 15px;
      color: #6b7280;
      line-height: 1.5;
    }

    /* Info Section */
    .info-section {
      background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
      border-radius: 16px;
      padding: 40px;
      color: white;
      text-align: center;
      margin-bottom: 40px;
      opacity: 0;
      animation: fadeUp 0.8s ease forwards;
      animation-delay: 1.2s;
    }

    .info-section h2 {
      font-size: 28px;
      margin-bottom: 15px;
    }

    .info-section p {
      font-size: 16px;
      opacity: 0.95;
      line-height: 1.6;
      max-width: 700px;
      margin: 0 auto;
    }

    /* Features Grid */
    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 30px;
      margin-bottom: 40px;
      opacity: 0;
      animation: fadeUp 0.8s ease forwards;
      animation-delay: 1.3s;
    }

    .feature-item {
      background: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .feature-icon {
      font-size: 32px;
      margin-bottom: 15px;
    }

    .feature-title {
      font-size: 18px;
      font-weight: 600;
      color: #111827;
      margin-bottom: 8px;
    }

    .feature-text {
      font-size: 14px;
      color: #6b7280;
      line-height: 1.5;
    }

    footer {
      text-align: center;
      font-size: 0.9em;
      color: #6b7280;
      margin-top: 40px;
      padding-bottom: 30px;
      opacity: 0;
      animation: fadeIn 1s ease forwards;
      animation-delay: 1.4s;
    }

    /* Animations */
    @keyframes fadeDown {
      0% { opacity: 0; transform: translateY(-20px); }
      100% { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeUp {
      0% { opacity: 0; transform: translateY(20px); }
      100% { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @media (max-width: 768px) {
      header {
        padding: 20px;
      }

      h1 {
        font-size: 28px;
      }

      nav {
        flex-wrap: wrap;
        gap: 10px;
        padding: 15px 10px;
      }

      nav a {
        font-size: 15px;
        padding: 8px 15px;
      }

      main {
        padding: 40px 20px;
      }

      .welcome-text {
        font-size: 24px;
      }

      .slogan {
        font-size: 16px;
      }

      .cards-container {
        grid-template-columns: 1fr;
        gap: 20px;
      }

      .info-section {
        padding: 30px 20px;
      }

      .info-section h2 {
        font-size: 22px;
      }

      .features-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

  <header>
    <h1><span class="first-word-title">SQL </span><span class="second-word-title">Aircons</span></h1>
  </header>

  <nav>
    <a href="services.php">Services</a>
    <a href="appointments.php">My Appointments</a>
    <a href="billing.php">Billing</a>
    <a href="index.php">Logout</a>
  </nav>

  <main>
    <div class="welcome-section">
      <p class="welcome-text">Welcome back, <?php echo htmlspecialchars($username); ?></p>
      <p class="slogan">Manage your air conditioning services with ease.</p>
    </div>

    <!-- Quick Action Cards -->
    <div class="cards-container">
      <a href="services.php" class="card">
        <div class="card-icon">🔧</div>
        <div class="card-title">Browse Services</div>
        <p class="card-description">Explore our professional AC maintenance and repair services</p>
      </a>

      <a href="appointments.php" class="card">
        <div class="card-icon">📅</div>
        <div class="card-title">My Appointments</div>
        <p class="card-description">View and manage your scheduled service appointments</p>
      </a>

      <a href="billing.php" class="card">
        <div class="card-icon">💳</div>
        <div class="card-title">Billing</div>
        <p class="card-description">Check invoices and payment history for your services</p>
      </a>

      <a href="services.php" class="card">
        <div class="card-icon">📞</div>
        <div class="card-title">Request Service</div>
        <p class="card-description">Schedule a new appointment for AC maintenance or repair</p>
      </a>
    </div>

    <!-- Info Section -->
    <div class="info-section">
      <h2>Professional Air Conditioning Services</h2>
      <p>We provide top-quality installation, maintenance, and repair services for residential and commercial air conditioning systems. Our certified technicians ensure your comfort year-round with reliable and efficient service.</p>
    </div>

    <!-- Features -->
    <div class="features-grid">
      <div class="feature-item">
        <div class="feature-icon">⚡</div>
        <div class="feature-title">Fast Response</div>
        <p class="feature-text">Quick scheduling and same-day service available for urgent repairs</p>
      </div>

      <div class="feature-item">
        <div class="feature-icon">✅</div>
        <div class="feature-title">Certified Technicians</div>
        <p class="feature-text">Experienced professionals trained in all major AC brands and models</p>
      </div>

      <div class="feature-item">
        <div class="feature-icon">💰</div>
        <div class="feature-title">Transparent Pricing</div>
        <p class="feature-text">Upfront quotes with no hidden fees or surprise charges</p>
      </div>

      <div class="feature-item">
        <div class="feature-icon">🛡️</div>
        <div class="feature-title">Service Guarantee</div>
        <p class="feature-text">All work backed by our satisfaction guarantee and warranty</p>
      </div>
    </div>
  </main>

  <footer>
    <p>© <?php echo date("Y"); ?> SQL Aircons. All rights reserved.</p>
  </footer>

</body>
</html>
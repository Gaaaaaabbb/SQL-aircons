<?php
include('../includes/auth.php');
include('../config/db.php');

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

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
        * { box-sizing: border-box; }

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
        }

        h1 {
            margin: 0;
            font-size: 36px;
        }

        .first-word-title { color: #2563eb; }
        .second-word-title { color: #111827; }

    /* --- NAVIGATION --- */
    .nav-links {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 25px;
      background: white;
      padding: 15px 0;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
      animation: fadeInUp 1s ease;
    }

    .nav-links a {
      color: #111827;
      text-decoration: none;
      font-size: 17px;
      font-weight: 500;
      padding: 10px 25px;
      border-radius: 10px;
      transition: 0.3s;
    }

    .nav-links a:hover,
    .nav-links a.active {
      background: #2563eb;
      color: white;
      transform: translateY(-3px);
    }

        main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 20px;
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 60px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 20px;
            padding: 50px 30px;
        }

        .welcome-text {
            font-size: 36px;
            font-weight: 700;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 15px;
        }

        .slogan {
            font-size: 19px;
            color: #4b5563;
            font-weight: 500;
        }

        .section-title {
            text-align: center;
            font-size: 28px;
            color: #1e40af;
            margin-bottom: 40px;
        }

        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 30px rgba(37, 99, 235, 0.18);
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: contain;
            margin-bottom: 16px;
            background: #f9f9f9;
            border-radius: 10px;
        }

        /* HP Selector */
        .hp-selector {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }

        .hp-selector .arrow {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s;
        }

        .hp-selector .arrow:hover {
            background: #f0f0f0;
            color: #2563eb;
        }

        .hp-options {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .hp-options span {
            font-size: 14px;
            padding: 8px 16px;
            border-radius: 25px;
            cursor: pointer;
            color: #555;
            border: 2px solid #ddd;
            transition: all 0.2s;
            user-select: none;
        }

        .hp-options span.active {
            border-color: #e05a00;
            color: #e05a00;
            background: #fff8f0;
            font-weight: 600;
        }

        .product-info h3 {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 4px;
            color: #1a1a1a;
        }

        .product-info p {
            font-size: 13.5px;
            color: #666;
        }

        hr {
            border: none;
            border-top: 1px solid #eee;
            margin: 16px 0;
        }

        .features {
            list-style: none;
            margin-bottom: 22px;
            padding: 0;
        }

        .features li {
            font-size: 14px;
            color: #444;
            padding: 4px 0;
        }

        .features li::before {
            content: "✓ ";
            color: #2563eb;
        }

        .btn-learn {
            display: block;
            width: 100%;
            padding: 13px;
            background: #1a1a1a;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 14.5px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            margin-bottom: 10px;
            transition: 0.3s;
        }

        .btn-learn:hover { background: #333; }

        .btn-buy {
            display: block;
            width: 100%;
            padding: 13px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 14.5px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-buy:hover {
            background: #1d4ed8;
        }

        .price {
            font-size: 18px;
            font-weight: 700;
            color: #2563eb;
            margin: 10px 0;
        }

        @media (max-width: 768px) {
            header { padding: 20px; }
            h1 { font-size: 28px; }
            nav { flex-wrap: wrap; gap: 12px; padding: 15px 10px; }
            .cards-container { grid-template-columns: 1fr; }
    
        }
            /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes slideDown {
      from {
        transform: translateY(-20px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
}
    </style>
</head>
<body>

    <header>
        <h1><span class="first-word-title">SQL </span><span class="second-word-title">Aircons</span></h1>
    </header>

  <div class="nav-links">
    <a href="home.php" class="<?= basename($_SERVER['PHP_SELF']) == 'home.php' ? 'active' : '' ?>">Home</a>
    <a href="products.php" class="<?= basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : '' ?>">Products</a>
    <a href="services.php" class="<?= basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : '' ?>">Services</a>
    <a href="appointments.php" class="<?= basename($_SERVER['PHP_SELF']) == 'appointments.php' ? 'active' : '' ?>">My Appointments</a>
    <a href="billing.php" class="<?= basename($_SERVER['PHP_SELF']) == 'billing.php' ? 'active' : '' ?>">Billing</a>
  </div>


    <main>
        <div class="welcome-section">
            <h2 class="welcome-text">Welcome back, <?php echo htmlspecialchars($username); ?>!</h2>
            <p class="slogan">Premium air conditioning solutions for your perfect comfort</p>
        </div>

        <h2 class="section-title">Featured Air Conditioners</h2>

        <div class="cards-container">

            <?php
            $products = [
                [
                    "image" => "daikin_split.jpg",
                    "hp_options" => ["1.0HP", "1.5HP"],
                    "selected_hp" => "1.0HP",
                    "series" => "Daikin Split Type",
                    "model" => "TAC-09CWI/UJE",
                    "features" => ["AI Inverter", "Whisper Quiet", "Smart WiFi Control"],
                    "prices" => ["1.0HP" => 32900, "1.5HP" => 42500]
                ],
                [
                    "image" => "lg_split.png",
                    "hp_options" => ["1.5HP", "2.0HP"],
                    "selected_hp" => "1.5HP",
                    "series" => "LG Split type",
                    "model" => "LG-191910",
                    "features" => ["Energy Saving", "Fast Cooling", "4-Way Swing"],
                    "prices" => ["1.5HP" => 32900, "2.0HP" => 38900]
                ],
                [
                    "image" => "lg_window.png",
                    "hp_options" => ["1.0HP", "1.5HP", "2.0HP"],
                    "selected_hp" => "2.0HP",
                    "series" => "LG Window type",
                    "model" => "LGW-09082",
                    "features" => ["Turbo Mode", "Self-Cleaning", "Eco Friendly"],
                    "prices" => ["1.0HP" => 21900, "1.5HP" => 28900, "2.0HP" => 33900]
                ],
                [
                    "image" => "portable1.png",
                    "hp_options" => ["0.75HP", "1.0HP"],
                    "selected_hp" => "1.0HP",
                    "series" => "Portable Series",
                    "model" => "TAC-07MINI",
                    "features" => ["Space Saving", "Low Noise", "Portable"],
                    "prices" => ["0.75HP" => 18500, "1.0HP" => 22900]
                ],
                [
                    "image" => "tcl_center.png",
                    "hp_options" => ["2.0HP", "2.5HP"],
                    "selected_hp" => "2.5HP",
                    "series" => "TCL Heavy Duty",
                    "model" => "TAC-24HDI",
                    "features" => ["High Capacity", "Strong Airflow", "Commercial Grade"],
                    "prices" => ["2.0HP" => 35900, "2.5HP" => 42900]
                ],
                [
                    "image" => "tcl_window.png",
                    "hp_options" => ["1.5HP"],
                    "selected_hp" => "1.5HP",
                    "series" => "TCL Window type",
                    "model" => "TAC-12SMT",
                    "features" => ["Energy-Saving", "Fast-cooling", "Air Purification"],
                    "prices" => ["1.5HP" => 25900]
                ]
            ];

            foreach ($products as $index => $product):
                $current_price = $product['prices'][$product['selected_hp']];
            ?>
                <div class="card" data-index="<?php echo $index; ?>">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                         alt="<?php echo htmlspecialchars($product['series']); ?>">

                    <!-- Interactive HP Selector -->
                    <div class="hp-selector">
                        <button class="arrow left-arrow">&#8249;</button>
                        <div class="hp-options">
                            <?php foreach ($product['hp_options'] as $hp): ?>
                                <span class="<?php echo ($hp === $product['selected_hp']) ? 'active' : ''; ?>" 
                                      data-hp="<?php echo htmlspecialchars($hp); ?>"
                                      data-price="<?php echo $product['prices'][$hp]; ?>">
                                    <?php echo htmlspecialchars($hp); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                        <button class="arrow right-arrow">&#8250;</button>
                    </div>

                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($product['series']); ?></h3>
                        <p><?php echo htmlspecialchars($product['model']); ?></p>
                        <div class="price" id="price-<?php echo $index; ?>">
                            ₱<?php echo number_format($current_price, 0); ?>
                        </div>
                    </div>

                    <hr>

                    <ul class="features">
                        <?php foreach ($product['features'] as $feature): ?>
                            <li><?php echo htmlspecialchars($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <!-- Buy Now Button -->
                    <button class="btn-buy buy-btn"
                            data-series="<?php echo htmlspecialchars($product['series']); ?>"
                            data-model="<?php echo htmlspecialchars($product['model']); ?>">
                        Buy Now
                    </button>
                </div>
            <?php endforeach; ?>

        </div>
    </main>

    <footer style="text-align: center; padding: 30px 20px; color: #6b7280; margin-top: 50px;">
        <p>&copy; <?php echo date("Y"); ?> SQL Aircons. All rights reserved.</p>
    </footer>

    <!-- JavaScript - Updated to handle price dynamically -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.card');

            cards.forEach(card => {
                const hpContainer = card.querySelector('.hp-options');
                const spans = hpContainer.querySelectorAll('span');
                const leftArrow = card.querySelector('.left-arrow');
                const rightArrow = card.querySelector('.right-arrow');
                const buyBtn = card.querySelector('.buy-btn');
                const priceDisplay = card.querySelector('.price');

                let activeIndex = Array.from(spans).findIndex(span => span.classList.contains('active'));

                // HP Selector + Live Price Update
                function setActive(index) {
                    activeIndex = index;
                    spans.forEach((span, i) => {
                        span.classList.toggle('active', i === index);
                    });
                    // Update displayed price
                    const selectedPrice = parseInt(spans[index].dataset.price);
                    priceDisplay.textContent = '₱' + selectedPrice.toLocaleString('en-PH');
                }

                spans.forEach((span, index) => {
                    span.addEventListener('click', () => setActive(index));
                });

                leftArrow.addEventListener('click', () => {
                    activeIndex = (activeIndex - 1 + spans.length) % spans.length;
                    setActive(activeIndex);
                });

                rightArrow.addEventListener('click', () => {
                    activeIndex = (activeIndex + 1) % spans.length;
                    setActive(activeIndex);
                });

                // Buy Now Button - Pass selected HP and price to confirm_purchase.php
                buyBtn.addEventListener('click', () => {
                    const activeSpan = card.querySelector('.hp-options .active');
                    const selectedHP = activeSpan ? activeSpan.getAttribute('data-hp') : '';
                    const selectedPrice = activeSpan ? activeSpan.getAttribute('data-price') : 0;
                    const series = buyBtn.getAttribute('data-series');
                    const model = buyBtn.getAttribute('data-model');

                    const url = `confirm_purchase.php?series=${encodeURIComponent(series)}&model=${encodeURIComponent(model)}&hp=${encodeURIComponent(selectedHP)}&price=${selectedPrice}`;
                    window.location.href = url;
                });
            });
        });
    </script>

</body>
</html>
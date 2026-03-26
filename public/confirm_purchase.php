<?php
include('../includes/auth.php');
include('../config/db.php');

if (!isset($_GET['series']) || !isset($_GET['model']) || !isset($_GET['hp']) || !isset($_GET['price'])) {
    header("Location: products.php");
    exit();
}

$series = mysqli_real_escape_string($conn, $_GET['series']);
$model  = mysqli_real_escape_string($conn, $_GET['model']);
$hp     = mysqli_real_escape_string($conn, $_GET['hp']);
$price  = (float)$_GET['price'];

$user_id  = $_SESSION['user_id'];
$username = $_SESSION['user_name'] ?? 'Valued Customer';

$error = '';

// Handle confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {

    $payment_method = $_POST['payment_method'] ?? '';
    $gcash_number   = trim($_POST['gcash_number'] ?? '');

    // Validate payment method
    if (!in_array($payment_method, ['cash', 'gcash'])) {
        $error = 'Please select a payment method.';
    } elseif ($payment_method === 'gcash') {
        if (empty($gcash_number)) {
            $error = 'Please enter your GCash number.';
        } elseif (!preg_match('/^09\d{9}$/', $gcash_number)) {
            $error = 'Please enter a valid GCash number (e.g. 09XXXXXXXXX).';
        }
    }

    if (empty($error)) {
        if ($payment_method === 'gcash') {
            // Redirect to GCash payment page with all order details
            header("Location: gcash_payment.php?series=" . urlencode($series) .
                   "&model="        . urlencode($model) .
                   "&hp="           . urlencode($hp) .
                   "&price="        . $price .
                   "&gcash_number=" . urlencode($gcash_number));
            exit();
        }

        // Cash: insert directly
        $sql = "INSERT INTO orders (user_id, series, model, hp, total_amount, payment_method, status)
                VALUES (?, ?, ?, ?, ?, 'cash', 'confirmed')";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssd", $user_id, $series, $model, $hp, $price);

        if ($stmt->execute()) {
            header("Location: billing.php?success=1");
            exit();
        } else {
            $error = "Error placing order: " . $conn->error;
        }
        $stmt->close();
    }
}

$selected_payment = $_POST['payment_method'] ?? 'cash';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Purchase - SQL Aircons</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .container {
            background: #fff;
            max-width: 460px;
            width: 100%;
            border-radius: 16px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }

        h1 { color: #1e40af; margin-bottom: 8px; font-size: 24px; }

        .subtitle { color: #4b5563; font-size: 14px; margin-bottom: 24px; }

        /* Order summary */
        .info-box {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            text-align: left;
            font-size: 14px;
            line-height: 1.9;
            color: #1e293b;
        }

        .info-box .total {
            border-top: 1px solid #bae6fd;
            margin-top: 10px;
            padding-top: 10px;
        }

        /* Payment section */
        .payment-label {
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 10px;
        }

        .payment-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 16px;
        }

        .payment-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1.5px solid #d1d5db;
            background: #fff;
            cursor: pointer;
            text-align: left;
            transition: border-color 0.15s, background 0.15s;
        }

        .payment-option.active {
            border-color: #2563eb;
            background: #eff6ff;
        }

        .payment-option input[type="radio"] { display: none; }

        .radio-circle {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: border-color 0.15s;
        }

        .payment-option.active .radio-circle { border-color: #2563eb; }

        .radio-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: transparent;
            transition: background 0.15s;
        }

        .payment-option.active .radio-dot { background: #2563eb; }

        .payment-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        .icon-cash  { background: #dcfce7; }
        .icon-gcash { background: #dbeafe; }

        .payment-info .name {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }

        .payment-info .desc {
            font-size: 12px;
            color: #6b7280;
        }

        /* GCash number input */
        .gcash-field {
            display: none;
            text-align: left;
            margin-bottom: 16px;
        }

        .gcash-field.visible { display: block; }

        .gcash-field label {
            font-size: 12px;
            color: #6b7280;
            display: block;
            margin-bottom: 5px;
        }

        .gcash-field input {
            width: 100%;
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 14px;
            outline: none;
            transition: border-color 0.15s;
        }

        .gcash-field input:focus { border-color: #2563eb; }

        /* Buttons */
        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 8px;
        }

        .btn-cancel, .btn-confirm {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 30px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s;
        }

        .btn-cancel {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-cancel:hover { background: #d1d5db; }

        .btn-confirm {
            flex: 1.4;
            background: #2563eb;
            color: #fff;
        }

        .btn-confirm:hover { background: #1d4ed8; }

        /* Alerts */
        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fca5a5;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 13px;
            text-align: left;
            margin-bottom: 16px;
        }

        .success {
            color: #166534;
            background: #f0fdf4;
            padding: 25px;
            border-radius: 12px;
            margin: 20px 0;
        }
    </style>
</head>
<body>

<div class="container">

    <?php if (isset($success)): ?>
        <div class="success">
            <h2>✅ Order Placed Successfully!</h2>
            <p>Your order has been saved and can now be viewed in Billing.</p>
        </div>
        <a href="billing.php" style="display:inline-block; padding:14px 32px; background:#2563eb; color:#fff; text-decoration:none; border-radius:30px; font-weight:600;">
            Go to Billing
        </a>

    <?php else: ?>

        <h1>Confirm Your Purchase</h1>
        <p class="subtitle">Please review your order details</p>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="info-box">
            <div><strong>Customer:</strong> <?= htmlspecialchars($username) ?></div>
            <div><strong>Product:</strong>  <?= htmlspecialchars($series) ?></div>
            <div><strong>Model:</strong>    <?= htmlspecialchars($model) ?></div>
            <div><strong>Capacity:</strong> <?= htmlspecialchars($hp) ?></div>
            <div class="total"><strong>Total Amount:</strong> ₱<?= number_format($price, 0) ?></div>
        </div>

        <form method="POST" action="">
            <p class="payment-label">Payment method</p>

            <div class="payment-options">

                <label class="payment-option <?= $selected_payment === 'cash' ? 'active' : '' ?>">
                    <input type="radio" name="payment_method" value="cash"
                           <?= $selected_payment === 'cash' ? 'checked' : '' ?> />
                    <div class="radio-circle"><div class="radio-dot"></div></div>
                    <div class="payment-icon icon-cash">💵</div>
                    <div class="payment-info">
                        <div class="name">Cash</div>
                        <div class="desc">Pay in person</div>
                    </div>
                </label>

                <label class="payment-option <?= $selected_payment === 'gcash' ? 'active' : '' ?>">
                    <input type="radio" name="payment_method" value="gcash"
                           <?= $selected_payment === 'gcash' ? 'checked' : '' ?> />
                    <div class="radio-circle"><div class="radio-dot"></div></div>
                    <div class="payment-icon icon-gcash">📱</div>
                    <div class="payment-info">
                        <div class="name">GCash</div>
                        <div class="desc">Pay via GCash e-wallet</div>
                    </div>
                </label>

            </div>

            <div class="gcash-field <?= $selected_payment === 'gcash' ? 'visible' : '' ?>" id="gcash-field">
                <label for="gcash_number">GCash number</label>
                <input type="text" id="gcash_number" name="gcash_number"
                       placeholder="09XX XXX XXXX" maxlength="11"
                       value="<?= htmlspecialchars($_POST['gcash_number'] ?? '') ?>" />
            </div>

            <div class="btn-group">
                <a href="products.php" class="btn-cancel">Cancel</a>
                <button type="submit" name="confirm" class="btn-confirm">Confirm Purchase</button>
            </div>

        </form>

    <?php endif; ?>

</div>

<script>
    const radios = document.querySelectorAll('input[type="radio"][name="payment_method"]');
    const gcashField = document.getElementById('gcash-field');

    radios.forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.payment-option').forEach(function(opt) {
                opt.classList.remove('active');
            });
            radio.closest('.payment-option').classList.add('active');
            gcashField.classList.toggle('visible', radio.value === 'gcash');
        });
    });
</script>

</body>
</html>
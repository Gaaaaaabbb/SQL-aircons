<?php
include('../includes/auth.php');
include('../config/db.php');

// Require all order params
if (!isset($_GET['series'], $_GET['model'], $_GET['hp'], $_GET['price'])) {
    header("Location: dashboard.php");
    exit();
}

$series   = mysqli_real_escape_string($conn, $_GET['series']);
$model    = mysqli_real_escape_string($conn, $_GET['model']);
$hp       = mysqli_real_escape_string($conn, $_GET['hp']);
$price    = (float)$_GET['price'];
$user_id  = $_SESSION['user_id'];
$username = $_SESSION['user_name'] ?? 'Valued Customer';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_gcash'])) {
    $ref_number = trim($_POST['reference_number'] ?? '');

    if (empty($ref_number)) {
        $error = 'Please enter your GCash reference number.';
    } elseif (!preg_match('/^\d{13}$/', $ref_number)) {
        $error = 'Reference number must be exactly 13 digits.';
    } else {
        $sql = "INSERT INTO orders (user_id, series, model, hp, total_amount, payment_method, gcash_number, gcash_reference, status)
                VALUES (?, ?, ?, ?, ?, 'gcash', ?, ?, 'confirmed')";
        $stmt = $conn->prepare($sql);
        $gcash_number = $_GET['gcash_number'] ?? null;
        $stmt->bind_param("isssdss", $user_id, $series, $model, $hp, $price, $gcash_number, $ref_number);

        if ($stmt->execute()) {
            header("Location: billing.php?success=1");
            exit();
        } else {
            $error = "Error placing order: " . $conn->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GCash Payment - SQL Aircons</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 40px 36px;
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        /* GCash brand header */
        .gcash-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 6px;
        }

        .gcash-logo {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #007DFF, #0055CC);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .gcash-title {
            font-size: 24px;
            font-weight: 700;
            color: #007DFF;
        }

        .gcash-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 28px;
        }

        /* Amount badge */
        .amount-badge {
            display: inline-block;
            background: #eff6ff;
            border: 1.5px solid #bfdbfe;
            border-radius: 40px;
            padding: 8px 22px;
            font-size: 20px;
            font-weight: 700;
            color: #1d4ed8;
            margin-bottom: 24px;
        }

        /* QR box */
        .qr-wrapper {
            background: #f9fafb;
            border: 1.5px dashed #d1d5db;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
        }

        .qr-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #9ca3af;
            margin-bottom: 12px;
        }

        .qr-image {
            width: 180px;
            height: 180px;
            border-radius: 12px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
            /* Placeholder style if no image */
            background: #e5e7eb;
        }

        /* QR placeholder (shown when no actual QR image) */
        .qr-placeholder {
            width: 180px;
            height: 180px;
            margin: 0 auto;
            border-radius: 12px;
            background: #fff;
            border: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .qr-placeholder svg {
            width: 120px;
            height: 120px;
            color: #374151;
        }

        .qr-placeholder-text {
            font-size: 11px;
            color: #9ca3af;
        }

        .gcash-name {
            margin-top: 12px;
            font-size: 13px;
            color: #374151;
        }

        .gcash-name strong {
            color: #007DFF;
        }

        /* Steps */
        .steps {
            text-align: left;
            background: #f0f7ff;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 22px;
            font-size: 13px;
            color: #374151;
            line-height: 2;
        }

        .steps-title {
            font-weight: 600;
            color: #1d4ed8;
            margin-bottom: 4px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .steps ol {
            padding-left: 18px;
        }

        /* Reference input */
        .ref-label {
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            display: block;
            margin-bottom: 6px;
        }

        .ref-input-wrap {
            position: relative;
            margin-bottom: 8px;
        }

        .ref-input-wrap input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border-radius: 10px;
            border: 1.5px solid #d1d5db;
            font-size: 15px;
            letter-spacing: 1px;
            outline: none;
            transition: border-color 0.15s;
            font-family: 'Segoe UI', monospace;
        }

        .ref-input-wrap input:focus {
            border-color: #007DFF;
        }

        .ref-input-wrap .input-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: #9ca3af;
        }

        .ref-hint {
            font-size: 11px;
            color: #9ca3af;
            text-align: left;
            margin-bottom: 20px;
        }

        /* Alert */
        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fca5a5;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 13px;
            text-align: left;
            margin-bottom: 16px;
        }

        /* Buttons */
        .btn-group {
            display: flex;
            gap: 10px;
        }

        .btn {
            flex: 1;
            padding: 13px;
            border: none;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s, opacity 0.15s;
        }

        .btn-back {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-back:hover { background: #d1d5db; }

        .btn-submit {
            flex: 1.6;
            background: #007DFF;
            color: #fff;
        }

        .btn-submit:hover { background: #0066dd; }

        .security-note {
            margin-top: 18px;
            font-size: 11px;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
    </style>
</head>
<body>

<div class="card">

    <div class="gcash-header">
        <div class="gcash-logo">G</div>
        <span class="gcash-title">GCash</span>
    </div>
    <p class="gcash-subtitle">Scan the QR code to complete your payment</p>

    <div class="amount-badge">₱<?= number_format($price, 0) ?></div>

    <?php if (!empty($error)): ?>
        <div class="alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="qr-wrapper">
        <p class="qr-label">Scan with GCash App</p>

        <?php
        // Replace the src below with your actual QR code image path
        // e.g. '../assets/gcash_qr.png'
        $qr_image_path = '../assets/gcash_qr.png';
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/SQL-aircons/assets/gcash_qr.png')): ?>
            <img src="<?= $qr_image_path ?>" alt="GCash QR Code" class="qr-image" />
        <?php else: ?>
            <!-- QR Code placeholder — replace with your actual QR image -->
            <div class="qr-placeholder">
                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Top-left finder -->
                    <rect x="10" y="10" width="25" height="25" rx="3" fill="#111"/>
                    <rect x="14" y="14" width="17" height="17" rx="2" fill="#fff"/>
                    <rect x="18" y="18" width="9" height="9" fill="#111"/>
                    <!-- Top-right finder -->
                    <rect x="65" y="10" width="25" height="25" rx="3" fill="#111"/>
                    <rect x="69" y="14" width="17" height="17" rx="2" fill="#fff"/>
                    <rect x="73" y="18" width="9" height="9" fill="#111"/>
                    <!-- Bottom-left finder -->
                    <rect x="10" y="65" width="25" height="25" rx="3" fill="#111"/>
                    <rect x="14" y="69" width="17" height="17" rx="2" fill="#fff"/>
                    <rect x="18" y="73" width="9" height="9" fill="#111"/>
                    <!-- Data dots -->
                    <rect x="42" y="10" width="6" height="6" fill="#111"/>
                    <rect x="52" y="10" width="6" height="6" fill="#111"/>
                    <rect x="42" y="20" width="6" height="6" fill="#111"/>
                    <rect x="42" y="42" width="6" height="6" fill="#111"/>
                    <rect x="52" y="42" width="6" height="6" fill="#111"/>
                    <rect x="62" y="42" width="6" height="6" fill="#111"/>
                    <rect x="52" y="52" width="6" height="6" fill="#111"/>
                    <rect x="62" y="52" width="6" height="6" fill="#111"/>
                    <rect x="42" y="62" width="6" height="6" fill="#111"/>
                    <rect x="62" y="62" width="6" height="6" fill="#111"/>
                    <rect x="42" y="72" width="6" height="6" fill="#111"/>
                    <rect x="52" y="72" width="6" height="6" fill="#111"/>
                    <rect x="62" y="72" width="6" height="6" fill="#111"/>
                    <rect x="42" y="82" width="6" height="6" fill="#111"/>
                    <rect x="62" y="82" width="6" height="6" fill="#111"/>
                    <rect x="72" y="42" width="6" height="6" fill="#111"/>
                    <rect x="82" y="42" width="6" height="6" fill="#111"/>
                    <rect x="72" y="52" width="6" height="6" fill="#111"/>
                    <rect x="82" y="62" width="6" height="6" fill="#111"/>
                    <rect x="72" y="72" width="6" height="6" fill="#111"/>
                    <rect x="82" y="72" width="6" height="6" fill="#111"/>
                    <rect x="72" y="82" width="6" height="6" fill="#111"/>
                    <rect x="10" y="42" width="6" height="6" fill="#111"/>
                    <rect x="20" y="42" width="6" height="6" fill="#111"/>
                    <rect x="30" y="42" width="6" height="6" fill="#111"/>
                    <rect x="10" y="52" width="6" height="6" fill="#111"/>
                    <rect x="30" y="52" width="6" height="6" fill="#111"/>
                    <rect x="20" y="62" width="6" height="6" fill="#111"/>
                    <rect x="10" y="72" width="6" height="6" fill="#111"/>
                    <rect x="30" y="72" width="6" height="6" fill="#111"/>
                    <rect x="20" y="82" width="6" height="6" fill="#111"/>
                    <rect x="30" y="82" width="6" height="6" fill="#111"/>
                </svg>
                <span class="qr-placeholder-text">Place your QR image here</span>
            </div>
        <?php endif; ?>

        <p class="gcash-name">Send to: <strong>SQL Aircons</strong></p>
    </div>

    <div class="steps">
        <p class="steps-title">How to pay</p>
        <ol>
            <li>Open your <strong>GCash app</strong></li>
            <li>Tap <strong>Scan QR</strong> and scan the code above</li>
            <li>Enter the exact amount: <strong>₱<?= number_format($price, 0) ?></strong></li>
            <li>Copy the <strong>13-digit reference number</strong> from your receipt</li>
            <li>Paste it below and click <strong>Confirm</strong></li>
        </ol>
    </div>

    <form method="POST" action="">
        <label class="ref-label" for="reference_number">GCash Reference Number</label>
        <div class="ref-input-wrap">
            <span class="input-icon">🧾</span>
            <input
                type="text"
                id="reference_number"
                name="reference_number"
                placeholder="e.g. 1234567890123"
                maxlength="13"
                inputmode="numeric"
                pattern="\d{13}"
                value="<?= htmlspecialchars($_POST['reference_number'] ?? '') ?>"
                required
            />
        </div>
        <p class="ref-hint">Found in your GCash transaction receipt (13 digits)</p>

        <div class="btn-group">
            <a href="confirm_purchase.php?series=<?= urlencode($series) ?>&model=<?= urlencode($model) ?>&hp=<?= urlencode($hp) ?>&price=<?= $price ?>"
               class="btn btn-back">← Back</a>
            <button type="submit" name="submit_gcash" class="btn btn-submit">Confirm Payment</button>
        </div>
    </form>

    <p class="security-note">🔒 Secured by GCash · Do not share your reference number</p>
</div>

</body>
</html>

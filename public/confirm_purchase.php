<?php
include('../includes/auth.php');
include('../config/db.php');

if (!isset($_GET['series']) || !isset($_GET['model']) || !isset($_GET['hp']) || !isset($_GET['price'])) {
    header("Location: dashboard.php");
    exit();
}

$series = mysqli_real_escape_string($conn, $_GET['series']);
$model  = mysqli_real_escape_string($conn, $_GET['model']);
$hp     = mysqli_real_escape_string($conn, $_GET['hp']);
$price  = (float)$_GET['price'];

$user_id = $_SESSION['user_id'];
$username = $_SESSION['user_name'] ?? 'Valued Customer';

// Handle confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    
    $sql = "INSERT INTO orders (user_id, series, model, hp, total_amount, status) 
            VALUES (?, ?, ?, ?, ?, 'confirmed')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issid", $user_id, $series, $model, $hp, $price);
    
    if ($stmt->execute()) {
        // Redirect to billing.php after successful order
        header("Location: billing.php?success=1");
        exit();
    } else {
        $error = "Error placing order: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Purchase - SQL Aircons</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f8fafc;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            max-width: 460px;
            width: 100%;
            border-radius: 16px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 { color: #1e40af; margin-bottom: 10px; }
        .info-box {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
            text-align: left;
            line-height: 1.7;
        }
        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        button, .btn-back {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-confirm {
            background: #2563eb;
            color: white;
        }
        .btn-confirm:hover { background: #1d4ed8; }
        .btn-cancel {
            background: #e5e7eb;
            color: #374151;
        }
        .btn-cancel:hover { background: #d1d5db; }
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
            <a href="billing.php" style="display:inline-block; padding:14px 32px; background:#2563eb; color:white; text-decoration:none; border-radius:30px;">Go to Billing</a>
        <?php else: ?>
            <h1>Confirm Your Purchase</h1>
            <p style="color:#4b5563;">Please review your order details</p>

            <div class="info-box">
                <strong>Customer:</strong> <?php echo htmlspecialchars($username); ?><br>
                <strong>Product:</strong> <?php echo htmlspecialchars($series); ?><br>
                <strong>Model:</strong> <?php echo htmlspecialchars($model); ?><br>
                <strong>Capacity:</strong> <?php echo htmlspecialchars($hp); ?><br><br>
                <strong>Total Amount:</strong> ₱<?php echo number_format($price, 0); ?>
            </div>

            <div class="btn-group">
                <a href="dashboard.php" class="btn-back btn-cancel">Cancel</a>
                <form method="POST" style="flex:1;">
                    <button type="submit" name="confirm" class="btn-confirm">Confirm Purchase</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
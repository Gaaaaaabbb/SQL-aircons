<?php
include('../includes/auth.php');
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Always show all bills by this user
$sql = "
    SELECT b.*, s.name AS service_name
    FROM billing b
    JOIN services s ON b.service_id = s.id
    WHERE b.user_id = ?
    ORDER BY b.created_at DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$billings = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Billing | SQL Aircons</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        .pending {
            color: orange;
            font-weight: bold;
        }
        .paid {
            color: green;
            font-weight: bold;
        }
        .nav-links {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        .nav-links a {
            text-decoration: none;
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .nav-links a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<h1>SQL Aircons</h1>

<div class="nav-links">
    <a href="home.php">Home</a>
    <a href="services.php">Services</a>
    <a href="billing.php">Billing</a>
</div>

<h2>Your Billing Summary</h2>

<table>
    <tr>
        <th>Service</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Payment Method</th>
        <th>Date</th>
    </tr>

    <?php if (empty($billings)): ?>
        <tr>
            <td colspan="5" style="text-align:center;">No billing records found.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($billings as $bill): ?>
            <tr>
                <td><?= htmlspecialchars($bill['service_name']) ?></td>
                <td>₱<?= number_format($bill['total_amount'], 2) ?></td>
                <td>
                    <?php if (isset($bill['status']) && strtolower($bill['status']) === 'paid'): ?>
                        <span class="paid">Paid</span>
                    <?php else: ?>
                        <span class="pending">Pending</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($bill['payment_method'] ?? 'Cash') ?></td>
                <td><?= htmlspecialchars($bill['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

</body>
</html>

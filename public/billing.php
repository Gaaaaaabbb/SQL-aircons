<?php
// Show any hidden PHP errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection
include '../config/db.php';

// For testing — skip login and use a dummy user
session_start();
$user_id = 1;

// Try fetching from the database
$query = "SELECT * FROM billing WHERE user_id = '$user_id' ORDER BY order_date DESC";
$result = mysqli_query($conn, $query);

// If query fails (maybe table doesn't exist), show dummy data
$use_dummy = false;
if (!$result) {
    $use_dummy = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Billing</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; padding: 20px; }
        h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #007bff; color: white; }
        tr:nth-child(even) { background: #f2f2f2; }
        .pending { color: orange; font-weight: bold; }
        .paid { color: green; font-weight: bold; }
    </style>
</head>
<body>

<h2>Your Billing Summary (Test Mode)</h2>

<table>
    <tr>
        <th>Service</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Date</th>
    </tr>

    <?php
    if ($use_dummy) {
        // Show example data if DB not ready
        echo "
        <tr>
            <td>Installation</td>
            <td>₱1500.00</td>
            <td class='pending'>Pending</td>
            <td>2025-10-27 20:00:00</td>
        </tr>
        <tr>
            <td>Cleaning</td>
            <td>₱800.00</td>
            <td class='paid'>Paid</td>
            <td>2025-10-20 14:30:00</td>
        </tr>
        ";
    } else {
        // Use real billing data from the database
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $statusClass = strtolower($row['status']);
                echo "<tr>
                        <td>{$row['service_name']}</td>
                        <td>₱{$row['amount']}</td>
                        <td class='{$statusClass}'>{$row['status']}</td>
                        <td>{$row['order_date']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No billing records found.</td></tr>";
        }
    }
    ?>
</table>

</body>
</html>

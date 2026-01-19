<?php
session_start();
include "connect.php";

// Check if user is logged in and form submitted
if (!isset($_SESSION['uid']) || !isset($_POST['checkout_submit'])) {
    header("Location: checkout.php");
    exit();
}

$uid = mysqli_real_escape_string($con, $_SESSION['uid']);
$name = mysqli_real_escape_string($con, trim($_POST['nm']));
$mobile = mysqli_real_escape_string($con, trim($_POST['mo']));
$email = mysqli_real_escape_string($con, trim($_POST['em']));
$address = mysqli_real_escape_string($con, trim($_POST['ad']));

// Server-side validation
$errors = [];

if (empty($name) || strlen($name) < 2) {
    $errors[] = "Please enter a valid name";
}
if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
    $errors[] = "Name should contain only letters and spaces";
}
if (empty($mobile) || !preg_match('/^[0-9]{10}$/', $mobile)) {
    $errors[] = "Please enter a valid 10-digit mobile number";
}
if (empty($address) || strlen($address) < 10) {
    $errors[] = "Please enter a complete address";
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
    echo "<p><a href='checkout.php'>Go back to checkout</a></p>";
    exit();
}

// Check if cart has items
$cart_check = mysqli_query($con, "SELECT COUNT(*) as count FROM addcart WHERE u_id = '$uid'");
$cart_result = mysqli_fetch_array($cart_check);

if (!$cart_result || $cart_result['count'] == 0) {
    echo "<script>alert('Your cart is empty!'); window.location.href = 'menu.php';</script>";
    exit();
}

// Calculate total amount
$total_query = mysqli_query($con, "SELECT SUM(total) AS total FROM addcart WHERE u_id = '$uid'");
$totals = mysqli_fetch_assoc($total_query);
$grand_total = $totals['total'] ?? 0;

// Insert order into orders table
$order_insert = mysqli_query($con, "INSERT INTO orders (u_id, name, mobile, email, address, amount, order_date)
                                   VALUES ('$uid', '$name', '$mobile', '$email', '$address', '$grand_total', NOW())");

if (!$order_insert) {
    echo "<p style='color:red;'>Error placing your order. Please try again.</p>";
    echo "<p><a href='checkout.php'>Go back to checkout</a></p>";
    exit();
}

$order_id = mysqli_insert_id($con);

// Insert each cart item into order_items table
$cart_items = mysqli_query($con, "SELECT * FROM addcart WHERE u_id = '$uid'");
while ($item = mysqli_fetch_assoc($cart_items)) {
    $pid = $item['p_id'];
    $qty = $item['qty'];
    $item_total = $item['total'];

    mysqli_query($con, "INSERT INTO order_items (order_id, product_id, quantity, item_total)
                       VALUES ('$order_id', '$pid', '$qty', '$item_total')");
}

// Clear the cart
mysqli_query($con, "DELETE FROM addcart WHERE u_id = '$uid'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Maher Kathiyawadi Restaurant</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        .header {
            background: linear-gradient(135deg, #2e8b47, #27ae60);
            color: white;
            text-align: center;
            padding: 40px 30px;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="white" opacity="0.1"/><circle cx="80" cy="40" r="1.5" fill="white" opacity="0.1"/><circle cx="40" cy="60" r="1" fill="white" opacity="0.1"/><circle cx="70" cy="80" r="2.5" fill="white" opacity="0.1"/><circle cx="30" cy="80" r="1" fill="white" opacity="0.1"/></svg>');
            pointer-events: none;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 1;
        }

        .checkmark {
            width: 30px;
            height: 30px;
            border: 3px solid white;
            border-top: none;
            border-right: none;
            transform: rotate(-45deg);
            margin-top: -5px;
            margin-left: 5px;
        }

        .header h1 {
            font-size: 1.8em;
            font-weight: 600;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .header p {
            opacity: 0.9;
            font-size: 1em;
            position: relative;
            z-index: 1;
        }

        .content {
            padding: 40px 30px;
        }

        .order-info {
            text-align: center;
            margin-bottom: 30px;
        }

        .order-number {
            font-size: 2em;
            font-weight: 700;
            color: #2e8b47;
            margin-bottom: 10px;
        }

        .order-text {
            color: #666;
            font-size: 1.1em;
        }

        .details-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            border-left: 5px solid #2e8b47;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            color: #333;
            font-weight: 600;
        }

        .delivery-info {
            background: linear-gradient(135deg, #2e8b47, #27ae60);
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            margin: 20px 0;
        }

        .delivery-info h3 {
            font-size: 1.2em;
            margin-bottom: 5px;
        }

        .delivery-time {
            font-size: 1.5em;
            font-weight: 700;
        }

        .actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 15px 20px;
            border: none;
            border-radius: 12px;
            font-size: 1em;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2e8b47, #27ae60);
            color: white;
        }

        .btn-secondary {
            background: white;
            color: #2e8b47;
            border: 2px solid #2e8b47;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #27ae60, #2e8b47);
        }

        .btn-secondary:hover {
            background: #2e8b47;
            color: white;
        }

        .footer-message {
            text-align: center;
            color: #666;
            font-style: italic;
            margin-top: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 15px;
            }

            .header {
                padding: 30px 20px;
            }

            .content {
                padding: 30px 20px;
            }

            .actions {
                flex-direction: column;
            }

            .header h1 {
                font-size: 1.5em;
            }

            .order-number {
                font-size: 1.7em;
            }
        }

        /* CSS-only animations */
        .container {
            animation: slideUp 0.6s ease-out;
        }

        .success-icon {
            animation: bounce 0.8s ease-out 0.3s both;
        }

        .checkmark {
            animation: drawCheck 0.5s ease-out 0.8s both;
        }

        .order-number {
            animation: fadeIn 0.6s ease-out 1s both;
        }

        .details-card {
            animation: fadeIn 0.6s ease-out 1.2s both;
        }

        .delivery-info {
            animation: fadeIn 0.6s ease-out 1.4s both;
        }

        .actions {
            animation: fadeIn 0.6s ease-out 1.6s both;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounce {
            from {
                transform: scale(0);
            }
            50% {
                transform: scale(1.1);
            }
            to {
                transform: scale(1);
            }
        }

        @keyframes drawCheck {
            from {
                width: 0;
                height: 0;
            }
            to {
                width: 30px;
                height: 30px;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <div class="success-icon">
                <div class="checkmark"></div>
            </div>
            <h1>Order Placed Successfully!</h1>
            <p>Thank you for choosing Decent Restaurant</p>
        </div>

        <!-- Content Section -->
        <div class="content">
            <!-- Order Information -->
            <div class="order-info">
                <div class="order-number">#<?php echo $order_id; ?></div>
                <p class="order-text">Your order has been confirmed and is being prepared</p>
            </div>

            <!-- Order Details -->
            <div class="details-card">
                <div class="detail-row">
                    <span class="detail-label">Customer Name</span>
                    <span class="detail-value"><?php echo htmlspecialchars($name); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Mobile Number</span>
                    <span class="detail-value"><?php echo htmlspecialchars($mobile); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total Amount</span>
                    <span class="detail-value">₹<?php echo number_format($grand_total, 2); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Delivery Address</span>
                    <span class="detail-value"><?php echo htmlspecialchars(substr($address, 0, 30)) . (strlen($address) > 30 ? '...' : ''); ?></span>
                </div>
            </div>

            <!-- Delivery Estimate -->
            <div class="delivery-info">
                <h3>Estimated Delivery Time</h3>
                <div class="delivery-time">30-45 Minutes</div>
            </div>

            <!-- Action Buttons -->
            <div class="actions">
                <a href="menu.php" class="btn btn-primary">Keep Ordering</a>
            </div>

        </div>
    </div>
</body>
</html>

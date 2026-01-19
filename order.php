<?php 
session_start();

// Enhanced logging function
function logError($message, $data = []) {
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] $message";
    if (!empty($data)) {
        $log_entry .= " | Data: " . json_encode($data);
    }
    error_log($log_entry . PHP_EOL, 3, 'logs/order_errors.log');
}

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    echo "<script>
        alert('⚠️ Session expired! Please login again.');
        window.location.href = 'login.php';
    </script>";
    exit();
}

// Check if form was submitted properly
if (!isset($_POST['s']) || !isset($_POST['nm']) || !isset($_POST['mo']) || !isset($_POST['ad'])) {
    logError('Invalid form submission', $_POST);
    echo "<script>
        alert('❌ Invalid form submission! Please try again.');
        window.location.href = 'checkout.php';
    </script>";
    exit();
}

include "connect.php";

// Secure user ID handling
$uid = mysqli_real_escape_string($con, $_SESSION['uid']);
$nm = mysqli_real_escape_string($con, trim($_POST['nm']));
$mo = mysqli_real_escape_string($con, trim($_POST['mo']));
$em = mysqli_real_escape_string($con, trim($_POST['em']));
$ad = mysqli_real_escape_string($con, trim($_POST['ad']));

// Enhanced server-side validation
$validation_errors = [];

if (empty($nm) || strlen($nm) < 2) {
    $validation_errors[] = "Name must be at least 2 characters long";
}

if (!preg_match('/^[a-zA-Z\s]+$/', $nm)) {
    $validation_errors[] = "Name should contain only letters and spaces";
}

if (empty($mo) || !preg_match('/^[0-9]{10}$/', $mo)) {
    $validation_errors[] = "Mobile number must be exactly 10 digits";
}

if (empty($ad) || strlen($ad) < 10) {
    $validation_errors[] = "Address must be at least 10 characters long";
}

// If validation fails, return to checkout with errors
if (!empty($validation_errors)) {
    $error_message = implode("\\n• ", $validation_errors);
    logError('Validation failed', ['uid' => $uid, 'errors' => $validation_errors]);
    echo "<script>
        alert('❌ Please fix the following errors:\\n• $error_message');
        window.history.back();
    </script>";
    exit();
}

// START TRANSACTION FOR DATA CONSISTENCY
mysqli_autocommit($con, FALSE);

try {
    // Get cart items with JOIN to verify products exist
    $cart_query = "SELECT ac.*, m.title, m.price as menu_price 
                   FROM addcart ac 
                   INNER JOIN menu m ON ac.p_id = m.id 
                   WHERE ac.u_id = '$uid'";
    $cart_result = mysqli_query($con, $cart_query);

    if (!$cart_result) {
        throw new Exception("Database query failed: " . mysqli_error($con));
    }

    if (mysqli_num_rows($cart_result) == 0) {
        throw new Exception("Cart is empty");
    }

    $total_amount = 0;
    $order_items = [];
    $inserted_count = 0;

    // Process each cart item
    while ($cart_item = mysqli_fetch_array($cart_result)) {
        $p_id = $cart_item['p_id'];
        $item_total = $cart_item['total'];
        $total_amount += $item_total;
        
        // Store for logging
        $order_items[] = [
            'product_id' => $p_id,
            'title' => $cart_item['title'],
            'quantity' => $cart_item['qty'],
            'price' => $cart_item['price'],
            'total' => $item_total
        ];
        
        // Insert into checkout table
        $insert_query = "INSERT INTO checkout (p_id, u_id, name, mobile, email, location) 
                         VALUES ('$p_id', '$uid', '$nm', '$mo', '$em', '$ad')";
        
        if (!mysqli_query($con, $insert_query)) {
            throw new Exception("Failed to insert order item: " . mysqli_error($con));
        }
        
        $inserted_count++;
    }

    if ($inserted_count == 0) {
        throw new Exception("No items were processed");
    }

    // Clear the cart - CRITICAL STEP
    $clear_cart_query = "DELETE FROM addcart WHERE u_id = '$uid'";
    if (!mysqli_query($con, $clear_cart_query)) {
        throw new Exception("Failed to clear cart: " . mysqli_error($con));
    }

    // COMMIT TRANSACTION - Everything succeeded
    mysqli_commit($con);
    
    // Generate unique order reference
    $order_id = 'MKR' . date('YmdHis') . sprintf('%03d', $uid % 1000);
    $order_date = date('Y-m-d H:i:s');
    
    // Log successful order
    logError('Order placed successfully', [
        'order_id' => $order_id,
        'uid' => $uid,
        'customer' => $nm,
        'mobile' => $mo,
        'total' => $total_amount,
        'items_count' => $inserted_count
    ]);
    
    // Enhanced success message
    echo "<script>
        alert('🎉 ORDER CONFIRMED SUCCESSFULLY! 🎉\\n\\n' +
              '📋 Order Details:\\n' +
              '🆔 Order ID: $order_id\\n' +
              '👤 Customer: $nm\\n' +
              '📱 Mobile: $mo\\n' +
              '📧 Email: " . ($em ? $em : 'Not provided') . "\\n' +
              '📍 Address: " . substr($ad, 0, 50) . "...\\n' +
              '🛒 Items: $inserted_count\\n' +
              '💰 Total: ₹" . number_format($total_amount, 2) . "\\n\\n' +
              '✅ Your order has been placed successfully!\\n' +
              '🚚 Expected delivery: 30-45 minutes\\n' +
              '📞 Contact: +91 12345-67890\\n\\n' +
              'Thank you for choosing Maher Kathiyawadi Restaurant! 🙏');
        
        // Redirect after a delay to let user read the message
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 2000);
    </script>";

} catch (Exception $e) {
    // ROLLBACK TRANSACTION on any error
    mysqli_rollback($con);
    
    // Log the error
    logError('Order processing failed', [
        'uid' => $uid,
        'error' => $e->getMessage(),
        'customer' => $nm,
        'mobile' => $mo
    ]);
    
    // User-friendly error message
    echo "<script>
        alert('❌ ORDER PROCESSING FAILED\\n\\n' +
              'Error: We encountered an issue while processing your order.\\n\\n' +
              '🔄 What to do next:\\n' +
              '• Please try again in a few moments\\n' +
              '• Check your internet connection\\n' +
              '• Contact support if issue persists\\n\\n' +
              '📞 Support: +91 12345-67890\\n' +
              '📧 Email: support@maherkathiyawadi.com\\n\\n' +
              'We apologize for the inconvenience.');
        window.location.href = 'checkout.php';
    </script>";
    
} finally {
    // Reset autocommit regardless of success or failure
    mysqli_autocommit($con, TRUE);
}
?>

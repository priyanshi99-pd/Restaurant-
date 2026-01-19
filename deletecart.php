<?php
session_start();

// Enable error logging
function logCartDeletion($message, $data = []) {
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] CART DELETION: $message";
    if (!empty($data)) {
        $log_entry .= " | Data: " . json_encode($data);
    }
    error_log($log_entry . PHP_EOL, 3, 'logs/cart_operations.log');
}

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    logCartDeletion('Unauthorized deletion attempt', ['ip' => $_SERVER['REMOTE_ADDR']]);
    echo "<script>
        alert('❌ Session expired! Please login again.');
        window.location.href = 'login.php';
    </script>";
    exit();
}

// Check if cart ID is provided and valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    logCartDeletion('Missing cart ID parameter', ['uid' => $_SESSION['uid']]);
    echo "<script>
        alert('❌ Invalid request! No item specified.');
        window.location.href = 'cart.php';
    </script>";
    exit();
}

include "connect.php";

// Secure input handling
$cart_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
$uid = mysqli_real_escape_string($con, $_SESSION['uid']);

// Validate cart ID is a positive integer
if ($cart_id === false || $cart_id <= 0) {
    logCartDeletion('Invalid cart ID format', ['cart_id' => $_GET['id'], 'uid' => $_SESSION['uid']]);
    echo "<script>
        alert('❌ Invalid item ID!');
        window.location.href = 'cart.php';
    </script>";
    exit();
}

// Check if the cart item exists and belongs to the user BEFORE deletion
$verify_query = "SELECT id, p_id, qty, total FROM addcart WHERE id = $cart_id AND u_id = '$uid'";
$verify_result = mysqli_query($con, $verify_query);

if (!$verify_result) {
    logCartDeletion('Database query failed during verification', ['error' => mysqli_error($con), 'cart_id' => $cart_id, 'uid' => $_SESSION['uid']]);
    echo "<script>
        alert('❌ Database error occurred! Please try again.');
        window.location.href = 'cart.php?error=database_error';
    </script>";
    exit();
}

if (mysqli_num_rows($verify_result) == 0) {
    logCartDeletion('Attempted to delete non-existent or unauthorized item', ['cart_id' => $cart_id, 'uid' => $_SESSION['uid']]);
    echo "<script>
        alert('❌ Item not found or you don\'t have permission to delete it!');
        window.location.href = 'cart.php?error=item_not_found';
    </script>";
    exit();
}

// Get item details for logging
$item_data = mysqli_fetch_array($verify_result);

// Perform the deletion with prepared statement for extra security
$delete_query = "DELETE FROM addcart WHERE id = $cart_id AND u_id = '$uid' LIMIT 1";
$delete_result = mysqli_query($con, $delete_query);

if ($delete_result) {
    // Check if any row was actually deleted
    $affected_rows = mysqli_affected_rows($con);
    
    if ($affected_rows > 0) {
        // Log successful deletion
        logCartDeletion('Item deleted successfully', [
            'cart_id' => $cart_id,
            'uid' => $_SESSION['uid'],
            'product_id' => $item_data['p_id'],
            'quantity' => $item_data['qty'],
            'total' => $item_data['total']
        ]);
        
        // Check if cart is now empty
        $remaining_items = mysqli_query($con, "SELECT COUNT(*) as count FROM addcart WHERE u_id = '$uid'");
        $remaining_count = mysqli_fetch_array($remaining_items)['count'];
        
        if ($remaining_count == 0) {
            echo "<script>
                alert('🗑️ Item removed successfully!\\n\\nYour cart is now empty.');
                window.location.href = 'cart.php?msg=cart_empty';
            </script>";
        } else {
            echo "<script>
                alert('🗑️ Item removed successfully!\\n\\nRemaining items: $remaining_count');
                window.location.href = 'cart.php?msg=deleted';
            </script>";
        }
    } else {
        // Query executed but no rows affected
        logCartDeletion('Delete query executed but no rows affected', ['cart_id' => $cart_id, 'uid' => $_SESSION['uid']]);
        echo "<script>
            alert('⚠️ Item was already removed or doesn\'t exist!');
            window.location.href = 'cart.php?msg=already_removed';
        </script>";
    }
} else {
    // Database error during deletion
    logCartDeletion('Delete query failed', [
        'error' => mysqli_error($con),
        'cart_id' => $cart_id,
        'uid' => $_SESSION['uid']
    ]);
    
    echo "<script>
        alert('❌ Failed to remove item!\\n\\nPlease try again or contact support if the issue persists.');
        window.location.href = 'cart.php?error=delete_failed';
    </script>";
}

exit();
?>

<?php 
session_start();

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

include "connect.php";
include "header.php";

$uid = mysqli_real_escape_string($con, $_SESSION['uid']);

// Check if cart has items
$cart_check = mysqli_query($con, "SELECT COUNT(*) as count FROM addcart WHERE u_id = '$uid'");
$cart_result = mysqli_fetch_array($cart_check);

if (!$cart_result || $cart_result['count'] == 0) {
    echo "<script>
        alert('Your cart is empty! Please add items first.');
        window.location.href = 'menu.php';
    </script>";
    exit();
}

// Get cart total and item count
$total_query = mysqli_query($con, "SELECT SUM(total) as grand_total, COUNT(*) as item_count FROM addcart WHERE u_id = '$uid'");
$total_result = mysqli_fetch_array($total_query);
$grand_total = $total_result['grand_total'] ?? 0;
$item_count = $total_result['item_count'] ?? 0;

// Get cart items (for display)
$items_query = mysqli_query($con, "SELECT ac.*, m.title, m.image FROM addcart ac 
                                   JOIN menu m ON ac.p_id = m.id WHERE ac.u_id = '$uid' 
                                   ORDER BY ac.id DESC LIMIT 3");
?>

<div style="height:120px;"></div>
<div style="width:90%; margin:0 auto;">
    <div style="width:70%; margin:0 auto; max-width:800px;">
        
        <div style="text-align:center; margin-bottom:40px;">
            <div style="font-size:3.5em; color:#e67e22; margin-bottom:15px;">🛍️</div>
            <h1 style="color:#e67e22; font-size:2.2em; margin:0 0 10px 0;">CHECKOUT</h1>
            <p style="color:#666; font-size:1.2em; margin:0;">Complete your order details</p>
        </div>
        
        <form action="process_order.php" method="post" id="checkoutForm" style="background: white; padding: 35px; border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.1); border: 1px solid #eee;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td colspan="2" style="padding: 0 0 25px 0;">
                        <div style="text-align: center; background: linear-gradient(135deg, #f8f9fa, #ffffff); padding: 25px; border-radius: 15px; border: 2px solid #e67e22;">
                            <h2 style="margin: 0 0 10px 0; color: #e67e22; font-size: 1.5em;">📝 Delivery Information</h2>
                            <p style="margin: 0; color: #666; font-size: 1.1em;">Please provide accurate details for delivery</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 20px 10px;">
                        <label style="display: block; color: #e67e22; font-weight: bold; margin-bottom: 10px; font-size: 1.15em;">
                            👤 Full Name *
                        </label>
                        <input type="text" name="nm" id="name" placeholder="Enter your full name" 
                               style="width: 100%; padding: 18px; color: #333; background-color: #f8f9fa; border: 2px solid #ddd; border-radius: 12px; font-size: 1.1em;" 
                               required>
                        <small style="color: #666; font-size: 0.9em;">Enter your full name as per ID</small>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 20px 10px;">
                        <label style="display: block; color: #e67e22; font-weight: bold; margin-bottom: 10px; font-size: 1.15em;">
                            📱 Mobile Number *
                        </label>
                        <input type="text" name="mo" id="mobile" placeholder="Enter 10-digit mobile number" maxlength="10"
                               style="width: 100%; padding: 18px; color: #333; background-color: #f8f9fa; border: 2px solid #ddd; border-radius: 12px; font-size: 1.1em;" 
                               required>
                        <small style="color: #666; font-size: 0.9em;">We'll call you for delivery updates</small>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 20px 10px;">
                        <label style="display: block; color: #e67e22; font-weight: bold; margin-bottom: 10px; font-size: 1.15em;">
                            📧 Email Address
                        </label>
                        <input type="email" name="em" id="email" placeholder="Enter your email address (optional)" 
                               style="width: 100%; padding: 18px; color: #333; background-color: #f8f9fa; border: 2px solid #ddd; border-radius: 12px; font-size: 1.1em;">
                        <small style="color: #666; font-size: 0.9em;">For order confirmation and updates</small>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 20px 10px;">
                        <label style="display: block; color: #e67e22; font-weight: bold; margin-bottom: 10px; font-size: 1.15em;">
                            📍 Delivery Address *
                        </label>
                        <textarea name="ad" id="address" placeholder="Enter your complete delivery address with landmark" 
                                  style="width:100%; padding:18px; color:#333; background-color:#f8f9fa; border:2px solid #ddd; border-radius: 12px; font-size:1.1em; min-height:100px; resize:vertical;" 
                                  required></textarea>
                        <small style="color: #666; font-size: 0.9em;">Include house number, street, landmark, and area</small>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 30px 10px 10px 10px; text-align: center;">
                        <button type="submit" name="checkout_submit" id="submitBtn"
                                style="background: linear-gradient(135deg, #27ae60, #2ecc71); color: white; border: none; padding: 20px 45px; border-radius: 50px;
                                       font-size: 1.3em; font-weight: bold; cursor: pointer; box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4); width: 85%;">
                            🚀 CONFIRM ORDER - ₹<?php echo number_format($grand_total, 2); ?>
                        </button>
                    </td>
                </tr>
            </table>
        </form>

        <div style="text-align: center; margin: 30px 0; display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
            <a href="cart.php" style="color: #e67e22; text-decoration: none; font-weight: bold; padding: 12px 25px; border: 2px solid #e67e22; border-radius: 30px;">🛒 Back to Cart</a>
            <a href="menu.php" style="color: #27ae60; text-decoration: none; font-weight: bold; padding: 12px 25px; border: 2px solid #27ae60; border-radius: 30px;">🍽️ Continue Shopping</a>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>

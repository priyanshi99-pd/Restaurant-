<?php session_start();
    if (isset($_GET['msg']) && $_GET['msg'] == 'added') {
        echo "<p style='color: green; text-align: center;'>Item added to cart successfully!</p>";
    }
    
    // Check if user is logged in
    if (!isset($_SESSION['uid'])) {
        header("Location: login.php");
        exit();
    }
    
    $uid = $_SESSION['uid'];
    include "header.php"; 
?>
<style type="text/css">
    .cart-container {
        width: 90%; 
        margin: 0 auto;
        padding: 20px;
    }
    
    .cart-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .cart-header img {
        width: 80px;
        margin-bottom: 10px;
    }
    
    .cart-title {
        font-size: 2.4em; 
        color: #e67e22;
        margin: 10px 0;
        font-weight: bold;
    }
    
    .checkout-section {
        width: 100%; 
        padding: 20px; 
        text-align: right;
        margin: 20px 0;
    }
    
    .checkout-btn {
        background-color: #e67e22;
        color: white;
        padding: 12px 25px;
        text-decoration: none;
        border-radius: 8px;
        font-weight: bold;
        display: inline-block;
        transition: background-color 0.3s;
    }
    
    .checkout-btn:hover {
        background-color: #cf6c13;
        color: white;
        text-decoration: none;
    }
    
    .cart-table {
        border: 2px solid #e67e22;
        width: 90%;
        margin: 0 auto;
        background-color: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    
    .cart-table th {
        background-color: #e67e22;
        color: white;
        padding: 15px;
        font-weight: bold;
        text-align: center;
        font-size: 1.1em;
    }
    
    .cart-table td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #eee;
        color: #333;
    }
    
    .cart-table tr:hover {
        background-color: #f8f9fa;
        color: black;
    }
    
    .product-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .product-name {
        font-weight: bold;
        color: #e67e22;
        margin-bottom: 5px;
    }
    
    .price-text {
        font-weight: bold;
        color: #27ae60;
        font-size: 1.1em;
    }
    
    .qty-text {
        font-weight: bold;
        background-color: #f39c12;
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        display: inline-block;
    }
    
    .total-text {
        font-weight: bold;
        color: #e74c3c;
        font-size: 1.2em;
    }
    
    .delete-btn {
        background-color: #e74c3c;
        color: white;
        padding: 8px 15px;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        transition: background-color 0.3s;
    }
    
    .delete-btn:hover {
        background-color: #c0392b;
        color: white;
        text-decoration: none;
    }
    
    .empty-cart {
        text-align: center;
        color: #666;
        font-size: 1.3em;
        padding: 50px;
    }
    
    .continue-shopping {
        background-color: #27ae60;
        color: white;
        padding: 12px 25px;
        text-decoration: none;
        border-radius: 8px;
        font-weight: bold;
        margin-right: 15px;
        transition: background-color 0.3s;
    }
    
    .continue-shopping:hover {
        background-color: #219a52;
        color: white;
        text-decoration: none;
    }
    
    .grand-total-row {
        background-color: #f8f9fa !important;
        font-weight: bold;
        font-size: 1.2em;
    }
    
    .grand-total-row td {
        border-top: 2px solid #e67e22;
        color: #e67e22;
    }
</style>

<div style="height: 100px;"></div>
<div class="cart-container">
    <div class="cart-header">
        <!-- <img src="images/cart.png" alt="Cart Icon"> -->
        <h1 class="cart-title">VIEW CART PRODUCTS</h1>
    </div>
    <div>
        <?php 
        include "connect.php";
        
        // Modified query to fetch product details including name and image
        $s = mysqli_query($con, "SELECT addcart.id as cart_id, addcart.price, addcart.qty, addcart.total, addcart.p_id,
                                        menu.title, menu.image, menu.description
                                 FROM addcart
                                 INNER JOIN menu ON addcart.p_id = menu.id 
                                 WHERE addcart.u_id = '$uid'
                                 ORDER BY addcart.id DESC");
        
        if(mysqli_num_rows($s) > 0) {
        ?>
        
        <table class="cart-table">
            <tr>
                <th>Product</th>
                <th>Image</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            
            <?php
            $grand_total = 0;
            while($r = mysqli_fetch_array($s)) {
                $grand_total += $r['total'];
            ?>
            <tr>
                <td>
                    <div class="product-name"><?php echo htmlspecialchars($r['title']); ?></div>
                    <small><?php echo htmlspecialchars(substr($r['description'], 0, 50)) . '...'; ?></small>
                </td>
                <td>
                    <img src="<?php echo htmlspecialchars($r['image']); ?>" 
                         class="product-img" 
                         alt="<?php echo htmlspecialchars($r['title']); ?>"
                         onerror="this.src='images/no-image.png'">
                </td>
                <td class="price-text">₹<?php echo number_format($r['price'], 2); ?></td>
                <td>
                    <span class="qty-text"><?php echo $r['qty']; ?></span>
                </td>
                <td class="total-text">₹<?php echo number_format($r['total'], 2); ?></td>
                <td>
                    <a href="deletecart.php?id=<?php echo $r['cart_id']; ?>" 
                       class="delete-btn"
                       onclick="return confirm('Are you sure you want to remove this item from cart?')">
                       Remove
                    </a>
                </td>
            </tr>
            <?php } ?>
            
            <!-- Grand Total Row -->
            <tr class="grand-total-row">
                <td colspan="4"><strong>GRAND TOTAL:</strong></td>
                <td><strong>₹<?php echo number_format($grand_total, 2); ?></strong></td>
                <td>-</td>
            </tr>
        </table>
        
        <div class="checkout-section">
            <a href="menu.php" class="continue-shopping">← Keep Ordering</a>
            <a href="checkout.php" class="checkout-btn">Proceed to Checkout (₹<?php echo number_format($grand_total, 2); ?>) →</a>
        </div>
        
        <?php 
        } else {
        ?>
        <div class="empty-cart">
            <!-- <img src="images/empty-cart.png" width="150" alt="Empty Cart"><br><br> -->
            <h3>Your cart is empty!</h3>
            <p>Add some delicious items to your cart.</p>
            <a href="menu.php" class="checkout-btn">Browse Menu</a>
        </div>      
        <?php } ?>
    </div>
</div>

<?php include "footer.php"; ?>

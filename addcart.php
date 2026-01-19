<?php session_start(); ?>
<?php include "header.php"; ?> 
<body>
    
    <!-- Start All Pages -->
    <div class="all-page-title page-breadcrumb">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-12">
                    <h1>ADD TO CART</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- End All Pages -->
    
    <!-- Start Contact -->
    <div class="contact-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    
                    <?php
                    // Handle form submission from bombay.php and other category pages
                    if(isset($_POST['item_id']) && isset($_POST['quantity'])) {
                        $pid = $_POST['item_id'];
                        $item_name = $_POST['item_name'];
                        $price = $_POST['price'];
                        $qty = $_POST['quantity'];
                        $total = $price * $qty;
                        
                        // Check if user is logged in
                        if(isset($_SESSION['uid'])) {
                            $uid = $_SESSION['uid'];
                            
                            include "connect.php";
                            
                            // Check if item already exists in cart
                            $check_query = mysqli_query($con, "SELECT * FROM addcart WHERE p_id='$pid' AND u_id='$uid'");
                            
                            if(mysqli_num_rows($check_query) > 0) {
                                // Update existing item quantity
                                $existing = mysqli_fetch_array($check_query);
                                $new_qty = $existing['qty'] + $qty;
                                $new_total = $price * $new_qty;
                                
                                mysqli_query($con, "UPDATE addcart SET qty='$new_qty', total='$new_total' WHERE p_id='$pid' AND u_id='$uid'") or die(mysqli_error($con));
                                echo "<div class='alert alert-success'>Item quantity updated in cart!</div>";
                            } else {
                                // Insert new item
                                mysqli_query($con, "INSERT INTO addcart(p_id, u_id, price, qty, total) VALUES('$pid', '$uid', '$price', '$qty', '$total')") or die(mysqli_error($con));
                                echo "<div class='alert alert-success'>Item added to cart successfully!</div>";
                            }
                            
                            echo "<script>
                                setTimeout(function() {
                                    window.history.back();
                                }, 2000);
                            </script>";
                            
                        } else {
                            echo "<div class='alert alert-warning'>Please login to add items to cart.</div>";
                            echo "<script>
                                setTimeout(function() {
                                    window.location.href = 'login.php';
                                }, 2000);
                            </script>";
                        }
                    }
                    // Handle form submission from URL parameters (old method)
                    elseif(isset($_GET['pid'])) {
                    ?>
                    
                    <form action="" method="post">
                        <table align="center" border="1" cellspacing="14" cellpadding="12" style="color: black;">
                            <tr align="center">
                                <td style="color: red">Product ID</td>
                                <td><input type="hidden" name="pid" value="<?php echo $_GET['pid']; ?>"><?php echo $_GET['pid']; ?></td>
                            </tr>
                            <tr align="center">
                                <td style="color: red">Your USERID</td>
                                <td><input type="hidden" name="uid" value="<?php echo $_GET['uid']; ?>"><?php echo $_GET['uid']; ?></td>
                            </tr>
                            <tr align="center">
                                <td style="color: red">Price</td>
                                <td><input type="hidden" name="price" value="<?php echo $_GET['price']; ?>">₹<?php echo $_GET['price']; ?></td>
                            </tr>
                            <tr align="center">
                                <td style="color: red">QTY</td>
                                <td><input type="number" name="qty" value="1" min="1" max="10" required></td>
                            </tr>
                            <tr align="center">
                                <td colspan="4"><input type="submit" name="sb" value="Add To Cart" class="btn btn-primary"></td>
                            </tr>
                        </table>
                    </form>
                    
                    <?php
                        // Handle old form submission
                        if(isset($_POST['sb'])) {
                            $pid = $_POST['pid'];
                            $uid = $_POST['uid'];
                            $price = $_POST['price'];
                            $qty = $_POST['qty'];
                            $total = $price * $qty;
                            
                            include "connect.php";
                            mysqli_query($con, "INSERT INTO addcart(p_id, u_id, price, qty, total) VALUES('$pid', '$uid', '$price', '$qty', '$total')") or die(mysqli_error($con));
                            echo "<script>alert('Your item has been added to cart!')</script>";
                        }
                    } else {
                        echo "<div class='alert alert-info'>No item selected. Please go back and select an item.</div>";
                        echo "<a href='menu.php' class='btn btn-primary'>Back to Menu</a>";
                    }
                    ?>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- End Contact -->
    
<?php include "footer.php"; ?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "connect.php"; // Database connection ($con)
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Special Menu</title>
<style>
/* Combined Top Bar */
.topbar-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fff;
    border-bottom: 1.5px solid #eee;
    padding: 0 2em;
    height: 52px;
    position: sticky;
    top: 0; left: 0;
    z-index: 55;
}
/* Back button styling */
.back-button {
    background: #e67e22;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 7px;
    font-weight: 600;
    font-size: 1.05em;
    cursor: pointer;
    box-shadow: 0 1px 7px rgba(0,0,0,0.06);
    transition: background-color 0.3s;
    text-decoration: none;
}
.back-button:hover {
    background: #cf6c13;
    color: white;
    text-decoration: none;
}
/* Navbar styling */
.topnav {
    display: flex;
    gap: 18px;
}
.topnav .nav-link {
    color: #222;
    text-decoration: none;
    font-size: 1.05em;
    font-weight: 500;
    padding: 7px 15px;
    border-radius: 8px;
    transition: background 0.16s, color 0.16s;
}
.topnav .nav-link:hover,
.topnav .nav-link.active {
    background: #e67e22;
    color: #fff;
}
body {
    background: #f8f8f8;
    font-family: Arial, sans-serif;
    margin: 0; padding: 0;
}
.main-page {
    display: flex;
    align-items: flex-start;
    width: 100%;
    min-height: 100vh;
}
.sidebar {
    width: 210px;
    min-height: 80vh;
    background: #fff;
    border-radius: 16px;
    margin: 40px 0 0 0;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.07);
    padding: 32px 14px;
    position: sticky;
    top: 40px;
    height: fit-content;
}
.sidebar-title {
    font-size: 1.16em;
    font-weight: bold;
    margin-bottom: 18px;
    color: #e67e22;
    letter-spacing: 0.5px;
}
.sidebar-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.sidebar-list li {
    margin-bottom: 14px;
}
.sidebar-list a {
    text-decoration: none;
    color: #333;
    font-size: 1.04em;
    font-weight: 500;
    padding-left: 3px;
    transition: color 0.2s;
    border-left: 3px solid transparent;
}
.sidebar-list a.active, .sidebar-list a:hover {
    color: #e67e22;
    font-weight: 700;
    border-left: 3px solid #e67e22;
    padding-left: 8px;
}
.vertical-divider {
    width: 2px;
    background: #e0e0e0;
    margin: 0 26px;
    height: 80vh;
    min-height: 400px;
    margin-top: 40px;
    border-radius: 2px;
    box-shadow: 0 2px 14px rgba(0, 0, 0, 0.03);
}
.content {
    flex: 1;
    padding-top: 40px;
}
.menu-container {
    padding: 0 12px 40px 12px;
}
.menu-punch {
    text-align: center;
    font-size: 1.18em;
    color: #c54a00;
    font-weight: 600;
    letter-spacing: 0.2px;
    margin: 0 0 18px 0;
    font-style: italic;
}
.menu-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 32px;
    justify-items: center;
    align-items: stretch;
}
.menu-item {
    position: relative;
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 4px 28px rgba(0, 0, 0, 0.13);
    width: 310px;
    height: auto; /* Changed to auto height */
    min-height: 450px; /* Minimum height to maintain consistency */
    display: flex;
    flex-direction: column;
    align-items: stretch;
    margin-bottom: 10px;
    transition: box-shadow 0.19s;
}
.menu-item:hover {
    box-shadow: 0 8px 21px rgba(0, 0, 0, 0.20);
}
.menu-badge {
    position: absolute;
    top: 15px;
    left: 13px;
    background: #e67e22;
    color: #fff;
    font-size: 0.93em;
    padding: 5px 12px;
    border-radius: 12px;
    font-weight: 600;
    z-index: 2;
    box-shadow: 0 1px 9px rgba(0, 0, 0, 0.07);
}
/* Square image container */
.menu-img-container {
    position: relative;
    width: 100%;
    height: 250px;
    overflow: hidden;
    border-bottom: 1px solid #eee;
    flex-shrink: 0;
}
.menu-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}
.menu-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    text-align: center;
    padding: 15px 10px 20px 10px; /* Added bottom padding */
    flex-grow: 1;
}
.menu-title {
    font-size: 1.10em;
    font-weight: bold;
    margin: 0 0 8px 0;
    color: #222;
    line-height: 1.3em;
}
.menu-desc {
    font-size: 0.95em;
    color: #555;
    margin-bottom: 10px;
    line-height: 1.2em;
    height: 2.4em;
    overflow: hidden;
    display: -webkit-box;
    /* -webkit-line-clamp: 2; */
    -webkit-box-orient: vertical;
}
.menu-price {
    color: #e67e22;
    font-size: 1.08em;
    margin-bottom: 15px; /* Reduced margin */
    font-weight: 700;
}
.add-cart-form {
    /* Removed margin-top: auto */
    margin-bottom: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
    padding: 0 10px;
}
.add-cart-form input[type=number] {
    width: 65px;
    padding: 8px;
    font-size: 0.95em;
    border: 1px solid #ccc;
    border-radius: 9px;
    text-align: center;
}
.add-cart-form button {
    background-color: #e67e22;
    border: none;
    padding: 8px 18px;
    color: white;
    font-weight: 600;
    border-radius: 9px;
    cursor: pointer;
    font-size: 0.95em;
    transition: background-color 0.3s;
}
.add-cart-form button:hover {
    background-color: #cf6c13;
}
.login-required {
    background-color: #6c757d;
    cursor: not-allowed;
    padding: 8px 18px;
    text-decoration: none;
    color: white;
    border-radius: 9px;
    font-size: 0.95em;
    font-weight: 600;
}
.login-required:hover {
    background-color: #5a6268;
    text-decoration: none;
    color: white;
}
@media (max-width: 1150px) {
    .main-page {
        flex-direction: column;
    }
    .sidebar, .vertical-divider {
        display: none;
    }
    .content {
        padding-top: 10px;
    }
    .menu-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 540px) {
    .menu-grid {
        grid-template-columns: 1fr;
    }
    .menu-item {
        width: 98vw;
        max-width: 280px;
        height: auto;
        min-height: 420px;
    }
    .menu-img-container {
        height: 220px;
    }
    .menu-container {
        padding: 0 4px 20px 4px;
    }
    .menu-punch {
        font-size: 1.03em;
    }
}
</style>
</head>
<body>
<div class="topbar-container">
    <a href="index.php" class="back-button">← Back</a>
    <nav class="topnav">
        <a href="index.php" class="nav-link">Home</a>
        <a href="menu.php" class="nav-link active">Menu</a>
        <a href="about.php" class="nav-link">About us</a>
        <a href="contact.php" class="nav-link">Contact us</a>
        <a href="review.php" class="nav-link">Review</a>
        <?php if(isset($_SESSION['uid'])): ?>
            <a href="cart.php" class="nav-link">Cart</a>
            <a href="logout.php" class="nav-link">Logout</a>
        <?php else: ?>
            <a href="login.php" class="nav-link">Login</a>
        <?php endif; ?>
    </nav>
</div>

<div class="main-page">
    <aside class="sidebar">
        <div class="sidebar-title">Categories</div>
        <ul class="sidebar-list">
            <li><a href="menu.php" class="active">All</a></li>
            <li><a href="punjabi.php">Punjabi</a></li>
            <li><a href="Gujarati.php">Gujarati</a></li>
            <li><a href="south.php">South Indian</a></li>
            <li><a href="fast_food.php">Street Food</a></li>
            <li><a href="bombay.php">Bombay Special</a></li>
            
        </ul>
    </aside>
    
    <div class="vertical-divider"></div>
    
    <section class="content">
        <div class="menu-container">
            <h2 style="text-align:center;margin-bottom:12px;">Special Menu</h2>
            <div class="menu-punch">Every day is a good day to eat great food</div>
            
            <div class="menu-grid">
                <?php
                $sql = "SELECT * FROM menu";
                $result = mysqli_query($con, $sql);
                
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($food = mysqli_fetch_assoc($result)) {
                        echo '<div class="menu-item">';
                        
                        // Display category as badge
                        echo '<div class="menu-badge">' . htmlspecialchars($food["category"]) . '</div>';
                        
                        // Square image container
                        echo '<div class="menu-img-container">';
                        echo '<img src="' . htmlspecialchars($food['image']) . '" class="menu-img" alt="' . htmlspecialchars($food['title']) . '">';
                        echo '</div>';
                        
                        echo '<div class="menu-content">';
                        echo '<div class="menu-title">' . htmlspecialchars($food['title']) . '</div>';
                        echo '<div class="menu-desc">' . htmlspecialchars($food['description']) . '</div>';
                        echo '<div class="menu-price">₹' . htmlspecialchars($food['price']) . '</div>';
                        
                        // Check if user is logged in
                        if(isset($_SESSION['uid'])) {
                            echo '<form class="add-cart-form" method="post" action="addcart.php">';
                            echo '<input type="hidden" name="item_id" value="' . htmlspecialchars($food['id']) . '">';
                            echo '<input type="hidden" name="item_name" value="' . htmlspecialchars($food['title']) . '">';
                            echo '<input type="hidden" name="price" value="' . htmlspecialchars($food['price']) . '">';
                            echo '<input type="number" name="quantity" value="1" min="1" max="99" required>';
                            echo '<button type="submit">Add to Cart</button>';
                            echo '</form>';
                        } else {
                            echo '<div class="add-cart-form">';
                            echo '<a href="login.php" class="login-required">Login to Add</a>';
                            echo '</div>';
                        }
                        
                        echo '</div>'; // Close menu-content
                        echo '</div>'; // Close menu-item
                    }
                } else {
                    echo "<div style='grid-column:1/-1;text-align:center;color:#666;'>No dishes found.</div>";
                }
                ?>
            </div>
        </div>
    </section>
</div>

</body>
</html>

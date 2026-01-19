<?php
// Check if session is not already started before starting it
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// If user is logged in but location not in session, fetch from database
if(isset($_SESSION['uid']) && (!isset($_SESSION['user_location']) || empty($_SESSION['user_location']))) {
    include "connect.php";
    $uid = $_SESSION['uid'];
    $query = "SELECT user_location, user_address, user_latitude, user_longitude FROM registration WHERE userid='$uid'";
    $result = mysqli_query($con, $query);
    
    if($result && mysqli_num_rows($result) > 0) {
        $user_data = mysqli_fetch_assoc($result);
        if(!empty($user_data['user_location'])) {
            $_SESSION['user_location'] = $user_data['user_location'];
            $_SESSION['user_address'] = $user_data['user_address'];
            $_SESSION['user_latitude'] = $user_data['user_latitude'];
            $_SESSION['user_longitude'] = $user_data['user_longitude'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">   
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Decent Restaurant</title>  
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Site Icons -->
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">    
    <!-- Site CSS -->
    <link rel="stylesheet" href="css/style.css">    
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/custom.css">
    
    <style>
    /* Universal Header Styling - Same as Punjab Page */
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
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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
        display: inline-block;
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
        <?php if(basename($_SERVER['PHP_SELF']) == 'index.php'): ?>
        margin-left: 0;
        <?php endif; ?>
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
        text-decoration: none;
    }

    /* Logo section for home page */
    .site-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.2em;
        font-weight: bold;
        color: #e67e22;
    }

    /* Location Selector in Header */
    .location-selector {
        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
        background-color: #1e3a8a;
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
        min-width: 180px;
        font-size: 13px;
        font-weight: 500;
        border: none;
        transition: all 0.3s ease;
        margin-right: 15px;
    }

    .location-selector:hover {
        background-color: #1e40af;
    }

    .location-icon {
        color: #ef4444;
        font-size: 14px;
    }

    .location-details {
        flex: 1;
    }

    .location-name {
        font-weight: 600;
        font-size: 13px;
        margin-bottom: 1px;
    }

    .location-address {
        font-size: 10px;
        opacity: 0.8;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 140px;
    }

    .dropdown-arrow {
        font-size: 10px;
        transition: transform 0.3s ease;
    }

    /* User Profile in Header */
    .user-profile {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-left: 15px;
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid #28a745;
    }

    .user-info {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .user-name {
        font-weight: 500;
        font-size: 14px;
        color: #333;
    }

    .user-actions {
        font-size: 11px;
        display: flex;
        gap: 5px;
    }

    .user-actions a {
        color: #666;
        text-decoration: none;
        transition: color 0.3s;
    }

    .user-actions a:hover {
        color: #e67e22;
        text-decoration: none;
    }

    /* Guest User Links */
    .guest-links {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
    }

    .guest-links a {
        color: #333;
        text-decoration: none;
        padding: 6px 12px;
        border-radius: 6px;
        transition: all 0.3s;
        border: 1px solid #ddd;
    }

    .guest-links a:hover {
        background: #e67e22;
        color: white;
        text-decoration: none;
        border-color: #e67e22;
    }

    /* Hide old header elements */
    .top-navbar {
        display: none !important;
    }

    /* Body adjustment for new header */
    body {
        padding-top: 0 !important;
        background: #f8f8f8;
        font-family: Arial, sans-serif;
        margin: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .topbar-container {
            padding: 0 1em;
            flex-wrap: wrap;
            height: auto;
            min-height: 52px;
        }

        .topnav {
            gap: 10px;
            flex-wrap: wrap;
        }

        .topnav .nav-link {
            font-size: 14px;
            padding: 5px 10px;
        }

        .location-selector {
            min-width: 140px;
            font-size: 12px;
            margin-right: 10px;
        }

        .user-profile, .guest-links {
            margin-left: 10px;
        }

        .back-button {
            font-size: 14px;
            padding: 6px 15px;
        }

        .site-logo {
            font-size: 1.1em;
        }
    }

    @media (max-width: 480px) {
        .topbar-container {
            padding: 5px;
        }

        .topnav {
            gap: 5px;
        }

        .topnav .nav-link {
            font-size: 12px;
            padding: 4px 8px;
        }

        .location-selector {
            min-width: 120px;
            font-size: 11px;
        }

        .guest-links {
            gap: 5px;
        }

        .guest-links a {
            font-size: 12px;
            padding: 4px 8px;
        }

        .site-logo {
            font-size: 1em;
        }
    }
    </style>
</head>

<body>
    <!-- New Universal Header -->
    <div class="topbar-container">
        <!-- Left Side: Back Button OR Logo -->
        <?php if(basename($_SERVER['PHP_SELF']) == 'index.php'): ?>
            <!-- Show Logo on Home Page -->
            <div class="site-logo">
                🍽️ Decent Restaurant
            </div>
        <?php else: ?>
            <!-- Show Back Button on Other Pages -->
            <a href="index.php" class="back-button">← Back</a>
        <?php endif; ?>

        <!-- Main Navigation -->
        <nav class="topnav">
            <a href="index.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">Home</a>
            <a href="menu.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'menu.php') ? 'active' : ''; ?>">Menu</a>
            <a href="about.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active' : ''; ?>">About us</a>
            <a href="contact.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'active' : ''; ?>">Contact us</a>
            <a href="review.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'review.php') ? 'active' : ''; ?>">Review</a>
            
            <?php if(isset($_SESSION['uid'])): ?>
                <a href="cart.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'cart.php') ? 'active' : ''; ?>">Cart</a>
                <a href="logout.php" class="nav-link">Logout</a>
            <?php else: ?>
                <a href="registration.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'registration.php') ? 'active' : ''; ?>">Register</a>
                <a href="login.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'login.php') ? 'active' : ''; ?>">Login</a>
            <?php endif; ?>
        </nav>

        <!-- Right Side Elements -->
        <div style="display: flex; align-items: center;">
            <!-- Location Selector (for ALL users) -->
            <div class="location-selector" onclick="<?php echo isset($_SESSION['uid']) ? "window.location.href='select_location.php'" : "window.location.href='login.php'"; ?>">
                <span class="location-icon">📍</span>
                <div class="location-details">
                    <?php if(isset($_SESSION['uid']) && isset($_SESSION['user_location']) && !empty($_SESSION['user_location'])): ?>
                        <div class="location-name"><?php echo htmlspecialchars($_SESSION['user_location']); ?></div>
                        <div class="location-address"><?php echo htmlspecialchars(substr($_SESSION['user_address'], 0, 25)) . (strlen($_SESSION['user_address']) > 25 ? '...' : ''); ?></div>
                    <?php else: ?>
                        <div class="location-name">Select Location</div>
                        <div class="location-address"><?php echo isset($_SESSION['uid']) ? 'Choose delivery location' : 'Login to set location'; ?></div>
                    <?php endif; ?>
                </div>
                <span class="dropdown-arrow">▼</span>
            </div>

            <?php if(isset($_SESSION['uid'])): ?>
                <!-- User Profile (for logged-in users) -->
                <div class="user-profile">
                    <div class="user-avatar">
                        <?php 
                        // Get user photo
                        include "connect.php";
                        $uid = $_SESSION['uid'];
                        $photo_query = mysqli_query($con, "SELECT user_photo FROM registration WHERE userid='$uid'");
                        $photo_data = mysqli_fetch_assoc($photo_query);
                        
                        if(!empty($photo_data['user_photo']) && file_exists('uploads/user_photos/' . $photo_data['user_photo'])): ?>
                            <img src="uploads/user_photos/<?php echo $photo_data['user_photo']; ?>" 
                                 style="width: 100%; height: 100%; object-fit: cover;" 
                                 alt="<?php echo $_SESSION['uid']; ?>">
                        <?php else: ?>
                            <div style="width: 100%; height: 100%; background: #28a745; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 12px;">
                                <?php echo strtoupper(substr($_SESSION['uid'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="user-info">
                        <div class="user-name">Hi <?php echo htmlspecialchars($_SESSION['uid']); ?></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Your existing header content will be hidden by CSS -->
    <!-- This ensures compatibility with existing pages -->
</body>
</html>

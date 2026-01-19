<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bombay Food Menu</title>
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
}
.back-button:hover {
    background: #cf6c13;
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
    color: #ffffffff;
}

/* Existing CSS */
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
    padding: 40px 20px;
    background: #f8f8f8;
    min-height: 90vh;
}
.menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 30px;
    justify-items: center;
    align-items: start;
}
.menu-item {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.09);
    width: 220px;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 10px;
    transition: box-shadow .2s;
}
.menu-item:hover {
    box-shadow: 0 6px 22px rgba(0,0,0,0.13);
}
.menu-img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    background: #e0e0e0;
    border-bottom: 1px solid #eee;
    display: block;
}
.menu-content {
    padding: 16px 10px 14px 10px;
    width: 100%;
    text-align: center;
}
.menu-title {
    font-size: 1.08em;
    font-weight: bold;
    margin: 9px 0 4px 0;
    color: #222;
}
.menu-price {
    color: #e67e22;
    font-size: 1.04em;
    margin-bottom: 2px;
    font-weight: 600;
}
</style>
<div class="topbar-container">
     <a href="menu.php" class="back-button">←Back</a>
     <nav class="topnav">
        <a href="index.php" class="nav-link">Home</a>
        <a href="menu.php" class="nav-link">Menu</a>
        <a href="about.php" class="nav-link">About us</a>
        <a href="contact.php" class="nav-link">Contact us</a>
        <a href="review.php" class="nav-link">Review</a>
    </nav>
</div>

<div class="menu-container">
    <h2 style="text-align:center;margin-bottom:32px;">Our Menu</h2>
    <div class="menu-grid">
        <?php
        $foods = [
            ["Misal Pau", "₹80", "admin\bombay\misal_pau.png"],
            ["Modak", "₹180", "admin\bombay\modak.png"],
            ["Pau Bhaji", "₹110", "admin\bombay\paubhaji.png"],
            ["Poha", "₹30", "admin\bombay\poha.png"],
            ["Puranpoli", "₹70", "admin/bombay/puranpoli.png"],
            ["Sabudana Khichdi", "₹100", "admin\bombay\sabudana_khichdi.png"],
            ["Shrikhand", "₹120", "admin\bombay\shrikhand.png"],
            ["Thalipeeth", "₹90", "admin/bombay/thalipeeth.png"],
            ["Vadapau", "₹30", "admin/bombay/vadapau.png"],
            ["Basundi", "₹120", "admin\gujarati\basundi.png"],
            ["Dhokla", "₹30", "admin\gujarati\dhokla.png"],
            ["Fafda Jalebi", "₹250", "admin/gujarati/fafda_jalebi.png"],
            ["Handvo", "₹50", "admin\gujarati\handvo.png"],
            ["Kadhi", "₹80", "admin\gujarati\kadhi.png"],
            ["Khaman", "₹80", "admin\gujarati\khaman.png"],
            ["Khandvi", "₹100", "admin\gujarati\khandvi.png"],
            ["Khichu", "₹60", "admin\gujarati\khichu.png"],
            ["Undhiyu", "₹100", "admin\gujarati\undhiyu.png"],
            ["Chhole Bhature", "₹150", "admin\punjabi\chhole.png"],
            ["Dal Makhani", "₹90", "admin\punjabi\dal_makhni.png"],
            ["Lassi", "₹25", "admin\punjabi\lassi.png"],
            ["Butter Naan", "₹70", "admin/punjabi/naan.png"],
            ["Palak Paneer", "₹120", "admin\punjabi\palak_paneer.png"],
            ["Rajma Chawal", "₹140", "admin\punjabi\Rajma.png"],
            ["Aloo Paratha", "₹50", "admin\punjabi\aloo_paratha.png"],
            ["Shahi Paneer", "₹90", "admin\punjabi\shahi.png"],
            ["Paneer Tikka", "₹120", "admin/punjabi/tikka.png"],
            ["Bonda", "₹50", "admin\south\bonda.png"],
            ["Dosa", "₹90", "admin\south\dosa.png"],
            ["Idli", "₹70", "admin\south\idli.png"],
            ["Lemon Rice", "₹50", "admin\south\lemonice.png"],
            ["Meduvada", "₹70", "admin\south\meduvada.png"],
            ["Mysore Masala Dosa", "₹100", "admin\south\mysoredosa.png"],
            ["Pongal", "₹60", "admin\south\pongal.png"],
            ["Sambar", "₹70", "admin\south\sambar.png"],
            ["Uttapam", "₹50", "admin\south\uttapam.png"],
            ["Aloo Tikki", "₹60", "admin\street food\alootikki.png"],
            ["Bhel Puri", "₹85", "admin\street food\bhelpuri.png"],
            ["Corn Chaat", "₹90", "admin\street food\cornchaat.png"],
            ["Dahi Puri", "₹55", "admin\street food\dahi_puri.png"],
            ["Frankie", "₹70", "admin/street food/frankie.png"],
            ["Noodles", "₹60", "admin/street food/noodles.png"],
            ["Panipuri", "₹40", "admin\street food\panipuri.png"],
            ["Spring Roll", "₹80", "admin\street food\springroll.png"],
            ["Steam Momo", "₹75", "admin\street food\steam_momos.png"]
        ];
        foreach ($foods as $food) {
            echo '<div class="menu-item">';
            echo '<img src="' . htmlspecialchars($food[2]) . '" class="menu-img" alt="' . htmlspecialchars($food[0]) . '">';
            echo '<div class="menu-content">';
            echo '<div class="menu-title">' . htmlspecialchars($food[0]) . '</div>';
            echo '<div class="menu-price">' . htmlspecialchars($food[1]) .  '</div>';
            echo '</div></div>';
        }
        ?>
    </div>
</div>

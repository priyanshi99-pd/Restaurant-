<?php 
session_start();

$u = $_POST['uid'];
$p = $_POST['pass'];
include "connect.php";
$s = mysqli_query($con,"select * from registration where userid='$u' and password='$p'");

if($r = mysqli_fetch_array($s))
{
    $_SESSION['uid'] = $u;
    $_SESSION['user_id'] = $r['id']; // Store user ID for database operations
    
    // Check if user has set their location
    if($r['location_set'] == 0 || empty($r['user_location'])) {
        // User needs to set location first
        header("location:select_location.php");
    } else {
        // User already has location set, store in session
        $_SESSION['user_location'] = $r['user_location'];
        $_SESSION['user_address'] = $r['user_address'];
        $_SESSION['user_latitude'] = $r['user_latitude'];
        $_SESSION['user_longitude'] = $r['user_longitude'];
        
        header("location:index.php");
    }
}
else
{
    echo "<br><div style='color:black; border-radius:10px; padding:10px; text-align:center; background-color:tomato;'>Please Enter Valid User and password</div><br>";
    include "login.php";
}
?>

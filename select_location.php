<?php 
session_start();
if(!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}
include "header.php"; 
?>

<style>
.location-selection-wrapper {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
}

.location-card {
    background: white;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    width: 100%;
    text-align: center;
}

.location-title {
    font-size: 28px;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
}

.location-subtitle {
    color: #666;
    margin-bottom: 30px;
    font-size: 16px;
}

.location-input-group {
    position: relative;
    margin-bottom: 20px;
}

.location-input {
    width: 100%;
    padding: 15px 50px 15px 15px;
    border: 2px solid #e1e5e9;
    border-radius: 10px;
    font-size: 16px;
    outline: none;
    transition: border-color 0.3s;
    box-sizing: border-box;
}

.location-input:focus {
    border-color: #667eea;
}

.location-icon-input {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #667eea;
    font-size: 20px;
}

.current-location-btn {
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    padding: 15px;
    border-radius: 10px;
    cursor: pointer;
    margin-bottom: 20px;
    transition: all 0.3s;
    width: 100%;
}

.current-location-btn:hover {
    border-color: #667eea;
    background: #f0f2ff;
}

.save-location-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    width: 100%;
    transition: all 0.3s;
}

.save-location-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.popular-locations {
    margin-top: 25px;
    text-align: left;
}

.popular-location-item {
    padding: 12px 15px;
    border: 1px solid #e1e5e9;
    border-radius: 8px;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all 0.3s;
}

.popular-location-item:hover {
    border-color: #667eea;
    background: #f0f2ff;
}

.skip-link {
    margin-top: 15px;
    color: #666;
    text-decoration: none;
    font-size: 14px;
}

.skip-link:hover {
    color: #667eea;
}
</style>

<div class="location-selection-wrapper">
    <div class="location-card">
        <h2 class="location-title">📍 Welcome <?php echo $_SESSION['uid']; ?>!</h2>
        <p class="location-subtitle">Please select your delivery location to get started</p>
        
        <form action="" method="POST" id="locationForm">
            <div class="location-input-group">
                <input type="text" name="location_name" id="locationName" class="location-input" 
                       placeholder="Enter area/locality name (e.g., Mota Bazaar)" required>
                <span class="location-icon-input">🏠</span>
            </div>
            
            <div class="location-input-group">
                <input type="text" name="full_address" id="fullAddress" class="location-input" 
                       placeholder="Enter complete address" required>
                <span class="location-icon-input">📍</span>
            </div>
            
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
            
            <div class="current-location-btn" onclick="getCurrentLocation()">
                <strong>📡 Use Current Location</strong>
                <br><small>Get precise location automatically</small>
            </div>
            
            <button type="submit" name="save_location" class="save-location-btn">
                Save Location & Continue
            </button>
        </form>
        
        <div class="popular-locations">
            <h4>Popular Areas in Anand:</h4>
            <div class="popular-location-item" onclick="selectPopular('Mota Bazaar', 'Om Shiv Girls Hostel, GWXC+PQ9, Mota Bazaar, Vallabh Vidyanagar')">
                <strong>Mota Bazaar</strong><br>
                <small>Vallabh Vidyanagar</small>
            </div>
            <div class="popular-location-item" onclick="selectPopular('Anand Station', 'Station Road, Near Railway Station, Anand, Gujarat')">
                <strong>Anand Station</strong><br>
                <small>Near Railway Station</small>
            </div>
            <div class="popular-location-item" onclick="selectPopular('V.V. Nagar', 'Sardar Patel University Road, V.V. Nagar')">
                <strong>V.V. Nagar</strong><br>
                <small>University Area</small>
            </div>
            <div class="popular-location-item" onclick="selectPopular('Nadiad', 'College Road, Near Bus Stand, Nadiad')">
                <strong>Nadiad</strong><br>
                <small>Nadiad City</small>
            </div>
        </div>
        
        <a href="index.php" class="skip-link">Skip for now (You can set location later)</a>
    </div>
</div>

<?php
// Handle form submission
if(isset($_POST['save_location'])) {
    include "connect.php";
    
    $uid = $_SESSION['uid'];
    $location_name = mysqli_real_escape_string($con, $_POST['location_name']);
    $full_address = mysqli_real_escape_string($con, $_POST['full_address']);
    $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : null;
    $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : null;
    
    // Update user location in database
    $query = "UPDATE registration SET 
              user_location = '$location_name', 
              user_address = '$full_address', 
              user_latitude = '$latitude', 
              user_longitude = '$longitude', 
              location_set = 1 
              WHERE userid = '$uid'";
    
    if(mysqli_query($con, $query)) {
        // Set location in session
        $_SESSION['user_location'] = $location_name;
        $_SESSION['user_address'] = $full_address;
        $_SESSION['user_latitude'] = $latitude;
        $_SESSION['user_longitude'] = $longitude;
        
        echo "<script>
                alert('Location saved successfully! Welcome to Decent Restaurant!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>alert('Error saving location. Please try again.');</script>";
    }
}
?>

<script>
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
            
            // Set a default location name for current location
            document.getElementById('locationName').value = 'Current Location';
            document.getElementById('fullAddress').value = `Lat: ${position.coords.latitude}, Lng: ${position.coords.longitude}`;
            
            alert('Current location captured! You can edit the location name if needed.');
        }, function(error) {
            alert('Unable to get your location. Please enter manually.');
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}

function selectPopular(name, address) {
    document.getElementById('locationName').value = name;
    document.getElementById('fullAddress').value = address;
}

// Form validation
document.getElementById('locationForm').addEventListener('submit', function(e) {
    const locationName = document.getElementById('locationName').value;
    const fullAddress = document.getElementById('fullAddress').value;
    
    if (!locationName.trim() || !fullAddress.trim()) {
        e.preventDefault();
        alert('Please fill in both location name and address.');
    }
});
</script>

<?php include "footer.php"; ?>

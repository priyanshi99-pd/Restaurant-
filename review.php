<?php 
session_start();
include "header.php"; 
?>
<body>

<!-- Page Banner -->

<!-- Feedback Section -->
<div class="contact-box" style="padding: 60px 0; background-color: #f9f9f9;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 style="font-family: Cambria; color: #2c3e50;">We Value Your Feedback</h2>
                <p style="font-size: 1.1em; color: #555;">Our Main Goal is Client Satisfaction 💬</p>
            </div>
        </div>

        <!-- Floating Tooltip -->
        <div style="text-align: center; margin-top: 10px;">
            <span style="background: #ffedcc; padding: 8px 15px; border-radius: 20px; font-weight: bold;">
                ✨ Share your honest opinion & help us improve!
            </span>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <form id="feedbackForm" action="" method="post" enctype="multipart/form-data" style="background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                    
                    <!-- Profile Photo Section -->
                    <div class="form-group text-center" style="margin-bottom: 30px;">
                        <label><strong>Your Profile Photo</strong></label>
                        <div style="margin: 15px 0;">
                            <div class="photo-upload-container" style="position: relative; display: inline-block;">
                                <div id="photoPreview" style="width: 120px; height: 120px; border-radius: 50%; border: 3px solid #007bff; margin: 0 auto; overflow: hidden; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                    <?php 
                                    // Check if user is logged in and has existing photo
                                    if(isset($_SESSION['uid'])) {
                                        include "connect.php";
                                        $uid = $_SESSION['uid'];
                                        $user_query = mysqli_query($con, "SELECT user_photo FROM registration WHERE userid='$uid'");
                                        if($user_query && mysqli_num_rows($user_query) > 0) {
                                            $user_data = mysqli_fetch_assoc($user_query);
                                            if(!empty($user_data['user_photo']) && file_exists('uploads/user_photos/' . $user_data['user_photo'])) {
                                                echo '<img src="uploads/user_photos/' . $user_data['user_photo'] . '" style="width: 100%; height: 100%; object-fit: cover;" alt="Your Photo" id="currentPhoto">';
                                            } else {
                                                echo '<div style="color: #666; font-size: 48px;" id="currentPhoto">📷</div>';
                                            }
                                        } else {
                                            echo '<div style="color: #666; font-size: 48px;" id="currentPhoto">📷</div>';
                                        }
                                    } else {
                                        echo '<div style="color: #666; font-size: 48px;" id="currentPhoto">📷</div>';
                                    }
                                    ?>
                                </div>
                                <input type="file" name="user_photo" id="userPhoto" accept="image/*" style="display: none;" onchange="previewPhoto(this)">
                                <button type="button" onclick="document.getElementById('userPhoto').click()" style="background: #007bff; color: white; border: none; padding: 8px 16px; border-radius: 20px; margin-top: 10px; cursor: pointer; font-size: 12px;">
                                    📸 Choose Photo
                                </button>
                            </div>
                            <p style="font-size: 11px; color: #666; margin-top: 10px;">Optional - Upload your photo (JPG, PNG - Max 2MB)</p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label><strong>Your Name</strong></label>
                        <input type="text" name="name" class="form-control" placeholder="Enter your name" 
                               value="<?php echo isset($_SESSION['uid']) ? $_SESSION['uid'] : ''; ?>" required>
                    </div>

                    <div class="form-group mt-3">
                        <label><strong>How was your experience?</strong></label>
                        <select class="form-control" name="rev" required>
                            <option value="" disabled selected>Select an option</option>
                            <option value="Excellent">Excellent</option>
                            <option value="Good">Good</option>
                            <option value="Average">Average</option>
                            <option value="Poor">Poor</option>
                        </select>
                    </div>

                    <!-- Star Rating + Emoji Reaction -->
                    <div class="form-group mt-3">
                        <label><strong>Rate Us</strong></label><br>
                        <div class="star-rating" id="starRating">
                            <input type="radio" name="rating" id="star5" value="5"><label for="star5">★</label>
                            <input type="radio" name="rating" id="star4" value="4"><label for="star4">★</label>
                            <input type="radio" name="rating" id="star3" value="3"><label for="star3">★</label>
                            <input type="radio" name="rating" id="star2" value="2"><label for="star2">★</label>
                            <input type="radio" name="rating" id="star1" value="1"><label for="star1">★</label>
                        </div>
                        <div id="emojiFeedback" style="font-size: 30px; margin-top: 10px;"></div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="form-group">
                        <label><strong>Your Feedback Message</strong></label>
                        <textarea name="desc" class="form-control" rows="4" placeholder="Write your thoughts..." required></textarea>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="sb" class="btn btn-primary px-4">Send Feedback</button>
                    </div>
                </form>

                <!-- PHP Insert -->
                <?php
                if (isset($_POST['sb'])) {
                    $nm = mysqli_real_escape_string($con, $_POST['name']);
                    $rev = mysqli_real_escape_string($con, $_POST['rev']);
                    $des = mysqli_real_escape_string($con, $_POST['desc']);
                    $rating = isset($_POST['rating']) ? $_POST['rating'] : 'Not Rated';

                    // Handle photo upload
                    $photo_updated = false;
                    if(isset($_SESSION['uid']) && isset($_FILES['user_photo']) && $_FILES['user_photo']['error'] == 0) {
                        $upload_dir = "uploads/user_photos/";
                        
                        // Create directory if it doesn't exist
                        if (!is_dir($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }
                        
                        $uid = $_SESSION['uid'];
                        $file_extension = pathinfo($_FILES['user_photo']['name'], PATHINFO_EXTENSION);
                        $photo_name = $uid . '_' . time() . '.' . $file_extension;
                        $upload_path = $upload_dir . $photo_name;
                        
                        // Check file size (2MB limit)
                        if($_FILES['user_photo']['size'] <= 2097152) {
                            // Check if it's actually an image
                            $check = getimagesize($_FILES['user_photo']['tmp_name']);
                            if($check !== false) {
                                if(move_uploaded_file($_FILES['user_photo']['tmp_name'], $upload_path)) {
                                    // Add user_photo column if it doesn't exist
                                    $check_column = mysqli_query($con, "SHOW COLUMNS FROM registration LIKE 'user_photo'");
                                    if(mysqli_num_rows($check_column) == 0) {
                                        mysqli_query($con, "ALTER TABLE registration ADD COLUMN user_photo VARCHAR(255) DEFAULT NULL");
                                    }
                                    
                                    // Get current user photo to delete old one
                                    $old_photo_query = mysqli_query($con, "SELECT user_photo FROM registration WHERE userid='$uid'");
                                    if($old_photo_query && mysqli_num_rows($old_photo_query) > 0) {
                                        $old_photo_data = mysqli_fetch_assoc($old_photo_query);
                                        if(!empty($old_photo_data['user_photo']) && file_exists('uploads/user_photos/' . $old_photo_data['user_photo'])) {
                                            unlink('uploads/user_photos/' . $old_photo_data['user_photo']);
                                        }
                                    }
                                    
                                    // Update user photo in registration table
                                    $update_photo_query = "UPDATE registration SET user_photo='$photo_name' WHERE userid='$uid'";
                                    if(mysqli_query($con, $update_photo_query)) {
                                        $_SESSION['user_photo'] = $photo_name;
                                        $photo_updated = true;
                                    }
                                }
                            } else {
                                echo "<div style='color: red; text-align: center; margin: 10px 0;'>Please upload a valid image file.</div>";
                            }
                        } else {
                            echo "<div style='color: red; text-align: center; margin: 10px 0;'>Photo size should be less than 2MB.</div>";
                        }
                    }

                    include "connect.php";
                    
                    // Insert review
                    $insert_result = mysqli_query($con,"insert into review(name, review, description) values('$nm','$rev','$des')");
                    
                    if($insert_result) {
                        $success_message = "Review submitted successfully!";
                        if($photo_updated) {
                            $success_message .= " Your profile photo has also been updated.";
                        }
                        echo "<h2 style='color: green; text-align:center; margin: 20px 0;'>$success_message</h2>";
                        echo "<script>setTimeout(function(){ window.location.reload(); }, 2000);</script>";
                    } else {
                        echo "<h2 style='color: red; text-align:center; margin: 20px 0;'>Error submitting review. Please try again.</h2>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- CSS for stars -->
<style>
.star-rating {
  direction: rtl;
  display: inline-flex;
  font-size: 30px;
  color: #ccc;
  cursor: pointer;
}

.star-rating input[type="radio"] {
  display: none;
}

.star-rating label {
  padding: 0 5px;
  transition: color 0.3s;
}

.star-rating input[type="radio"]:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
  color: orange;
}

.photo-upload-container button:hover {
  background: #0056b3 !important;
  transform: translateY(-1px);
}

.form-control:focus {
  border-color: #007bff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
</style>

<!-- JS for Emoji Reaction and Photo Preview -->
<script>
document.querySelectorAll('input[name="rating"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        const val = this.value;
        const emojiBox = document.getElementById("emojiFeedback");
        const emojis = {
            "5": "😍 Excellent!",
            "4": "😊 Very Good!",
            "3": "🙂 Good",
            "2": "😐 Okay",
            "1": "😟 Bad"
        };
        emojiBox.innerHTML = emojis[val];
    });
});

function previewPhoto(input) {
    const file = input.files[0];
    const currentPhoto = document.getElementById('currentPhoto');
    
    if (file) {
        // Check file size (2MB = 2097152 bytes)
        if (file.size > 2097152) {
            alert('File size should be less than 2MB');
            input.value = '';
            return;
        }
        
        // Check file type
        if (!file.type.match('image.*')) {
            alert('Please select an image file');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            currentPhoto.innerHTML = '<img src="' + e.target.result + '" style="width: 100%; height: 100%; object-fit: cover;" alt="New Photo Preview">';
        };
        reader.readAsDataURL(file);
    }
}
</script>

<?php include "footer.php"; ?>

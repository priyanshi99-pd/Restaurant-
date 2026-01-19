<?php session_start(); ?>
<?php include "header.php"; ?>  

<!-- Start slides -->
<br>
<div id="slides" class="cover-slides">
    <ul class="slides-container">
        <li class="text-left">
            <img src="images/267.jpg" alt="">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="m-b-20"><strong>Welcome To our <br> Decent Restaurant</strong></h1>
                        <p class="m-b-40">Have It Your Way,<br> </p>
                        <p><a class="btn btn-lg btn-circle btn-outline-new-white" href="menu.php">Food Menu</a></p>
                    </div>
                </div>
            </div>
        </li>
        <li class="text-left">
            <img src="images/1-kathiyawadi-restaurants-1-ki1aq.jpg" alt="">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="m-b-20"><strong>We like  <br> to eat well.</strong></h1>
                        <p class="m-b-40">Decent Restaurant is serving an Authentic Kathiyawadi Food. Restaurant's 
                        <br> 
                        Ambience is very good with well-trained staff and an open kitchen concept...</p>
                        <p><a class="btn btn-lg btn-circle btn-outline-new-white" href="contact.php">Contact Us</a></p>
                    </div>
                </div>
            </div>
        </li>
        <li class="text-left">
            <img src="images/DSC_2734.jpg" alt="">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="m-b-20"><strong><br> Yummy food with us...</strong></h1>
                        <p class="m-b-40">Deliciousness jumping into the mouth<br> 
                        We know our food..</p>
                        <p><a class="btn btn-lg btn-circle btn-outline-new-white" href="review.php">Review</a></p>
                    </div>
                </div>
            </div>
        </li>
    </ul>
    <div class="slides-navigation">
        <a href="#" class="next"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
        <a href="#" class="prev"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
    </div>
</div>
<!-- End slides -->  

<!-- Story/Intro -->
<div class="hero-section" style="background: url('images/bg.jpg') no-repeat center center/cover; height: 100vh; display: flex; justify-content: center; align-items: center;">
    <div class="content-box" style="background-color: white; padding: 30px 40px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.2); max-width: 500px; text-align: center;">
        <h2 style="color: orange; margin-bottom: 10px;">Welcome To our Decent Restaurant</h2>
        <h4>Little Story</h4>
        <p style="color: #333; line-height: 1.5; font-size: 15px;">
            Restaurant in Anand, a hidden gem in the city. Served flavors divine, making taste buds feel giddy.
            With fragrant spices and culinary finesse, every dish was a culinary success. Patrons left content,
            with smiles and delight. Anand Restaurant, a dining experience that felt just right.
        </p>
        <p><a class="btn btn-lg btn-circle btn-outline-new-white" href="contact.php" style="margin-top: 20px; background-color: orange; color: white; border: none; padding: 12px 20px; border-radius: 5px; font-weight: bold; cursor: pointer; transition: background-color 0.3s ease;">Contact us</a></p>
    </div>
</div>
<!-- End About -->

<!-- Start QT -->
<div class="qt-box qt-background">
    <div class="container">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto text-center">
                <p class="lead ">
                    " If you're not the one cooking, stay out of the way and compliment the chef. "
                </p>
                <span class="lead">Decent Restaurant</span>
            </div>
        </div>
    </div>
</div>
<!-- End QT -->
<!-- Start Customer Reviews -->
<!-- Start Customer Reviews -->
<div class="customer-reviews-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="heading-title text-center">
                    <h2>Customer Reviews</h2>
                    <p>"If you build a greater experience, customers tell each other about that. Word of mouth is very powerful."</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 mr-auto ml-auto text-center">
                <div id="reviews" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner mt-4">
                        <?php 
                            include "connect.php";
                            
                            // Debug: First try simple query
                            $s = mysqli_query($con,"SELECT r.*, reg.user_photo, reg.userid as reg_userid 
                                                   FROM review r 
                                                   LEFT JOIN registration reg ON r.name = reg.userid 
                                                   ORDER BY r.id DESC 
                                                   LIMIT 4");
                            
                            echo "<!-- Debug: Query result count: " . ($s ? mysqli_num_rows($s) : 'Query failed') . " -->";
                            
                            $active = "active";
                            
                            if($s && mysqli_num_rows($s) > 0) {
                                while ($r = mysqli_fetch_array($s)) {
                                    // Debug output
                                    echo "<!-- Debug: User: " . $r['name'] . ", Photo: " . $r['user_photo'] . " -->";
                        ?>
                        <div class="carousel-item text-center <?php echo $active; ?>">
                            <div class="img-box p-1 border rounded-circle m-auto" style="width: 120px; height: 120px; overflow: hidden;">
                                <?php 
                                // Debug: Show what we're checking
                                $photo_path = 'uploads/user_photos/' . $r['user_photo'];
                                echo "<!-- Debug: Checking photo path: " . $photo_path . " -->";
                                echo "<!-- Debug: File exists: " . (file_exists($photo_path) ? 'YES' : 'NO') . " -->";
                                
                                // Check if user has photo and file exists
                                if(!empty($r['user_photo']) && file_exists($photo_path)): 
                                ?>
                                    <img class="d-block w-100 h-100 rounded-circle" 
                                         src="<?php echo htmlspecialchars($photo_path); ?>" 
                                         alt="<?php echo htmlspecialchars($r['name']); ?>"
                                         style="object-fit: cover;">
                                <?php else: ?>
                                    <!-- Fallback: Show user initial -->
                                    <div class="d-block w-100 h-100 rounded-circle d-flex align-items-center justify-content-center" 
                                         style="background: linear-gradient(135deg, #e67e22, #d35400); color: white; font-size: 48px; font-weight: bold;">
                                        <?php echo strtoupper(substr($r['name'], 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <h5 class="mt-4 mb-0">
                                <strong class="text-warning text-uppercase">
                                    <?php echo htmlspecialchars($r['name']); ?>
                                </strong>
                            </h5>
                            <h6 class="text-dark m-0">Review: <?php echo htmlspecialchars($r['review']); ?></h6>
                            <p class="m-0 pt-3"><?php echo htmlspecialchars($r['description']); ?></p>
                        </div>
                        <?php 
                            $active = ""; // Only the first item gets 'active'
                                }
                            } else {
                        ?>
                        <!-- No reviews found -->
                        <div class="carousel-item text-center active">
                            <div class="img-box p-1 border rounded-circle m-auto" style="width: 120px; height: 120px;">
                                <div class="d-block w-100 h-100 rounded-circle d-flex align-items-center justify-content-center" 
                                     style="background: linear-gradient(135deg, #e67e22, #d35400); color: white; font-size: 48px;">
                                    ⭐
                                </div>
                            </div>
                            <h5 class="mt-4 mb-0">
                                <strong class="text-warning text-uppercase">No Reviews Yet</strong>
                            </h5>
                            <h6 class="text-dark m-0">Be the first to share your experience!</h6>
                            <p class="m-0 pt-3">
                                <?php if(isset($_SESSION['uid'])): ?>
                                    <a href="review.php" class="btn btn-warning">Write a Review</a>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-success">Login to Review</a>
                                <?php endif; ?>
                            </p>
                        </div>
                        <?php } ?>
                    </div>
                    
                    <?php if($s && mysqli_num_rows($s) > 1): ?>
                    <a class="carousel-control-prev" href="#reviews" role="button" data-slide="prev">
                        <i class="fa fa-angle-left" aria-hidden="true"></i>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#reviews" role="button" data-slide="next">
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                        <span class="sr-only">Next</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Customer Reviews -->

<!-- End Customer Reviews -->

<?php include "footer.php"; ?>

<?php include "header.php"; ?>
	<!-- Start header -->
	<img src="images/banner_about_us.jpg" width="100%">
	<!-- End header -->
	
	<!-- Start About -->
	<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Decent Restaurant</title>
  <style>
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: Arial, sans-serif;
    }

    .hero-section {
      background: url('images/bg.jpg') no-repeat center center/cover;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .content-box {
      background-color: white;
      padding: 30px 40px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
      max-width: 500px;
      text-align: center;
    }

    .content-box h2 {
      color: orange;
      margin-bottom: 10px;
    }

    .content-box p {
      color: #333;
      line-height: 1.5;
      font-size: 15px;
    }

    .content-box button {
      margin-top: 20px;
      background-color: orange;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .content-box button:hover {
      background-color: darkorange;
    }
  </style>
</head>
<body>

  <div class="hero-section">
    <div class="content-box">
		<h2>Welcome To <span>Decent Resturant</span></h2>
      <h3>Little Story</h4>
      <p>Located in the heart of Anand, Decent Restaurant is your go-to place for delicious food, warm hospitality, and a cozy dining experience. Whether you're craving traditional Indian flavors or something a bit more modern, we serve every dish with love and freshness.</p>
		<p>At Decent Restaurant, we believe in quality, hygiene, and great service. Our friendly staff, comfortable ambiance, and mouthwatering menu make every visit memorable – whether it’s a family dinner, a casual outing, or a special celebration.
			Come, enjoy a decent meal – because at Decent Restaurant, it’s not just food, it’s a feeling.
		</p>
   </div>
  </div>

</body>
</html>

	<!-- End About -->
	
	<?php include "footer.php" ; ?>
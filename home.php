<?php 
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AJ Graphics</title>
    <link rel="stylesheet" href="styles.css" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins&display=swap"
      rel="stylesheet"
    />
    <script
      src="https://kit.fontawesome.com/eedbcd0c96.js"
      crossorigin="anonymous"
    ></script>
  </head>
  <body>
   
  <?php include_once "navbar.php";?>
    <section id="hero">
      <h2>Welcome to AJ Graphics</h2>
      <h1>High Quality Printing & Designing</h1>
      <p>Print with Impact. Design with Style!</p>
      <button>Place Order Now</button>
    </section>

    <section id="feature" class="section-p1">
      <div class="featurebox">
        <img src="images/onlineOrder.png" alt="" class="fsimg" />
        <h5>Order Online</h5>
      </div>
      <div class="featurebox">
        <img src="images/pickUpDelivery.png" alt="" class="fsimg" />
        <h5>Pick-up & Delivery</h5>
      </div>
      <div class="featurebox">
        <img src="images/promo.png" alt="" class="fsimg" />
        <h5>Promotions</h5>
      </div>
      <div class="featurebox">
        <img src="images/customize.jpg" alt="" class="fsimg" />
        <h5>Customization</h5>
      </div>
      <div class="featurebox">
        <img src="images/support.png" alt="" class="fsimg" />
        <h5>Support</h5>
      </div>
    </section>

    <section id="product1" class="section-p1">
      <h2>POPULAR SERVICES</h2>
      <p>High Quality Printing and Graphics</p>
      <div class="pro-cont js-pro-cont"></div>
    </section>
    
    <!-- this can be used for banners later on like announcements -->
    <!-- <section id="banner">
      <h2>First <span>25 Customers</span> to use the code on our Facebook gets <span>5% off!</span></h2>
      <button class="button-normal">ORDER NOW</button>
    </section> -->

    <section id="chotu1banner" class="section-p1">
      <div class="bannerbox">
        <h4>Printing</h4>
        <h3>Tarpaulines, T-Shirts, Souvenirs, Documents & More!</h3>
        <span>Request for prints here!</span>
        <button class="button-transparent">See More</button>
      </div>

      <div class="bannerbox">
        <h4>Designing</h4>
        <h3>Graphic Design, Logo Design, Invitations & More!</h3>
        <span>Choose from a template or request one here!</span>
        <button class="button-transparent">See More</button>
      </div>
    </section>

   
    

    <script src="scripts/cart.js"></script>
    <script src="scripts/products.js"></script>
    <script src="home.js"></script>
    <?php include_once "footer.php"?>
  </body>
</html>

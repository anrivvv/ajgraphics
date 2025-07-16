
<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="Login_SignUp.css" />
  
    <title>Sign in & Sign up Form</title>
  </head>
  <body>
    <?php include_once "navbar.php" ?>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form action="login_handler.inc.php" method="post" class="sign-in-form">
            <h2 class="title">Log in</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" placeholder="Username" class="form-control" type="text" name="username" required/>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input class="form-control" placeholder="Password" type="password" name="password" required/>
            </div>
            <input type="submit" value="Login" class="btn solid" />
            <?php if (isset($_SESSION['error'])): ?>
            <p style="color: red; text-align: center;"><?= $_SESSION['error']?></p>
            <?php else: ?>
            <?php endif; ?>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
           
          </form>




          <form action="signup_handler.inc.php" method="post" class="sign-up-form">
            <h2 class="title">Sign up</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input class="form-control" placeholder="Username" type="text" name="username" required>
            </div>
            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input class="form-control" placeholder="Email" type="email" name="email" required>
            </div>
            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input class="form-control" placeholder="Phone Number" type="text" name="phone_number" required>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input class="form-control" placeholder="Password" type="password" name="password" required>
            </div>
            <input type="submit" class="btn" value="Sign up" />
          </form>
        </div>
      </div>
       
      <?php 
    if (isset($_SESSION['error'])) {
      echo '<p style="color:red; text-align:center;">' . $_SESSION['error'] . '</p>';
      unset($_SESSION['error']);  // Unset error after displaying it
    }
    else {
    }
    ?> 
              
      <div class="panels-container">
        <div class="panel left-panel">
          <div class="content">
            <h3>New Here?</h3>
            <p>
              Lorem ipsum, dolo  sit amet consectetur adipisicing elit. Debitis,
              ex ratione. Aliquid!
            </p>
            <button class="btn transparent" id="sign-up-btn">
              Sign up
            </button>
          </div>
          <img src="images/undraw_creativity_z0ey.svg" class="image" alt="" />
        </div>
        <div class="panel right-panel">
          <div class="content">
            <h3>Already have an Account?</h3>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum
              laboriosam ad deleniti.
            </p>
            <button class="btn transparent" id="sign-in-btn">
              Log in
            </button>
          </div>
          <img src="images/undraw_online-ad_t56y.svg" class="image" alt="" />
        </div>
      </div>
    </div>

    <script src="Login_SignUp.js"></script>
  </body>
</html>

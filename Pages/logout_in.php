<!-- <?php
      include_once("../Library/MyLibrary.php");
      ?> -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="stylesheet" href="../style.css? <?= time(); ?>">
  <script src="../script.js"></script>
</head>

<body>
  <?= NavBar('logout_in') ?>
  <div class="login-container">
    <h2>Welcome to Croix-Rouge 👋</h2>
    <p>Empowering communities with care. Please sign in to manage your reservations and services.</p>

    <form action="login_handler.php" method="POST">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="your@email.com" required />

      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="••••••••" required />

      <a href="#" class="forgot">Forgot Password?</a>

      <button type="submit">Sign in</button>
    </form>

    <div class="separator">Or sign in with</div>

    <div class="social-login">
      <button><img src="../img/google.png" width="15px" height="15px" /> Google</button>
      <button><img src="../img/facebook.png" width="15px" height="15px" /> Facebook</button>
    </div>

    <div class="signup-text">
      Don’t have an account? <a href="#">Sign up</a>
    </div>
  </div>

</body>

</html>
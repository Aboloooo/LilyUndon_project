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

  <?php
  if (isset($_POST['username']) && isset($_POST['password'])) {
    $loginCheck = $connection->prepare('select * from users where username =?');
    $loginCheck->bind_param('s', $_POST["username"]);
    $loginCheck->execute();
    $result = $loginCheck->get_result();
    if ($row = $result->fetch_assoc()) {
      $username = $row['Username'];
      $password = $row['Password'];
      $level = $row['Level'];

      if ($password == $_POST['password']) {
        $_SESSION['username'] = $username;
        $_SESSION['level'] = $level;
        $_SESSION["userLogin"] = true;
        header("location: index.php");
        exit();
      } else {
        echo '<script>alert("Password is incorrect!")</script>';
      }
    } else {
      echo '<script>alert("Username is incorrect!")</script>';
    }
  }

  ?>
  <div class="login-container">
    <h2>Welcome to Croix-Rouge 👋</h2>
    <p>Empowering communities with care. Please sign in to manage your reservations and services.</p>

    <form action="" method="POST">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" placeholder="username" required />

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
      Don't have an account? <a href="#">Sign up</a>
    </div>
  </div>

</body>

</html>
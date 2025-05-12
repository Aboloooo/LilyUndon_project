<?php
include_once("../Library/MyLibrary.php");
?>
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

  /* sign in  */
   /* echo password_hash('password', PASSWORD_DEFAULT); */

  if (isset($_POST['username']) && isset($_POST['password'])) {
    $loginCheck = $connection->prepare('select * from users where username =?');
    $loginCheck->bind_param('s', $_POST["username"]);
    $loginCheck->execute();
    $result = $loginCheck->get_result();
    if ($row = $result->fetch_assoc()) {
      $username = $row['Username'];
      $password = $row['Password'];
      $level = $row['Level'];

      //use password verify function
      if(password_verify($_POST['password'], $password)){
$_SESSION["username"] = $username;
        $_SESSION['level'] = $level;
        $_SESSION["userLogin"] = true;
      
        if (strtoupper($level) == 'ADMIN') {
          $_SESSION["Admin"] = true;
        }
        // user will be double checked to see if user still use their initial pass or not
        $sqlChangeYourPass = $connection->prepare('select user_must_change_password from users where username=?');
        $sqlChangeYourPass->bind_param('s', $_SESSION['username']);
        $sqlChangeYourPass->execute();
        $result = $sqlChangeYourPass->get_result();
        $row = $result->fetch_assoc();
        if ($row["user_must_change_password"] == 1) {
          header("location: logout_in.php");
          $_SESSION['userMustChangeThePass'] = true;
        }
        if ($row["user_must_change_password"] == 0) {
          $_SESSION['userMustChangeThePass'] = false;
          header("location: index.php");
        }
        exit();
      } else {
        echo '<script>alert("Password is incorrect!")</script>';
      }
    } else {
      echo '<script>alert("Username couldnt find!")</script>';
    }
  }


  ?>
  <div class="login-container">
    <h2>Welcome to Croix-Rouge 👋</h2>
    <?php if ($_SESSION["userMustChangeThePass"]): ?>
      <p style="color: #b30000; background-color: #ffe6e6; border: 1px solid #b30000; padding: 10px; border-radius: 5px; font-weight: bold;">
        Your initial password needs to be changed. Please change your password and try again.
      </p>
    <?php elseif ($_SESSION["userLogin"]): ?>
      <p style="color: #004085; background-color: #e2e3e5; border: 1px solid #b8daff; padding: 10px; border-radius: 5px;">
        Secure your account. Please update your password and keep your information safe.
      </p>
    <?php else: ?>
      <p style="color: #0c5460; background-color: #d1ecf1; border: 1px solid #bee5eb; padding: 10px; border-radius: 5px;">
        Empowering communities with care. Please sign in to manage your reservations and services.
      </p>
    <?php endif; ?>
    <form action="" method="POST">
      <?php
      if (!$_SESSION["userLogin"]) {
      ?>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="username" required />

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="••••••••" required />

        <a href="#" class="forgot">Forgot Password?</a>

        <button type="submit">Sign in</button>
        <div class="separator">Or sign in with</div>

        <div class="social-login">
          <button><img src="../img/google.png" width="15px" height="15px" /> Google</button>
          <button><img src="../img/facebook.png" width="15px" height="15px" /> Facebook</button>
        </div>

        <div class="signup-text">
          Don't have an account? <a href="#">Sign up</a>
        </div>
    </form>
  </div>

<?php
      } else {

?>
  <form action="" method="POST">
    <!-- Chaging password -->
    <?php
        if (isset($_POST["logout"])) {
          session_unset();
          session_destroy();
          header("Refresh:0");
        }
        if (isset($_POST["updateBtn"])) {
          if (isset($_POST["CurrentPassword"]) && isset($_POST["NewPassword"]) && isset($_POST["ConfirmNewPassword"])) {
            $loggedInUserPass = $connection->prepare('select Password from users where username =?');
            $loggedInUserPass->bind_param('s', $_SESSION["username"]);
            $loggedInUserPass->execute();
            $result = $loggedInUserPass->get_result();
            $passRow = $result->fetch_assoc();
            if ($passRow) {
              $userSessionPass = $passRow["Password"];
              if ($_POST["CurrentPassword"] == $userSessionPass) {

                if ($_POST["NewPassword"] == $_POST["ConfirmNewPassword"]) {
                  $newPassword = $_POST["ConfirmNewPassword"];

                  $updatePass = $connection->prepare('UPDATE users SET Password = ?, user_must_change_password = ? WHERE Username = ?');
                  $zeroValue = 0;
                  $updatePass->bind_param("sis", $newPassword, $zeroValue, $_SESSION["username"]);

                  if ($updatePass->execute()) {
                    $_SESSION["userMustChangeThePass"] = false;
                    echo '<script>alert("Password updated successfully.");</script>';
                    header("Refresh:0");
                  } else {
                    echo '<script>alert("Failed to update password.");</script>';
                  }
                } else {
                  echo '<script>alert("New passwords do not match!");</script>';
                  exit();
                }
              } else {
                echo '<script>alert("Your current password is incorrect.")</script>';
              }
            } else {
              echo '<script>alert("user didnt find")</script>';
            }
          } else {
            echo '<script>alert("All fields required!")</script>';
          }
        }

    ?>
    <label for="username">Current Password</label>
    <input type="password" id="username" name="CurrentPassword" placeholder="Current password" required />

    <label for="username">New Password</label>
    <input type="password" id="username" name="NewPassword" placeholder="••••••••" required />

    <label for="password">Confirm New Password</label>
    <input type="password" id="password" name="ConfirmNewPassword" placeholder="••••••••" required />

    <a href="#" class="forgot">Forgot Password?</a>

    <button type="submit" name="updateBtn">Update password</button>

  <?php
      }
  ?>
  </form>

  <form action="" method="POST">
    <button type="submit" name="logout">Log out</button>
  </form>


</body>

</html>
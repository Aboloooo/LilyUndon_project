<?php
include_once("../Library/MyLibrary.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $t['login_logout'] ?></title>
  <link rel="stylesheet" href="../style.css? <?= time(); ?>">
  <script src="../script.js"></script>
</head>

<body>
  <?= NavBar('logout_in') ?>

  <?php

  /* sign in  */
  //echo password_hash('password', PASSWORD_DEFAULT);

  if (isset($_POST['username']) && isset($_POST['password'])) {
    $loginCheck = $connection->prepare('select * from users where username =?');
    $loginCheck->bind_param('s', $_POST["username"]);
    $loginCheck->execute();
    $result = $loginCheck->get_result();
    if ($row = $result->fetch_assoc()) {
      $username = $row['Username'];
      $password = $row['Password'];
      $level = $row['AccessLevelID'];

      //use password verify function
      if (password_verify($_POST['password'], $password)) {
        $_SESSION["username"] = $username;
        $_SESSION['level'] = $level;
        $_SESSION["userLogin"] = true;

        if ($level == 1) {
          $_SESSION["Admin"] = true;
        }else if($level == 2){
          $_SESSION["SecurityAccess"] = true;
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
        echo "<script>alert('" . $t['password_incorrect'] . "')</script>";
      }
    } else {
      echo "<script>alert('" . $t["username_not_found"] . "')</script>";
    }
  }


  ?>
  <div class="login-container">
    <h2><?= $t['welcome_message'] ?> 👋</h2>
    <?php if ($_SESSION["userMustChangeThePass"]): ?>
      <p style="color: #b30000; background-color: #ffe6e6; border: 1px solid #b30000; padding: 10px; border-radius: 5px; font-weight: bold;">
        <?= $t['initial_password_change_required'] ?>
      </p>
    <?php elseif ($_SESSION["userLogin"]): ?>
      <p style="color: #004085; background-color: #e2e3e5; border: 1px solid #b8daff; padding: 10px; border-radius: 5px;">
        <?= $t['secure_account'] ?>
      </p>
    <?php else: ?>
      <p style="color: #0c5460; background-color: #d1ecf1; border: 1px solid #bee5eb; padding: 10px; border-radius: 5px;">
        <?= $t['welcome_tagline'] ?>
      </p>
    <?php endif; ?>
    <form action="" method="POST">
      <?php
      if (!$_SESSION["userLogin"]) {
      ?>
        <label for="username"><?= $t['username'] ?></label>
        <input type="text" id="username" name="username" placeholder="<?= $t['username'] ?>" required />

        <label for="password"><?= $t['password'] ?></label>
        <input type="password" id="password" name="password" placeholder="••••••••" required />

        <a href="#" class="forgot"><?= $t['forgot_password'] ?></a>

        <button type="submit"><?= $t['sign_in'] ?></button>
        <div class="separator"><?= $t['or_sign_in_with'] ?></div>

        <div class="social-login">
          <button><img src="../img/google.png" width="15px" height="15px" /> Google</button>
          <button><img src="../img/facebook.png" width="15px" height="15px" /> Facebook</button>
        </div>

        <div class="signup-text">
          <?= $t['no_account'] ?> <a href="add_user.php"><?= $t['sign_up'] ?></a>
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
              if (password_verify($_POST["CurrentPassword"], $userSessionPass)) {

                if ($_POST["NewPassword"] == $_POST["ConfirmNewPassword"]) {
                  $newPassword = $_POST["ConfirmNewPassword"];
                  $newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);

                  $updatePass = $connection->prepare('UPDATE users SET Password = ?, user_must_change_password = ? WHERE Username = ?');
                  $zeroValue = 0;
                  $updatePass->bind_param("sis", $newPasswordHashed, $zeroValue, $_SESSION["username"]);

                  if ($updatePass->execute()) {
                    $_SESSION["userMustChangeThePass"] = false;
                    echo '<script>alert("' . $t["password_updated"] . '");</script>';
                    header("Refresh:0");
                  } else {
                    echo '<script>alert("' . $t["password_update_failed"] . '.");</script>';
                  }
                } else {
                  echo '<script>alert("' . $t["new_passwords_do_not_match"] . '");</script>';
                  exit();
                }
              } else {
                echo '<script>alert("' . $t["current_password_incorrect"] . '!")</script>';
              }
            } else {
              echo '<script>alert("' . $t["user_not_found"] . '")</script>';
            }
          } else {
            echo '<script>alert("' . $t["all_fields_required"] . '!")</script>';
          }
        }

    ?>
    <label for="username"><?= $t['current_password'] ?></label>
    <input type="password" id="username" name="CurrentPassword" placeholder="<?= $t['current_password'] ?>" required />

    <label for="username"><?= $t['new_password'] ?></label>
    <input type="password" id="username" name="NewPassword" placeholder="••••••••" pattern="(?=.*\d).{7,}" title="at least 7 characters long,one number and one special character " required />

    <label for="password"><?= $t['confirm_new_password'] ?></label>
    <input type="password" id="password" name="ConfirmNewPassword" placeholder="••••••••" required />

    <a href="#" class="forgot"><?= $t['forgot_password'] ?></a>

    <button type="submit" name="updateBtn"><?= $t['update_password'] ?></button>
  </form>

  <form action="" method="post">
    <button id="username" type="submit" name="logout"><?= $t['Logout'] ?></button>
  </form>
<?php
      }
?>


</body>

</html>
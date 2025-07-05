<?php
include_once("../Library/MyLibrary.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $t['home'] ?></title>
  <link rel="stylesheet" href="../style.css? <?= time(); ?>">
  <!-- bank of icons -->
  <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <!-- bank of img 
  https://www.flaticon.com/free-icons/google 
  -->
</head>

<body>
  <?= NavBar('index') ?>

  <div class="img">
    <div class="part1">
      <img src="../img/img1.jpg" alt="" />
    </div>
    <?php
    $reserveOrLogout = '';
    if ($_SESSION['userLogin']) {
      $reserveOrLogout = 'Logout';
    } else {
      $reserveOrLogout = 'Reserve';
    }

    if ($reserveOrLogout) {
      if (isset($_POST['Logout'])) {
        session_unset();
        session_destroy();
        header("Refresh:0");
      }
      if (isset($_POST['Reserve'])) {
        header("location: reserve.php");
        //if user is not login user must be redirected to login page
      }
      if (isset($_POST['Reserve']) && !$_SESSION['userLogin']) {
        header("location: logout_in.php");
      }
    }
    ?>
    <div class="part2">
      <h1><?= $t['plan'] ?> | <?= $t['cook'] ?> | <?= $t['enjoy'] ?></h1>
      <form action="" method='post'>
        <button class="reservationBtn" name="<?= $reserveOrLogout ?>" role="button"><?= $t[$reserveOrLogout] ?></button>
      </form>
    </div>



    <script src="../script.js"></script>

</body>

</html>
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
  <!-- bank of icons -->
  <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
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
      }
    }
    ?>
    <div class="part2">
      <h1>Plan | Cook | Enjoy</h1>
      <form action="" method='post'>
        <button class="reservationBtn" name="<?= $reserveOrLogout ?>" role="button"><?= $reserveOrLogout ?></button>
      </form>
    </div>
</body>

</html>
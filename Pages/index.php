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

    ?>
    <div class="part2">
      <h1>Plan | Cook | Enjoy</h1>

      <button class="reservationBtn" role="button">Reserve</button>
    </div>
</body>

</html>
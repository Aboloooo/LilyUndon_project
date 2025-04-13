<!-- <?php
      include_once("../Library/MyLibrary.php");
      ?> -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="stylesheet" href="../style.css" />
  <script src="../script.js"></script>
</head>

<body>
  <nav class="navbar">
    <div class="logo">
      <img src="../img/Logo.png" alt="Croix-Rouge" class="logo-icon" />
    </div>
    <ul class="nav-links">
      <li><a href="index.php">Home</a></li>
      <li><a href="reserve.php">Reserve</a></li>
      <li><a href="my_reservations.php">My Reservations</a></li>
      <li><a href="logout.php">Logout</a></li>
      <li>
        <select onchange="changeLanguage(this.value)">
          <option value="en">EN</option>
          <option value="fr">FR</option>
          <option value="de">DE</option>
        </select>
      </li>
    </ul>
    <div class="burger">
      <div class="line1"></div>
      <div class="line2"></div>
      <div class="line3"></div>
    </div>
  </nav>
</body>

</html>
<?php
include_once("../Library/MyLibrary.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $t['my_reservations'] ?></title>
  <link rel="stylesheet" href="../style.css?<?= time(); ?>">
  <script src="../script.js"></script>
</head>

<body>
  <?= NavBar('my_reservations') ?>

  <?php

  $userInfo = $connection->prepare('SELECT * FROM users WHERE Username = ?');
  $userInfo->bind_param('s', $_SESSION['username']);
  $AdminMode = false;

  if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
  } else {
    if ($_SESSION['username'] !== "Unknown") {
      $userInfo->execute();
      $userInfoResult = $userInfo->get_result();
      $userRow = $userInfoResult->fetch_assoc();

      if (strtoupper($userRow['Level']) === 'ADMIN') {
        $AdminMode = true;
        $displayReservations = $connection->prepare('SELECT * FROM reservation');
      } else {
        $displayReservations = $connection->prepare('SELECT * FROM reservation WHERE Reserved_by_userID = ?');
        $displayReservations->bind_param('i', $userRow['UserID']);
      }

      $displayReservations->execute();
      $reservations = $displayReservations->get_result();

      if ($reservations->num_rows > 0) {

        if (isset($_POST['cancel'])) {
          $cancelDateTime = $_POST['datetime'];
          // $session username makes an error here
          // $delete = $connection->prepare("DELETE FROM reservation WHERE StartMoment = ? AND Reserved_by_userID = (SELECT UserID FROM users WHERE Username = ?)");
          if ($AdminMode) {
            $queary = "DELETE FROM reservation WHERE StartMoment = ?";
            $delete = $connection->prepare($queary);
            $delete->bind_param('s', $cancelDateTime);
          } else {
            $queary = "DELETE FROM reservation WHERE StartMoment = ? AND Reserved_by_userID = (SELECT UserID FROM users WHERE Username = ?)";
            $delete = $connection->prepare($queary);
            $delete->bind_param('ss', $cancelDateTime, $_SESSION['username']);
          }
          if ($delete->execute()) {
            echo '<script>alert("' . $t["reservation_cancelled_successfully"] . '");</script>';
            header("Refresh:0");
          } else {
            echo "<script>alert('" . $t["cancellation_failed"] . "');</script>";
          }
        }
  ?>

        <div class="my_reservation_container">
          <h1><?= $t["my_kitchen_reservations"] ?></h1>
          <table>
            <thead>
              <tr>

                <th>#</th>
                </a>
                <?= ($AdminMode) ? '<th>Reserved by user_ID</th>' : ''; ?>
                <th><?= $t["date"] ?></th>
                <th><?= $t["time_slot"] ?></th>
                <th><?= $t["action"] ?></th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              while ($row = $reservations->fetch_assoc()) {
                $user_ID = $row['Reserved_by_userID'];
                $startMoment = $row['StartMoment'];
                $reservedDate = substr($startMoment, 0, 10);
                $reservedTime = substr($startMoment, 11);
                $startTime = new DateTime($reservedTime);
                $endTime = clone $startTime;
                $endTime->modify('+3 hours');

                $timeSlot = $startTime->format('H:i') . ' - ' . $endTime->format('H:i');
              ?>
                <tr>
                  <td><?= $i++ ?></td>
                  <?= ($AdminMode) ? '<td>' . $user_ID . '</td>' : ''; ?>
                  <td><?= $reservedDate ?></td>
                  <td><?= $timeSlot ?></td>
                  <td>
                    <form method="POST" onsubmit="return confirm('<?= $t['confirm_cancel_reservation'] ?>');">
                      <input type="hidden" name="datetime" value="<?= $startMoment ?>">
                      <button type="submit" class="cancel-btn" name="cancel"><?= $t['cancel'] ?></button>
                    </form>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      <?php
      } else {
      ?>
        <div class="no-reservations"><?= $t['no_reservations_yet'] ?></div>
      <?php
      }
    } else {
      ?>
      <div class="no-reservations"><?= $t['please_login_to_view_reservations'] ?></div>
  <?php
    }
  }
  ?>
</body>

</html>
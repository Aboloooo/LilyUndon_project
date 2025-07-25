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
  <!-- bank of icons -->
  <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
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

      if ($userRow['AccessLevelID'] == 1) {
        $AdminMode = true;
        $displayReservations = $connection->prepare('SELECT * FROM reservation join users on Reserved_by_userID = UserID  join Sites on reservation.SiteId = Sites.SiteId');
      } else {
        $displayReservations = $connection->prepare('SELECT * FROM reservation join Sites on reservation.SiteId = Sites.SiteId  WHERE Reserved_by_userID = ?');
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
                <th><?= $t["site"] ?></th>
                </a>
                <?php if ($AdminMode): ?>
                  <th><?= $t["reserved_by"] ?></th>
                <?php endif; ?>
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
                $site = $row['SiteName'];
                /* first and last name of who reserved will be display only to admin */
                if ($AdminMode) {
                  if ($row['AccessLevelID'] == 1) {
                    $reservedBy = '<td>Admin</td>';
                  } else {
                    $first_name = ucfirst($row['First_name']);
                    $last_name = strtoupper($row['Last_name']);
                    $reservedBy = '<td>' .  $last_name . ' ' . $first_name . '</td>';
                  }
                }
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
                  <td><?= $site ?></td>
                  <?= ($AdminMode) ? $reservedBy : ''; ?>
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





  <script src="../script.js"></script>

</body>

</html>
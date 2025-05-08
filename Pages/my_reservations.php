<?php
include_once("../Library/MyLibrary.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Reservations</title>
  <link rel="stylesheet" href="../style.css?<?= time(); ?>">
  <script src="../script.js"></script>
</head>

<body>
  <?= NavBar('my_reservations') ?>

  <?php
  if (isset($_POST['cancel'])) {
    $cancelDateTime = $_POST['datetime'];
    // $session username makes an error here
    $delete = $connection->prepare("DELETE FROM reservation WHERE StartMoment = ? AND Reserved_by_userID = (SELECT UserID FROM users WHERE Username = ?)");
    $delete->bind_param('ss', $cancelDateTime, $_SESSION['username']);
    if ($delete->execute()) {
      echo "<script>alert('Reservation cancelled successfully');</script>";
    } else {
      echo "<script>alert('Cancellation failed');</script>";
    }
  }

  $userInfo = $connection->prepare('SELECT * FROM users WHERE Username = ?');
  $userInfo->bind_param('s', $_SESSION['username']);

  if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
  } else {
    $userInfo->execute();
    $userInfoResult = $userInfo->get_result();
    $userRow = $userInfoResult->fetch_assoc();

    if (strtoupper($userRow['Level']) === 'ADMIN') {
      $displayReservations = $connection->prepare('SELECT * FROM reservation');
    } else {
      $displayReservations = $connection->prepare('SELECT * FROM reservation WHERE Reserved_by_userID = ?');
      $displayReservations->bind_param('i', $userRow['UserID']);
    }

    $displayReservations->execute();
    $reservations = $displayReservations->get_result();

    if ($reservations->num_rows > 0) {
  ?>

      <div class="my_reservation_container">
        <h1>My Kitchen Reservations</h1>
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Date</th>
              <th>Time Slot</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 1;
            while ($row = $reservations->fetch_assoc()) {
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
                <td><?= $reservedDate ?></td>
                <td><?= $timeSlot ?></td>
                <td>
                  <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
                    <input type="hidden" name="datetime" value="<?= $startMoment ?>">
                    <button type="submit" class="cancel-btn" name="cancel">Cancel</button>
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
      <div class="no-reservations">You have no reservations yet.</div>
  <?php
    }
  }
  ?>
</body>

</html>
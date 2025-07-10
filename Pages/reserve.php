<?php
include_once("../Library/MyLibrary.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $t['kitchen_reservation_calendar'] ?></title>
  <link rel="stylesheet" href="../style.css? <?= time(); ?>">
  <!-- bank of icons -->
  <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

</head>

<body>
  <?= NavBar('reserve') ?>

  <h1><?= $t['kitchen_reservation_calendar'] ?></h1>

  <?php
  /* security guard can not reserve time slot */
  $disableForSecurity = $_SESSION["SecurityAccess"] ? 'disabled' : ' ';

  $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
  $times = [];

  $currentMonth = date('m');
  $currentYear = date('Y');
  $TotalNumberOfDaysInTheMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

  for ($h = 8; $h <= 17; $h += 3) {
    $times[] = sprintf('%02d:00 - %02d:00', $h, $h + 3);
  }

  $minOffset = -1;
  $maxOffset = 5;

  /* Finding last Monday date */
  $lastMonday = strtotime('monday this week'); // timestamp of this week's Monday

  /* handle week offset */
  $weekOffset = isset($_GET['week']) ? intval($_GET['week']) : 0;
  if (isset($_GET['prevWeek'])) {
    $weekOffset--;
  } elseif (isset($_GET['nextWeek'])) {
    $weekOffset++;
  }

  if ($weekOffset < $minOffset) {
    $weekOffset = $minOffset;
  }
  if ($weekOffset > $maxOffset) {
    $weekOffset = $maxOffset;
  }
  if (isset($_GET['prevWeek']) && $minOffset == -1) {
    $minOffset = -1;
  }

  $startOfWeek = strtotime("+$weekOffset week", $lastMonday);

  /* finding last date day of the displayed week */
  $endOfWeek = strtotime("+6 days", $startOfWeek);
  $lastDayOfWeek = date('Y-m-d', $endOfWeek);

  $currentMonthYear = date('Y-m F', $startOfWeek);
  $currentMonthYearName = date('F', $startOfWeek);


  $startOfWeekGoodFormat = date('Y-m-d', $startOfWeek);


  // get dates for each day
  $fullWeekDates = [];
  $weekDates = [];
  $weekDatesAnotherFormat = [];
  for ($i = 0; $i < 7; $i++) {
    $fullWeekDates[] = date('Y-m-d', strtotime("+$i day", $startOfWeek));
    $weekDates[] = date('d/m', strtotime("+$i day", $startOfWeek));
    $weekDatesAnotherFormat[] = date('Y:m:d', strtotime("+$i day", $startOfWeek));
  }



  if (isset($_POST['reservationBtn'])) {
    $canBookTimeSlot = false;

    if ($_SESSION['username'] !== "Unknown") {
      $userIDStmt = $connection->prepare("SELECT UserID,AccessLevelID FROM users WHERE Username = ?");
      $userIDStmt->bind_param('s', $_SESSION['username']);
      $userIDStmt->execute();
      $result = $userIDStmt->get_result();
      $row = $result->fetch_assoc();

      // if user is not login user must be redirected to login page

      $userId = $row['UserID'];
      $Level = $row['AccessLevelID'];
      $date = $_POST['day'];
      $time = $_POST['time'];


      $reservationCheckStatement = $connection->prepare('select count(*) as cnt from reservation where Reserved_by_userID = ? and Date(StartMoment) <= ? and Date(StartMoment)>=? and SiteId=?');

      $reservationCheckStatement->bind_param('issi', $userId, $lastDayOfWeek, $startOfWeekGoodFormat, $_SESSION["currentSite"]);
      $reservationCheckStatement->execute();
      $resultOfReservationCheck = $reservationCheckStatement->get_result();
      $BookedTimeSlotsNumber = $resultOfReservationCheck->fetch_assoc();

      if ($BookedTimeSlotsNumber) {
        if ($BookedTimeSlotsNumber['cnt'] <= 3 || $Level == 1) {
          $canBookTimeSlot = true;
        }
      }

      if ($canBookTimeSlot) {
        $curDateToday = $date;
        $reservationDate = $curDateToday;
        $currentTimeSlot = $curDateToday . ' ' . substr($time, 0, 5);

        /* check if user is booking in the same day */
        $checkSameDayStmt  = $connection->prepare('select count(*) as count from reservation where Reserved_by_userID = ? and DATE(StartMoment) = ? and SiteId=?');
        $checkSameDayStmt->bind_param('isi', $userId, $reservationDate, $_SESSION["currentSite"]);
        $checkSameDayStmt->execute();
        $resultOfCheckSameDayStmt  = $checkSameDayStmt->get_result();
        $sameDayCount = $resultOfCheckSameDayStmt->fetch_assoc()['count'];
        if ($sameDayCount > 0 && $Level != 1) {
          echo "<script>alert('" . $t['already_have_reservation_on_day'] . "')</script>";
        } else {
          $sqlReservationInsert = $connection->prepare('insert into reservation(Reserved_by_userID,StartMoment,SiteId) values (?,?,?) ');
          $sqlReservationInsert->bind_param('isi', $userId, $currentTimeSlot, $_SESSION["currentSite"]);
          if ($sqlReservationInsert->execute()) {
            echo "<script>alert('" . $t['reservation_done_successfully'] . "')</script>";
          } else {
            echo "<script>alert('" . $t['error'] . "')</script>";
          }
        }
      } else {
        echo "<script>alert('" . $t['reservation_limit_exceeded'] . "')</script>";
      }
    } else {
      echo "<script>
        alert('" . $t['please_login_first'] . "')
          window.location.href = 'logout_in.php';
        </script>";
    }
  }

  ?>
  <div class="calMovement">
    <form action="" method="get" class="calendar-nav">
      <button id="prevWeek" name="prevWeek">◀</button>
      <span id="monthYear"><?= $currentMonthYear ?></span>
      <button id="nextWeek" name="nextWeek">▶</button>
      <input type="hidden" name="week" value="<?= $weekOffset ?>">
    </form>
  </div>
  <div class="reservation_table_content">

    <div class="btns">
      <form method="POST" id="siteForm">
        <select name="site" class="select-green" onchange="document.getElementById('siteForm').submit();">
          <?php
          $sqlSelectSite = $connection->prepare('SELECT * FROM Sites');
          $sqlSelectSite->execute();
          $resutlOfSite = $sqlSelectSite->get_result();
          while ($row = $resutlOfSite->fetch_assoc()) {
            $siteID = $row['SiteId'];
            $siteName = $row['SiteName'];
          ?>
            <option value="<?= $siteID ?>" <?= ($siteID == $_SESSION["currentSite"] ? "selected" : "") ?>>
              <?= $siteName ?>
            </option>
          <?php } ?>
        </select>
      </form>

      <?php if ($_SESSION["SecurityAccess"]) { ?>
        <button onclick="window.print()" class="btn-red-print">
          <i class='bx  bx-printer'></i> Print
        </button>
      <?php }; ?>
    </div>


    <table class="reservationTable" border="1" cellspacing="0" cellpadding="10">
      <thead>
        <tr>
          <th><?= $t['time'] ?></th>

          <?php
          $today = date('m-d');
          foreach ($days as $index => $day):
            $isToday = ($weekDates[$index] === $today);
            $BackHeadingColor = $isToday ? 'style="background-color: #e3f2fd;"' : '';
          ?>
            <th <?= $BackHeadingColor ?>><?= $t[$day] ?><br><small>(<?= $weekDates[$index] ?>)</small></th>
          <?php endforeach; ?>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($times as $time): ?>
          <tr>
            <td><strong><?= $time ?></strong></td>
            <?php foreach ($days as $index => $day): ?>
              <?php
              $currentTimeSlot = $weekDatesAnotherFormat[$index] . " " . substr($time, 0, 5);
              $currentTimeSlotDateTime = DateTime::createFromFormat('Y:m:d H:i', $currentTimeSlot);
              $now = new DateTime();

              $isPast = $currentTimeSlotDateTime < $now;

              $sqlSelect = $connection->prepare("SELECT * FROM reservation WHERE StartMoment = ? and SiteId=?");
              $sqlSelect->bind_param("si", $currentTimeSlot, $_SESSION["currentSite"]);
              $sqlSelect->execute();
              $result = $sqlSelect->get_result();
              if ($result->num_rows == 0) {
                $isReserved = false;
              } else {
                $isReserved = true;
                $row = $result->fetch_assoc();
                $userID = $row['Reserved_by_userID'];
                $userInfo = $connection->prepare('select * from users where UserID = ?');
                $userInfo->bind_param('i', $userID);
                $userInfo->execute();
                $resultOfUserInfo = $userInfo->get_result();
                while ($row = $resultOfUserInfo->fetch_assoc()) {
                  $Last_name = strtoupper($row['Last_name']);
                  $First_name = ucfirst($row['First_name']);
                  $username = $row['Username'];
                }
              }

              $cellColor = $isReserved ? ($_SESSION["username"] == $username) ? '#f7c1c6' : '#f8d7da' : ($isPast ? '#e0e0e0' : '#d4edda');

              ?>

              <td style="background-color: <?= $cellColor ?>;">
                <!-- <p style="line-height: 1.2;">First line<br>Second line</p> -->
                <?php if ($isReserved) { ?>
                  <?= $t['reserved'] ?>

                  <!-- <?= ($_SESSION["Admin"]) ? "<br> $t[by] <br> $Last_name $First_name" : " " ?>
                  <?= ($_SESSION["username"] == $username && !$_SESSION['Admin']) ? "<br> $t[by] <br> $t[you] " : " " ?>
                  <?= ($_SESSION["Admin"] &&  strtolower($username) == 'admin') ? "<br> $t[by] <br> admin " : " " ?> -->
                  <?php
                  if ($_SESSION["username"] == $username && !$_SESSION['Admin']) {
                    echo "<br> $t[by] <br> $t[you] ";
                  } else if ($_SESSION["Admin"] &&  strtolower($username) == 'admin') {
                    echo "<br> $t[by] <br> admin ";
                  } else if ($_SESSION['Admin'] || $_SESSION["SecurityAccess"]) {
                    echo "<br> $t[by] <br> $Last_name $First_name";
                  }

                  ?>


                <?php } else { ?>

                  <form method="POST" class="formBtn" style="margin:0;" onsubmit="return confirm('<?= $t['confirm_reserve_time'] ?>');">

                    <input type="hidden" name="day" value="<?= $fullWeekDates[$index] ?>">
                    <input type="hidden" name="time" value="<?= $time ?>">
                    <button type="submit" class="reserveBtn" name="reservationBtn" <?= $disableForSecurity ?> <?= ($isPast ? 'disabled style="background-color: #ccc; cursor: not-allowed;"' : '') ?>>
                      <?= $isPast ? $t['past'] : $t['available'] ?>
                    </button>
                  </form>

                <?php } ?>

              </td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>

    </table>
  </div>



  <script src="../script.js"></script>

</body>

</html>
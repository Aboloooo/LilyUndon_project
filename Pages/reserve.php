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
  <script src="../script.js"></script>
</head>

<body>
  <?= NavBar('reserve') ?>

  <body>

    <h1><?= $t['kitchen_reservation_calendar'] ?></h1>

    <?php
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
    if (isset($_GET['prevWeek']) && $minOffset = -1) {
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
    $weekDates = [];
    $weekDatesAnotherFormat = [];
    for ($i = 0; $i < 7; $i++) {
      $weekDates[] = date('d/m', strtotime("+$i day", $startOfWeek));
      $weekDatesAnotherFormat[] = date('Y:m:d', strtotime("+$i day", $startOfWeek));
    }



    if (isset($_POST['reservationBtn'])) {
      $canBookTimeSlot = false;

      if ($_SESSION['username'] !== "Unknown") {
        $userIDStmt = $connection->prepare("SELECT UserID FROM users WHERE Username = ?");
        $userIDStmt->bind_param('s', $_SESSION['username']);
        $userIDStmt->execute();
        $result = $userIDStmt->get_result();
        $row = $result->fetch_assoc();

        // if user is not login user must be redirected to login page

        $userId = $row['UserID'];
        $date = $_POST['day'];
        $time = $_POST['time'];




        //here we will check if this user has reserved the kitchen more than 4 times
        $reservationCheckStatement = $connection->prepare('select count(*) as cnt from reservation where Reserved_by_userID = ? and StartMoment <= ? and StartMoment>=?');

        $reservationCheckStatement->bind_param('iss', $userId, $lastDayOfWeek, $startOfWeekGoodFormat);
        $reservationCheckStatement->execute();
        $resultOfReservationCheck = $reservationCheckStatement->get_result();
        $BookedTimeSlotsNumber = $resultOfReservationCheck->fetch_assoc();

        if ($BookedTimeSlotsNumber) {
          if ($BookedTimeSlotsNumber['cnt'] < 3) {
            $canBookTimeSlot = true;
          }
        }

        if ($canBookTimeSlot) {

          // user can continue booking if reservation hasnt been done more than 4 times 
          //  $currentTimeSlot = date('Y-m-d', strtotime($date)) . ' ' . substr($time, 0, 5);
          //print($startOfWeekGoodFormat);
          $curDateToday =  date("Y-m", $startOfWeek) . "-" . substr($date, 0, 2);
          // print($curDateToday);
          $reservationDate = $curDateToday;
          $currentTimeSlot = $curDateToday . ' ' . substr($time, 0, 5);
          //print($currentTimeSlot);

          /* check if user is booking in the same day */
          $checkSameDayStmt  = $connection->prepare('select count(*) as count from reservation where Reserved_by_userID = ? and DATE(StartMoment) = ?');
          $checkSameDayStmt->bind_param('is', $userId, $reservationDate);
          $checkSameDayStmt->execute();
          $resultOfCheckSameDayStmt  = $checkSameDayStmt->get_result();
          $sameDayCount = $resultOfCheckSameDayStmt->fetch_assoc()['count'];
          if ($sameDayCount > 0) {
            echo "<script>alert('" . $t['already_have_reservation_on_day'] . "')</script>";
          } else {
            $sqlReservationInsert = $connection->prepare('insert into reservation(Reserved_by_userID,StartMoment) values (?,?) ');
            $sqlReservationInsert->bind_param('is', $userId, $currentTimeSlot);
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

    <div class="reservation_table_content">

      <div class="calMovement">
        <form action="" method="get" class="calendar-nav">
          <button id="prevWeek" name="prevWeek">◀</button>
          <span id="monthYear"><?= $currentMonthYear ?></span>
          <button id="nextWeek" name="nextWeek">▶</button>
          <input type="hidden" name="week" value="<?= $weekOffset ?>">
        </form>
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
              <th <?= $BackHeadingColor ?>><?= $day ?><br><small>(<?= $weekDates[$index] ?>)</small></th>
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

                $sqlSelect = $connection->prepare("SELECT * FROM reservation WHERE StartMoment = ?");
                $sqlSelect->bind_param("s", $currentTimeSlot);
                $sqlSelect->execute();
                $result = $sqlSelect->get_result();
                if ($result->num_rows == 0) {
                  $isReserved = false;
                } else {
                  $isReserved = true;
                }
                $cellColor = $isReserved ? '#f8d7da' : ($isPast ? '#e0e0e0' : '#d4edda');

                ?>

                <td style="background-color: <?= $cellColor ?>;">

                  <?php if ($isReserved) { ?>
                    <?= $t['reserved'] ?>
                  <?php } else { ?>
                    <form method="POST" style="margin:0;" onsubmit="return confirm('<?= $t['confirm_reserve_time'] ?>');">
                      <input type="hidden" name="day" value="<?= $weekDates[$index] ?>">
                      <input type="hidden" name="time" value="<?= $time ?>">
                      <button type="submit" class="reserveBtn" name="reservationBtn" <?= ($isPast ? 'disabled style="background-color: #ccc; cursor: not-allowed;"' : '') ?>>
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

  </body>

</html>
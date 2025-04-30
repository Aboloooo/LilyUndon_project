<!-- <?php
      session_start();
      include_once("../Library/MyLibrary.php");
      ?> -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kitchen Reservation Calendar</title>
  <link rel="stylesheet" href="../style.css? <?= time(); ?>">
  <script src="../script.js"></script>
</head>

<body>
  <?= NavBar('reserve') ?>

  <body>

    <h1>Kitchen Reservation Calendar</h1>

    <?php
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $times = [];

    $currentMonth = date('m');
    $currentYear = date('Y');
    $TotalNumberOfDaysInTheMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

    for ($h = 8; $h <= 20; $h += 2) {
      $times[] = sprintf('%02d:00 - %02d:00', $h, $h + 2);
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

    $currentMonthYear = date('Y-m F', $startOfWeek);
    $currentMonthYearName = date('F', $startOfWeek);



    if (isset($_POST['reservationBtn'])) {
      if (isset($_SESSION['username'])) {
        $userIDStmt = $connection->prepare("SELECT UserID FROM users WHERE Username = ?");
        $userIDStmt->bind_param('s', $_SESSION['username']);
        $userIDStmt->execute();
        $result = $userIDStmt->get_result();
        $row = $result->fetch_assoc();

        $userId = $row['UserID'];
        $date = $_POST['day'];
        $time = $_POST['time'];

        $currentTimeSlot = date('Y-m-d', strtotime($date)) . ' ' . substr($time, 0, 5);


        $sqlReservationInsert = $connection->prepare('insert into reservation(Reserved_by_userID,StartMoment) values (?,?) ');

        $sqlReservationInsert->bind_param('is', $userId, $currentTimeSlot);
        if ($sqlReservationInsert->execute()) {
          echo "<script>alert('Reservation done successfully!')</script>";
        } else {
          echo "<script>alert('Error']')</script>";
        }
      } else {
        echo "<script>alert('Plase login first!')</script>";
      }
    }


    // get dates for each day
    $weekDates = [];
    $weekDatesAnotherFormat = [];
    for ($i = 0; $i < 7; $i++) {
      $weekDates[] = date('d/m', strtotime("+$i day", $startOfWeek));
      $weekDatesAnotherFormat[] = date('Y:m:d', strtotime("+$i day", $startOfWeek));
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
            <th>Time</th>

            <?php
            $today = date('d/m');
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
                    Reserved
                  <?php } else { ?>
                    <form method="POST" style="margin:0;">
                      <input type="hidden" name="day" value="<?= $day ?>">
                      <input type="hidden" name="time" value="<?= $time ?>">
                      <button type="submit" class="reserveBtn" name="reservationBtn" <?= ($isPast ? 'disabled style="background-color: #ccc; cursor: not-allowed;"' : '') ?>>
                        <?= $isPast ? 'Past' : 'Available' ?>
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
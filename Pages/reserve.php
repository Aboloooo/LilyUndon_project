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
</head>

<body>
  <?= NavBar('reserve') ?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kitchen Reservation Calendar</title>
    <link rel="stylesheet" href="calendar.css" />
  </head>

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

    // Example reserved slots
    $reservedSlots = [
      'Monday' => ['10:00 - 12:00'],
      'Wednesday' => ['14:00 - 16:00'],
      'Saturday' => ['18:00 - 20:00']
    ];

    $minOffset = 0;
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

    $startOfWeek = strtotime("+$weekOffset week", $lastMonday);

    $currentMonthYear = date('Y-m F', $startOfWeek);
    $currentMonthYearName = date('F', $startOfWeek);

    // get dates for each day
    $weekDates = [];
    for ($i = 0; $i < 7; $i++) {
      $weekDates[] = date('d/m', strtotime("+$i day", $startOfWeek));
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
            <?php foreach ($days as $index => $day): ?>
              <th><?= $day ?><br><small>(<?= $weekDates[$index] ?>)</small></th>
            <?php endforeach; ?>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($times as $time): ?>
            <tr>
              <td><strong><?= $time ?></strong></td>
              <?php foreach ($days as $day): ?>
                <?php
                $isReserved = isset($reservedSlots[$day]) && in_array($time, $reservedSlots[$day]);
                ?>
                <td style="background-color: <?= $isReserved ? '#f8d7da' : '#d4edda'; ?>;">
                  <?php if ($isReserved): ?>
                    Reserved
                  <?php else: ?>
                    <form method="POST" action="reserve_slot.php" style="margin:0;">
                      <input type="hidden" name="day" value="<?= $day ?>">
                      <input type="hidden" name="time" value="<?= $time ?>">
                      <button type="submit" class="reserveBtn">Available</button>
                    </form>
                  <?php endif; ?>
                </td>
              <?php endforeach; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>

      </table>
    </div>

  </body>



  </html>



</body>

</html>
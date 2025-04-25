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

    /* Finding last Monday date */
    $lastMonday = date('d', strtotime('monday this week')); //- 7 days * NumberOfWeeks
    $lastMondayDate = $lastMonday - 1; // -1, will be fixed in the following function

    $currentWeekStart = date('Y-m-d', $lastMonday);
    $currentWeekEnd = date('Y-m-d', strtotime('sunday', $lastMonday));

    $weekOffset = isset($_GET['week']) ? intval($_GET['week']) : 0;
    if (isset($_GET['prevWeek'])) {
      $weekOffset--;
    } elseif (isset($_GET['nextWeek'])) {
      $weekOffset++;
    }

    $nextMonday = strtotime("+1 week", $lastMonday);
    $prevMonday = strtotime("-1 week", $lastMonday);

    /* function of movement calender btns  */
    /* previous week btn */

    function dateCalculator()
    {
      global $lastMondayDate;
      ($lastMondayDate += 1) % 7;
      return $lastMondayDate;
    }


    ?>
    <div class="reservation_table_content">
      <table class="reservationTable" border="1" cellspacing="0" cellpadding="10">

        <thead>

          <div class="calMovement">
            <form action="" method="get">
              <button id="prevWeek" name="prevWeek">◀</button>
              <span id="monthYear"> this needs to be changed according to shown dates <?= date('Y-m') ?></span>
              <button id="nextWeek" name="nextWeek">▶</button>
            </form>
          </div>
          <tr>
            <th>Time</th>

            <?php foreach ($days as $day): ?>
              <th><?= $day,
                  dateCalculator(); ?></th>
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
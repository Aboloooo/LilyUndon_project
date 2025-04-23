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
$TotalNumberOfDaysInTheMonth = cal_days_in_month(CAL_GREGORIAN,$currentMonth,$currentYear);

for ($h = 8; $h <= 20; $h += 2) {
  $times[] = sprintf('%02d:00 - %02d:00', $h, $h + 2);
}

/* finding last Monday date */
date_default_timezone_set('Europe/Paris'); // Adjust to your region

$today = new DateTime(); //- 7 days * NumberOfWeeks


if ($today->format('N') == 1) { // 1 = Monday
  $lastMonday = $today;
} else {
  $lastMonday = clone $today;
  $lastMonday->modify('last monday');
}
$lastMondayDate = $lastMonday->format("d");
$weekDaysDate += $lastMondayDate+1; 

// Example reserved slots
$reservedSlots = [
  'Monday' => ['10:00 - 12:00'],
  'Wednesday' => ['14:00 - 16:00'],
  'Saturday' => ['18:00 - 20:00']
];
?>
 
<table class="reservationTable" border="1" cellspacing="0" cellpadding="10" >

  <thead>
  <div class="calMovement">
        <button id="prevMonth">◀</button>
        <span id="monthYear"></span>
        <button id="nextMonth">▶</button>
      </div>
    <tr>
      <th>Time</th>
      <?php foreach ($days as $day): ?>
        <th><?= $day, $weekDaysDate?></th>
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



  </body>

  </html>



</body>

</html>
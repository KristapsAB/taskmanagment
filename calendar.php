<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
 
</style>
<body>
    <div class="container">
        <div class="box-cal">
            <div class="boxHeading-dash">CALENDAR</div>
            <select class="select">
  <option value="january">JANUARY</option>
  <option value="february">FEBRUARY</option>
  <option value="march">MARCH</option>
  <option value="april">APRIL</option>
  <option value="may">MAY</option>
  <option value="june">JUNE</option>
  <option value="july">JULY</option>
  <option value="august">AUGUST</option>
  <option value="september">SEPTEMBER</option>
  <option value="october">OCTOBER</option>
  <option value="november">NOVEMBER</option>
  <option value="december">DECEMBER</option>
</select>
            <div class="innerBox-cal">


               

    
            <?php
    include 'allCalendar.html'; 
    ?>
        





            </div>
        </div>

      


    </div>
</body>
</html>
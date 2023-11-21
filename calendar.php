<?php
session_start();
require_once('TaskManager.php');
require_once('db_connection.php'); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$userId = $_SESSION['user_id']; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'sidebar.php'; ?>

    <div class="container">
        <div class="box-cal">
            <div class="boxHeading-dash">CALENDAR</div>
            <form id="monthForm">
                <select class="select" id="selectedMonth" onchange="changeMonth()">
                    <?php
                    $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                    foreach ($months as $month) {
                        echo "<option value=\"$month\">$month</option>";
                    }
                    ?>
                </select>
            </form>
            <div class="innerBox-cal">
                <?php
                ?>
            </div>
        </div>
    </div>

    <script>
        function changeMonth() {
            const selectedMonth = document.getElementById("selectedMonth").value;

            fetch(`updateMonth.php?selectedMonth=${selectedMonth}`)
                .then(response => response.text())
                .then(data => {
                    document.querySelector(".innerBox-cal").innerHTML = data;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>

</body>
</html>

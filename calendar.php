<?php
session_start();
require_once('TaskManager.php');
require_once('db_connection.php'); // Include the database connection file
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
var_dump($_SESSION);

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if user_id is not set in the session
    header("Location: login.html");
    exit;
}

$userId = $_SESSION['user_id']; // Get user_id from session

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
    <div class="container">
        <div class="box-cal">
            <div class="boxHeading-dash">CALENDAR</div>
            <form id="monthForm">
                <select class="select" id="selectedMonth" onchange="changeMonth()">
                    <?php
                    // Loop through all months
                    $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                    foreach ($months as $month) {
                        echo "<option value=\"$month\">$month</option>";
                    }
                    ?>
                </select>
            </form>
            <div class="innerBox-cal">
                <?php
                // Your PHP code for displaying tasks by month goes here
                // Use $taskManager to fetch and display tasks
                ?>
            </div>
        </div>
    </div>

    <script>
        function changeMonth() {
            const selectedMonth = document.getElementById("selectedMonth").value;

            // Fetch tasks for the selected month
            fetch(`updateMonth.php?selectedMonth=${selectedMonth}`)
                .then(response => response.text())
                .then(data => {
                    // Update the innerBox with the fetched tasks
                    document.querySelector(".innerBox-cal").innerHTML = data;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>

</body>
</html>

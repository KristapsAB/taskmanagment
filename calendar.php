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
                    // Loop through months
                    $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                    foreach ($months as $month) {
                        echo "<option value=\"$month\">$month</option>";
                    }
                    ?>
                </select>
            </form>
            <div class="innerBox-cal">
                <?php
                require_once('TaskManager.php');

                // Function to convert month name to number
                function monthToNumber($month) {
                    global $months;
                    return array_search($month, $months) + 1;
                }

                $taskManager = new TaskManager();

                // Check if a specific month is selected, default to the current month
                $selectedMonth = isset($_GET['selectedMonth']) ? $_GET['selectedMonth'] : date('F');

                // Fetch tasks for the current month
                $tasks = $taskManager->getTasksByMonth($selectedMonth);

                // Get the number of days in the selected month (you may need to adjust the year dynamically)
                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, monthToNumber($selectedMonth), date('Y'));

                // Loop through days in the month
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    echo '<div class="innerBar-dash">';
                    echo "<div class=\"innerHeading-dash\">$day</div>";

                    // Fetch tasks for the current day
                    $tasksByDay = array_filter($tasks, function ($task) use ($day) {
                        return date('j', strtotime($task['due_date'])) == $day;
                    });

                    echo '<div class="taskBar">';
                    // Check if there are tasks for the current day
                    if (!empty($tasksByDay)) {
                        foreach ($tasksByDay as $task) {
                            echo '<div class="taskBox">';
                            echo "<div class=\"taskHeading\">{$task['name']}</div>";
                            echo '<div class="taskBotBox-left-cal">';
                            echo "<div class=\"textBox\">{$task['description']}</div>";
                            echo '</div>';
                            echo '<div class="taskBotBox-right-cal">';
                            echo "<div class=\"textBox2\">DUE: <br>{$task['due_date']}</div>";
                            echo '</div>';
                            echo "<div class=\"taskButtonBox-cal\">COMMENTS: {$task['comments']}</div>";
                            echo '</div>';
                        }
                    } else {
                        // Display a message if no tasks are found
                        echo '<div class="emptyText">NO TASKS FOUND</div>';
                    }
                    echo '</div>';

                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Add this script in the <head> section of your HTML -->
    <script>
        function changeMonth() {
            const selectedMonth = document.getElementById("selectedMonth").value;

            // Fetch tasks for the selected month
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

<?php
require_once('TaskManager.php');

// Start the session
session_start();

// Assuming you have the user ID available in your session
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Define the months array
$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

// Get the selected month from the query parameters or use the current month
$selectedMonth = isset($_GET['selectedMonth']) ? $_GET['selectedMonth'] : date('F');

// Create an instance of the TaskManager class
$taskManager = new TaskManager();

// Initialize an array to store the HTML content
$htmlContent = [];

// Loop through the days of the selected month
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, array_search($selectedMonth, $months) + 1, date('Y'));
for ($day = 1; $day <= $daysInMonth; $day++) {
    // Fetch tasks for the current day
    $tasks = $taskManager->getTasksByDay($userId, $selectedMonth, $day);

    $htmlContent[] = '<div class="innerBar-dash">';
    $htmlContent[] = "<div class=\"innerHeading-dash\">$day</div>";
    $htmlContent[] = '<div class="taskBar">';

    // Check if tasks exist for the current day
    if (!empty($tasks)) {
        foreach ($tasks as $task) {
            $htmlContent[] = '<div class="taskBox">';
            $htmlContent[] = "<div class=\"taskHeading\">{$task['name']}</div>";
            $htmlContent[] = '<div class="taskBotBox-left">';
            $htmlContent[] = "<div class=\"textBox\">{$task['description']}</div>";
            $htmlContent[] = '</div>';
            $htmlContent[] = '<div class="taskBotBox-right">';
            $htmlContent[] = "<div class=\"textBox2\">{$task['due_date']}</div>";
            $htmlContent[] = '</div>';
            $htmlContent[] = "<div class=\"taskButtonBox-cal\">COMMENTS: {$task['comments']}</div>";
            $htmlContent[] = '</div>';
        }
    } else {
        $htmlContent[] = '<div class="emptyText">NO TASKS FOUND</div>';
    }

    $htmlContent[] = '</div>';
    $htmlContent[] = '</div>';
}

// Output the HTML content
echo implode('', $htmlContent);
?>

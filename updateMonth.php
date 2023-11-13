<?php
require_once('TaskManager.php');

// Define the array of months
$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

// Check if a specific month is selected, default to the current month
$selectedMonth = isset($_GET['selectedMonth']) ? $_GET['selectedMonth'] : date('F');

$taskManager = new TaskManager();
$tasks = $taskManager->getAllTasks();

// Structure tasks by date
$tasksByDate = [];

foreach ($tasks as $task) {
    $dueDateMonth = date('F', strtotime($task['due_date']));
    if ($dueDateMonth === $selectedMonth) {
        $dueDate = date('j', strtotime($task['due_date']));
        $tasksByDate[$dueDate][] = $task;
    }
}

// Get the number of days in the selected month
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, array_search($selectedMonth, $months) + 1, date('Y'));

// Build the HTML content
$htmlContent = '';
for ($day = 1; $day <= $daysInMonth; $day++) {
    $htmlContent .= '<div class="innerBar-dash">';
    $htmlContent .= "<div class=\"innerHeading-dash\">$day</div>";
    $htmlContent .= '<div class="taskBar">';
    
    if (isset($tasksByDate[$day])) {
        foreach ($tasksByDate[$day] as $task) {
            $htmlContent .= '<div class="taskBox">';
            $htmlContent .= "<div class=\"taskHeading\">{$task['name']}</div>";
            $htmlContent .= '<div class="taskBotBox-left">';
            $htmlContent .= "<div class=\"textBox\">{$task['description']}</div>";
            $htmlContent .= '</div>';
            $htmlContent .= '<div class="taskBotBox-right">';
            $htmlContent .= "<div class=\"textBox2\">{$task['due_date']}</div>";
            $htmlContent .= '</div>';
            $htmlContent .= '</div>';
        }
    } else {
        $htmlContent .= '<div class="emptyText">NO TASKS FOUND</div>';
    }
    
    $htmlContent .= '</div>';
    $htmlContent .= '</div>';
}

echo $htmlContent;
?>

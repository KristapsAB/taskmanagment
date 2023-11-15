<?php
require_once('TaskManager.php');

$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

$selectedMonth = isset($_GET['selectedMonth']) ? $_GET['selectedMonth'] : date('F');

$daysInMonth = cal_days_in_month(CAL_GREGORIAN, array_search($selectedMonth, $months) + 1, date('Y'));

$htmlContent = '';
for ($day = 1; $day <= $daysInMonth; $day++) {
    $htmlContent .= '<div class="innerBar-dash">';
    $htmlContent .= "<div class=\"innerHeading-dash\">$day</div>";
    $htmlContent .= '<div class="taskBar">';
    
    // Fetch tasks for the current day
    $taskManager = new TaskManager();
    $tasks = $taskManager->getTasksByDay($selectedMonth, $day);

    if (!empty($tasks)) {
        foreach ($tasks as $task) {
            $htmlContent .= '<div class="taskBox">';
            $htmlContent .= "<div class=\"taskHeading\">{$task['name']}</div>";
            $htmlContent .= '<div class="taskBotBox-left">';
            $htmlContent .= "<div class=\"textBox\">{$task['description']}</div>";
            $htmlContent .= '</div>';
            $htmlContent .= '<div class="taskBotBox-right">';
            $htmlContent .= "<div class=\"textBox2\">{$task['due_date']}</div>";
            $htmlContent .= '</div>';
            $htmlContent .= "<div class=\"taskButtonBox-cal\">COMMENTS: {$task['comments']}</div>";
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

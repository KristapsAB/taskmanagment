<?php
require_once('TaskManager.php');

session_start();

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

$selectedMonth = isset($_GET['selectedMonth']) ? $_GET['selectedMonth'] : date('F');

$taskManager = new TaskManager();

$htmlContent = [];

$daysInMonth = cal_days_in_month(CAL_GREGORIAN, array_search($selectedMonth, $months) + 1, date('Y'));
for ($day = 1; $day <= $daysInMonth; $day++) {
    $tasks = $taskManager->getTasksByDay($userId, $selectedMonth, $day);

    $htmlContent[] = '<div class="innerBar-dash">';
    $htmlContent[] = "<div class=\"innerHeading-dash\">$day</div>";
    $htmlContent[] = '<div class="taskBar">';

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

echo implode('', $htmlContent);
?>

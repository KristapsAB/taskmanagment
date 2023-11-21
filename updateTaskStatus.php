<?php
require_once('TaskManager.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $taskId = $_POST['task_id'];
    $status = $_POST['new_status'];

    $taskManager = new TaskManager();
    $result = $taskManager->updateTaskStatus($taskId, $status);

    if ($result === true) {
        echo "Task status updated successfully";
    } else {
        echo "Error updating task status: " . $result;
    }
} else {
    echo "Error updating task status: Invalid request method";
}
?>

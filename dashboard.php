<?php
require_once('TaskManager.php');

$taskManager = new TaskManager();
$tasks = $taskManager->getAllTasks();

// Check if the form is submitted to update the task status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["task_id"]) && isset($_POST["new_status"])) {
    $task_id = $_POST["task_id"];
    $new_status = $_POST["new_status"];
    $taskManager->updateTaskStatus($task_id, $new_status);
    exit; // Avoid rendering the entire page when handling the update request
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="box-dash">
            <div class="boxHeading-dash">PROJECT NAME</div>
            <div class="innerBox-dash">
                <!-- Assuming there are three sections: TO DO, IN PROGRESS, and DONE -->
                <?php foreach (['TO DO', 'IN PROGRESS', 'DONE'] as $status): ?>
                    <div class="innerBar-dash">
                        <div class="innerHeading-dash"><?php echo $status; ?></div>
                        <div class="taskBar">
                            <?php
                            // Filter tasks based on their status
                            $tasksByStatus = array_filter($tasks, function ($t) use ($status) {
                                return (isset($t['status']) && $t['status'] === $status) || !isset($t['status']);
                            });

                            // Display tasks for the current status
                            foreach ($tasksByStatus as $taskByStatus):
                            ?>
<div class="taskBox-dash" id="task_<?php echo htmlspecialchars($taskByStatus['name']); ?>">
                                    <div class="taskHeading"><?php echo $taskByStatus['name']; ?></div>
                                    <div class="taskBotBox-left">
                                        <div class="textBox"><?php echo $taskByStatus['description']; ?></div>
                                    </div>
                                    <div class="taskBotBox-right">
                                        <div class="textBox2">DUE: <br> <?php echo $taskByStatus['due_date']; ?></div>
                                    </div>
                                    <div class="taskButtonBox">
                                    <?php
switch ($status) {
    case 'TO DO':
        $taskId = isset($taskByStatus['task_id']) ? $taskByStatus['task_id'] : '';
        ?>
        <button class="beginButton" onclick="beginTask('<?php echo $taskId; ?>')">BEGIN</button>
        <?php
        break;
    case 'IN PROGRESS':
        $taskId = isset($taskByStatus['task_id']) ? $taskByStatus['task_id'] : '';
        ?>
        <!-- Add the "MARK AS DONE" button for tasks in progress -->
        <button class="markAsDoneButton" onclick="markAsDone('<?php echo $taskId; ?>')">MARK AS DONE</button>
        <?php
        // You can add more buttons or custom content for tasks in progress if needed
        break;
        case 'DONE':
            $taskId = isset($taskByStatus['task_id']) ? $taskByStatus['task_id'] : '';
            ?>
            <!-- Change the button text to "DELETE" -->
            <button class="markAsDoneButton" onclick="deleteTask('<?php echo $taskId; ?>')">DELETE</button>
            <?php
            break;
}
?>
    
</div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
<div class="botButtonBox">
        <button onclick="location.href='createTask.php'" class="botButton">CREATE</button>
        </div>
    </div>

    <script>
function beginTask(taskId) {
    const taskIdString = taskId.toString();

    const formData = new FormData();
    formData.append('task_id', taskIdString);
    formData.append('new_status', 'IN PROGRESS');

    // Print the task ID to the console
    console.log('Task ID:', taskIdString);

    fetch('updateTaskStatus.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        // Refresh the page after updating the task status
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function markAsDone(taskId) {
    const taskIdString = taskId.toString();

    const formData = new FormData();
    formData.append('task_id', taskIdString);
    formData.append('new_status', 'DONE');

    fetch('updateTaskStatus.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        // Refresh the page after updating the task status
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function deleteTask(taskId) {
    const taskIdString = taskId.toString();

    const formData = new FormData();
    formData.append('task_id', taskIdString);
    formData.append('new_status', 'DELETED'); // Use a new status like 'DELETED' to represent deleted tasks

    fetch('updateTaskStatus.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        // Refresh the page after updating the task status
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

</script>



</body>
</html>

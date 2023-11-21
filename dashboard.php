<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('TaskManager.php');

$taskManager = new TaskManager();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $userTasks = $taskManager->getTasksByUserId($userId);

    $tasksByStatus = [
        'TO DO' => array_filter($userTasks, function ($t) {
            return (!isset($t['status']) || $t['status'] === 'TO DO');
        }),
        'IN PROGRESS' => array_filter($userTasks, function ($t) {
            return (isset($t['status']) && $t['status'] === 'IN PROGRESS');
        }),
        'DONE' => array_filter($userTasks, function ($t) {
            return (isset($t['status']) && $t['status'] === 'DONE');
        }),
    ];
} else {
    $tasksByStatus = [
        'TO DO' => [],
        'IN PROGRESS' => [],
        'DONE' => [],
    ];
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
<?php include 'sidebar.php'; ?>
    <div class="container">
        <div class="box-dash">
            <div class="boxHeading-dash"><?php echo isset($tasksByStatus['TO DO'][0]['name']) ? $tasksByStatus['TO DO'][0]['name'] : ''; ?></div>
            <div class="innerBox-dash">
                <?php foreach ($tasksByStatus as $status => $tasks): ?>
                    <div class="innerBar-dash">
                        <div class="innerHeading-dash"><?php echo $status; ?></div>
                        <div class="taskBar">
                            <?php
                            foreach ($tasks as $task):
                            ?>
                                <div class="taskBox" id="task_<?php echo htmlspecialchars($task['name']); ?>">
                                    <div class="taskHeading"><?php echo $task['name']; ?></div>
                                    <div class="taskBotBox-left">
                                        <div class="textBox"><?php echo $task['description']; ?></div>
                                    </div>
                                    <div class="taskBotBox-right">
                                        <div class="textBox2"><?php echo $task['due_date']; ?></div>
                                    </div>
                                    <div class="taskButtonBox">
                                        <?php
                                        switch ($status) {
                                            case 'TO DO':
                                                $taskId = isset($task['task_id']) ? $task['task_id'] : '';
                                                ?>
                                                <button class="beginButton" onclick="beginTask('<?php echo $taskId; ?>')">BEGIN</button>
                                                <?php
                                                break;
                                            case 'IN PROGRESS':
                                                $taskId = isset($task['task_id']) ? $task['task_id'] : '';
                                                ?>
                                                <button class="markAsDoneButton" onclick="markAsDone('<?php echo $taskId; ?>')">MARK AS DONE</button>
                                                <?php
                                                break;
                                            case 'DONE':
                                                $taskId = isset($task['task_id']) ? $task['task_id'] : '';
                                                ?>
                                                <button class="markAsDoneButton" >FINISHED</button>
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

        <button onclick="location.href='createTask.php'" class="botButton">CREATE</button>
    </div>

    <script>
function beginTask(taskId) {
    const taskIdString = taskId.toString();

    const formData = new FormData();
    formData.append('task_id', taskIdString);
    formData.append('new_status', 'IN PROGRESS');

    fetch('updateTaskStatus.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
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
</script>

</body>
</html>

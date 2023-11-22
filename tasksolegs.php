<?php
require_once('TaskManager.php');

$taskManager = new TaskManager();

$tasks = $taskManager->getAllTasks( );

if (array_key_exists("searchBtn", $_POST)) {
    $searchInput = $_POST["searchInput"];
    if (!empty($searchInput)) {
        $temp = [ ];
        foreach ($tasks as $task) {
            $name = strtoupper($task['name']);
            $desc = strtoupper($task['description']);

            similar_text($name, strtoupper($_POST["searchInput"]), $percName);
            similar_text($desc, strtoupper($_POST["searchInput"]), $percDesc);
            if ($percName > 50 || $percDesc > 50) { // cik similar jaabuut
                $temp []= $task;
                continue;
            }

            if (str_contains($name, $searchInput) || str_contains($desc, $searchInput)) {
                $temp []= $task;
            }
        }
        $tasks = $temp;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome!</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<style>
    
</style>
<body>
    <div class="container">
        <div class="tasks-page">
            <form class="area-searchbar" method='post'>
                <div class="search-bar-container">
                    <button class="invis-btn" name="searchBtn">
                        <i class="fa fa-search"></i>
                    </button>
                    <input type="text" class="search-input" name="searchInput" />
                </div>
            </form>  
            <section class="area-tasks">
                <?php

                if (!empty($tasks)) {
                    foreach ($tasks as $task) {
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

                ?>
            </section>
        </div>
    </div>

    <script>
        function redirectToDashboard() {
            window.location.href = 'dashboard.php';
        }
    </script>
</body>
</html>

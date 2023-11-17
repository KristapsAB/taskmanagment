<?php
require_once('ProjectManager.php');

$projectManager = new ProjectManager();
$projects = $projectManager->getAllProjects();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome!</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    
</style>
<body>
<?php
include('menu.html');

?>
    <div class="container">
        <div class="box">
            <div class="boxHeading">WELCOME BACK, LOHS</div>
            <div class="innerBox">
                <?php
                foreach ($projects as $project) {
                    echo '<div class="innerBar" onclick="redirectToDashboard()"> ' . htmlspecialchars($project['project_name']) . ' </div>';
                }
                ?>
            </div>
            <button class="buttonBot" onclick="location.href='createProject.php'">CREATE NEW</button>
        </div>
    </div>

    <script>
        function redirectToDashboard() {
            window.location.href = 'dashboard.php';
        }
    </script>
</body>
</html>

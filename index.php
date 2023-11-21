<?php
session_start();
require_once('ProjectManager.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$projectManager = new ProjectManager();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $projects = $projectManager->getProjectsByUserId($userId);
} else {
    $projects = [];
}
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
<?php include 'sidebar.php'; ?>
    <div class="container">
        <div class="box">
            <div class="boxHeading">Welcome Back <?php echo $_SESSION['username']; ?></div>
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
        console.log('Redirecting to dashboard...');
        window.location.href = 'dashboard.php';
    }
    </script>
</body>
</html>

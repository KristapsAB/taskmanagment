<?php
require_once('ProjectManager.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$userId = $_SESSION['user_id']; 
$projectManager = new ProjectManager();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $projectName = $_POST["fname"];
    $projectDescription = $_POST["fdesc"];

    if (empty($projectName) || empty($projectDescription)) {
        echo "Please fill out all fields.";
    } else {
        $result = $projectManager->createProject($userId, $projectName, $projectDescription);
        echo $result;
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
    <script>
        function validateForm() {
            let x = document.forms["Mform"]["fname"].value;
            let y = document.forms["Mform"]["fdesc"].value;
            if (x === "" || y === "") {
                alert("Please fill out all fields.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<?php include 'sidebar.php'; ?>
    <div class="container">
        <div class="box">
            <div class="boxHeadingCR">CREATE A PROJECT</div>
            <div class="innerBoxCR">
                <form name="Mform" class="innerForm" method="post" onsubmit="return validateForm()">
                    <div class="formBox">
                        <div class="formHeading">PROJECT NAME</div>
                        <input name="fname" type="text" class="nameInput">
                    </div>
                    <div class="formBox">
                        <div class="formHeading">PROJECT DESCRIPTION</div>
                        <input name="fdesc" type="text" class="nameInputD">
                    </div>
                    <button class="buttonBotCR">CREATE</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

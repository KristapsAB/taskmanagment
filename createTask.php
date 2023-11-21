<?php
session_start();
require_once('TaskManager.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}



$userId = $_SESSION['user_id']; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $taskManager = new TaskManager();

    // Retrieve form data
    $name = $_POST["fname"];
    $description = $_POST["fdesc"];
    $dueDate = $_POST["fdate"];
    $comments = $_POST["fcomment"];
    $userId = $_SESSION['user_id']; 
    $result = $taskManager->createTask($userId, $name, $description, $dueDate, $comments);

    echo $result;
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Task!</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'sidebar.php'; ?>

    <div class="containerED">
        <div class="boxED">
            <div class="boxHeadingED">CREATE A TASK</div>
            <div class="innerBoxED">
                <form class="innerFormED" method="post" name="Mform" onsubmit="return submitForm()">
                    <div class="formBoxED">
                        <div class="formHeadingED">TASK NAME</div>
                        <input name="fname" type="text" class="nameInputED">
                    </div>
                    <div class="formBoxED">
                        <div class="formHeadingED">TASK DESCRIPTION</div>
                        <input name="fdesc" type="text" class="nameInputED">
                    </div>
                    <div class="formBoxED">
                        <div class="formHeadingED">DUE DATE</div>
                        <input name="fdate" type="date" class="nameInputED">
                    </div>
                    <div class="formBoxED">
                        <div class="formHeadingED">COMMENTS</div>
                        <input name="fcomment" type="text" class="nameInputED">
                    </div>
                </form>
            </div>
        </div>
        <div class="buttonBox">
            <button class="buttonBotED" onclick="submitForm()">CREATE</button>
        </div>
        
        <div id="statusMessage"></div>
    </div>
</body>
<script>
    function validateForm() {
        let x = document.forms["Mform"]["fname"].value;
        let y = document.forms["Mform"]["fdesc"].value;
        let z = document.forms["Mform"]["fdate"].value;
        if (x === "") {
            alert("The NAME input field must be filled out");
            return false;
        }
        if (y === "") {
            alert("The DESCRIPTION input field must be filled out");
            return false;
        }
        if (z === "") {
            alert("The DATE input field must be filled out");
            return false;
        }
        return true;
    }

    function submitForm() {
        if (validateForm()) {
            let form = document.forms["Mform"];
            let formData = new FormData(form);

            fetch("createTask.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById("statusMessage").innerHTML = data;

                form.reset();
            })
            .catch(error => {
                console.error("Error:", error);
            });
        }
        return false;
    }
</script>

</html>


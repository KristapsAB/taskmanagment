<?php
session_start();
require_once('TaskManager.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if user_id is not set in the session
    header("Location: login.html");
    exit;
}



$userId = $_SESSION['user_id']; // Get user_id from session


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Additional PHP code to handle form submission
    $taskManager = new TaskManager();

    // Retrieve form data
    $name = $_POST["fname"];
    $description = $_POST["fdesc"];
    $dueDate = $_POST["fdate"];
    $comments = $_POST["fcomment"];
    $userId = $_SESSION['user_id']; // Assuming you store user_id in session after login

    // Insert data into the "tasks" table
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
<style>
    /* Your styles go here */
</style>
<body>
    <div class="containerED">
        <div class="boxED">
            <div class="boxHeadingED">CREATE A TASK</div>
            <div class="innerBoxED">
                <form class="innerFormED" method="post" name="Mform" onsubmit="return submitForm()">
                    <div class="formBoxED">
                        <div class="formHeadingED">PROJECT NAME</div>
                        <input name="fname" type="text" class="nameInputED">
                    </div>
                    <div class="formBoxED">
                        <div class="formHeadingED">PROJECT DESCRIPTION</div>
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
        
        <!-- Add a status message element -->
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
                // Update a status message on the page
                document.getElementById("statusMessage").innerHTML = data;

                // Reset the form
                form.reset();
            })
            .catch(error => {
                console.error("Error:", error);
            });
        }
        return false; // Prevent form submission
    }
</script>

</html>


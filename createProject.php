<?php
require_once('ProjectManager.php');

// Additional PHP code to handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form inputs using JavaScript
    echo '<script>
        function validateForm() {
            let x = document.forms["Mform"]["fname"].value;
            let y = document.forms["Mform"]["fdesc"].value;
            if (x === "") {
                alert("The NAME input field must be filled out");
                return false;
            }
            if (y === "") {
                alert("The DESCRIPTION input field must be filled out");
                return false;
            }
            return true; // Add this line to indicate validation success
        }

        if (validateForm()) {
            let form = document.forms["Mform"];
            let formData = new FormData(form);

            fetch("createProject.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Display the result of the PHP operation
            })
            .catch(error => {
                console.error("Error:", error);
            });
        }
    </script>';

    $projectManager = new ProjectManager();

    // Retrieve form data
    $projectName = $_POST["fname"];
    $projectDescription = $_POST["fdesc"];

    // Insert data into the "projects" table
    $result = $projectManager->createProject($projectName, $projectDescription);

    echo $result;
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
    <div class="container">
        <div class="boxCR">
            <div class="boxHeadingCR">CREATE A PROJECT</div>
            <div class="innerBoxCR">
                <form name="Mform" class="innerForm" method="post" onsubmit="return validateForm()">
                    <div class="formBox">
                        <div class="formHeading">PROJECT NAME</div>
                        <input name="fname" type="text" class="nameInput">
                        <!-- <div id="project_error" class="errorC">hello there is an error</div>
                        -->
                    </div>
                    <div class="formBox">
                        <div class="formHeading">PROJECT DESCRIPTION</div>
                        <input name="fdesc" type="text" class="nameInputD">
                        <!-- <div id="description_error" class="errorC">hello there is an error</div>
                        -->
                    </div>
                </div>
                <button class="buttonBotCR">CREATE</button>
            </form>
        </div>
    </div>
</body>
<script>
    // function validateForm() {
    //     let x = document.forms["Mform"]["fname"].value;
    //     let y = document.forms["Mform"]["fdesc"].value;
    //     if (x === "") {
    //         alert("The NAME input field must be filled out");
    //         return false;
    //     }
    //     if (y === "") {
    //         alert("The DESCRIPTION input field must be filled out");
    //         return false;
    //     }
    // }
</script>
</html>

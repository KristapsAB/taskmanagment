<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.html");
    exit;
}

require_once 'db_connection.php';
require_once 'User.php';

// Initialize variables
$name = '';
$surname = '';
$email = '';
$password = '';
$username = '';

// Retrieve user information from the database
$user = new User($conn, $_SESSION['username'], null, null, null);

// Update the user information variables
$name = $user->getName();
$surname = $user->getSurname();
$email = $user->getEmail();
$password = $user->getPassword();

// Create a new User object with updated information
$user = new User($conn, $_SESSION['username'], $name, $surname, $email, $password);
$username = $_SESSION['username'];

// Handle profile updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $user->updateProfile($name, $surname, $email, $password);

    echo $result;
    exit;
}

// Handle profile image upload
// Handle profile image upload
// Handle profile image upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload'])) {
    if (isset($_POST['avatarLink'])) {
        $avatarLink = $_POST['avatarLink'];

        // Update the user's avatar URL
        $result = $user->updateAvatar($avatarLink);

        if ($result === true) {
            echo "Success: Avatar updated successfully!";
        } else {
            echo "Error: " . $result;
        }
    } else {
        echo "Error: Avatar link not provided.";
    }

    exit;
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profile.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Profile</title>
</head>
<body>
    <div class="profile">
        <div class="profile-center">
            <div class="profile-right">
            <div class="profile-image">
    <!-- Update the src attribute to use the user's avatar link -->
    <img src="<?php echo $user->getAvatarUrl(); ?>" alt="<?php echo $_SESSION['username']; ?>'s Avatar" class="avatar">
</div>
                <div class="name">
                    <h1><?php echo $_SESSION['username']; ?></h1>
                </div>

                <div class="side-options">
                    <div class="text" id="profileOption">
                        <p onclick="toggleProfile()">Profile</p>
                    </div>
                    <div class="text">
                        <p>Tasks uploaded</p>
                    </div>
                    <div class="text">
                        <p onclick="toggleUpload()">Upload Profile Image</p>
                    </div>
                    <div class="text">
                        <p>Support</p>
                    </div>
                    <div class="text">
 <form id="logout-form" method="post" action="">
    <div class="text">
    <button class="logout" type="submit" name="logout" style="display:block; border: none; background-color: transparent; color: white; font-weight: medium; width:100%;font-size:22px;">Logout</button>
    </div>
</form>

                    </div>
                </div>
            </div>
            <div class="profile-left">
                <div class="profile-info" id="profileInfo" style="display: none;">
                    <div class="ProfileMain">
                        <h2>Profile</h2>
                    </div>
                    <form id="register-form" method="post" action="">
                        <div class="info2">
                            <label for="name">Name:</label>
                        </div>
                        <div class="info1">
                        <input type="text" id="name" name="name" value="<?php echo $name; ?>">
                        </div>
                        <div class="info2">
                            <label for="surname">Surname:</label>
                        </div>
                        <div class="info1">
                        <input type="text" id="surname" name="surname" value="<?php echo $surname; ?>">
                        </div>
                        <div class="info2">
    <label for="username">Username:</label>
</div>
<div class="info1">
    <input type="text" id="username" name="username" value="<?php echo $username; ?>" readonly style="background-color: #f2f2f2;">
</div>

                        <div class="info2">
                            <label for="email">Email:</label>
                        </div>
                        <div class="info1">
                        <input type="email" id="email" name="email" value="<?php echo $email; ?>">
                        </div>
                        <div class="info2">
                            <label for="password">Password:</label>
                        </div>
                        <div class="info1">
                        <input type="password" id="password" name="password" value="<?php echo $password; ?>">
                        </div>
                        <div class="button">
                            <button type="submit" name="update">UPDATE</button>
                        </div>
                    </form>
                </div>
                <div class="upload-form" id="uploadForm" style="display: none;">
    <form id="upload-form" method="post" action="">
        <!-- Remove the file input -->
        <!-- <input type="file" name="avatar" accept="image/png, image/jpeg" required> -->
        <label for="avatarLink">Avatar Link:</label>
        <input type="text" id="avatarLink" name="avatarLink" placeholder="Enter image URL">
        <button type="submit" name="upload">Upload</button>
    </form>
</div>

            </div>
        </div>
    </div>

    <script>
        function toggleProfile() {
            var profileInfo = document.getElementById("profileInfo");
            var uploadForm = document.getElementById("uploadForm");

            uploadForm.style.display = 'none';

            profileInfo.style.display = (profileInfo.style.display === 'none') ? 'block' : 'none';
        }

        function toggleUpload() {
            var uploadForm = document.getElementById("uploadForm");
            var profileInfo = document.getElementById("profileInfo");

            profileInfo.style.display = 'none';
            uploadForm.style.display = (uploadForm.style.display === 'none') ? 'block' : 'none';
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#register-form").submit(function(e){
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "profile.php",
                    data: $(this).serialize() + "&update=true",
                    success: function(response) {
                        if (response === "Update successful") {
                            alert("Profile updated successfully!");
                        } else {
                            alert(response);
                        }
                    }
                });
            });

            $("#upload-form").submit(function (e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "profile.php",
        data: $(this).serialize() + "&upload=true",
        success: function (response) {
            if (response.startsWith("Success")) {
                alert(response);
            } else {
                alert("Error: " + response);
            }
        }
                });
            });
        });
    </script>
</body>
</html>

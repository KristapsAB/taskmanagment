<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
require_once 'TaskManager.php'; 

$dbManager = new DatabaseManager();
$conn = $dbManager->conn;

$name = '';
$surname = '';
$email = '';
$password = '';
$username = '';

$user = new User($conn, $_SESSION['username'], null, null, null);

$name = $user->getName();
$surname = $user->getSurname();
$email = $user->getEmail();
$password = $user->getPassword();

$user = new User($conn, $_SESSION['username'], $name, $surname, $email, $password);
$username = $_SESSION['username'];

$taskManager = new TaskManager();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $user->updateProfile($name, $surname, $email, $password);

    echo $result;
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload'])) {
    if (isset($_POST['avatarLink'])) {
        $avatarLink = $_POST['avatarLink'];

        if (strlen($avatarLink) <= 255) {
            $result = $user->updateAvatar($avatarLink);

            if ($result === true) {
                echo "Success: Avatar updated successfully!";
            } else {
                echo "Error: " . $result;
            }
        } else {
            echo "Error: Avatar link is too long. Maximum allowed length is 255 characters.";
        }
    } else {
        echo "Error: Avatar link not provided.";
    }

    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tasksUploaded'])) {
    // Fetch tasks from the database for the logged-in user
    $tasks = $taskManager->getTasksByUser($username);

    if (!empty($tasks)) {
        echo json_encode($tasks);
    } else {
        echo "No tasks found for the user.";
    }

    exit;
}


function displayAllTasks($conn, $username)
{
    // Assuming there is a 'tasks' table with a foreign key 'user_id'
    $query = "SELECT * FROM tasks WHERE user_id = (SELECT id FROM users WHERE username = ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $tasks = [];

    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }

    $stmt->close();

    return $tasks;
}


$allTasks = displayAllTasks($conn, $username);
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
<img src="<?php echo $user->getAvatarUrl() ? $user->getAvatarUrl() : 'Avatar.png'; ?>" alt="<?php echo $_SESSION['username']; ?>'s Avatar" class="avatar">
                </div>
                <div class="name">
                    <h1><?php echo $_SESSION['username']; ?></h1>
                </div>
                <div class="side-options">
                    <div class="text" id="profileOption">
                        <p onclick="toggleProfile()">Profile</p>
                    </div>
                    <div class="text">
                        <p onclick="showTasks()">Tasks Uploaded</p>
                    </div>
                    <div class="text">
                        <p onclick="toggleUpload()">Upload Profile Image</p>
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
                <div class="upload-form1"  id="uploadForm" style="display: none;">
                    <form id="upload-form" method="post" action="">
                        <label for="avatarLink">Avatar Link:</label>
                        <input type="text" id="avatarLink" name="avatarLink" placeholder="Enter image URL">
                        <div class="button69">
                            <button type="submit" name="upload">Upload</button>
                        </div>
                    </form>
                </div>
                <div id="tasksSection" style="display: none;">
    <h2>Your tasks</h2>
    <div id="allTasksList">
        <?php
        if (!empty($allTasks)) {
            echo "<table border='1'>";
            echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>Description</th>";
            echo "<th>Due Date</th>";
            echo "<th>Comments</th>";
            echo "<th>Status</th>";
            echo "</tr>";

            foreach ($allTasks as $task) {
                echo "<tr>";
                echo "<td>" . $task['name'] . "</td>";
                echo "<td>" . $task['description'] . "</td>";
                echo "<td>" . $task['due_date'] . "</td>";
                echo "<td>" . $task['comments'] . "</td>";
                echo "<td>" . $task['status'] . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "No tasks found for the user.";
        }
        ?>
    </div>
</div>

            </div>
        </div>
    </div>

    <script>
        function toggleProfile() {
            var profileInfo = document.getElementById("profileInfo");
            var uploadForm = document.getElementById("uploadForm");
            var tasksSection = document.getElementById("tasksSection");

            uploadForm.style.display = 'none';
            tasksSection.style.display = 'none';

            profileInfo.style.display = (profileInfo.style.display === 'none') ? 'block' : 'none';
        }

        function toggleUpload() {
            var uploadForm = document.getElementById("uploadForm");
            var profileInfo = document.getElementById("profileInfo");
            var tasksSection = document.getElementById("tasksSection");

            profileInfo.style.display = 'none';
            tasksSection.style.display = 'none';

            uploadForm.style.display = (uploadForm.style.display === 'none') ? 'block' : 'none';
        }

        function showTasks() {
            var tasksSection = document.getElementById("tasksSection");
            var profileInfo = document.getElementById("profileInfo");
            var uploadForm = document.getElementById("uploadForm");

            profileInfo.style.display = 'none';
            uploadForm.style.display = 'none';

            tasksSection.style.display = (tasksSection.style.display === 'none') ? 'block' : 'none';
        }
    </script>

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

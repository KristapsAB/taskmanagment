<?php
require_once 'db_connection.php';
require_once 'User.php';

function getCurrentUser($conn, $username) {
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}

if (isset($_SESSION['username'])) {
    $conn = (new DatabaseManager())->conn;
    $currentUser = getCurrentUser($conn, $_SESSION['username']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Toggle Sidebar</title>
  <link rel="stylesheet" href="sidebar.css">
</head>
<body>

<div id="sidebar">
    <?php if (isset($currentUser)) : ?>
        <div class="user-info">
            <img src="<?php echo $currentUser['avatar_url'] ? $currentUser['avatar_url'] : 'Avatar.png'; ?>" alt="User Avatar" class="avatar">
            <p><?php echo $currentUser['username']; ?></p>
        </div>
    <?php endif; ?>

    <a href="index.php">Home</a>
    <a href="profile.php">Profile</a>
    <a href="createTask.php">Create Task</a>
    <a href="createProject.php">Create project</a>
    <a href="calendar.php">Calendar</a>
    <a href="createProject.php">Create project</a>
    <a href="Dashboard.php">Dashboard</a>
    <a href="logout.php">Logout</a>
</div>

<div id="main">
  <div id="toggle-btn" onclick="toggleSidebar()">&#9776;</div>
</div>

<script>
  function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const main = document.getElementById('main');
    const toggleBtn = document.getElementById('toggle-btn');

    if (sidebar.style.left === '-250px') {
      sidebar.style.left = '0';
      main.style.marginLeft = '250px';
      toggleBtn.innerHTML = '&#10005;';
    } else {
      sidebar.style.left = '-250px';
      main.style.marginLeft = '0';
      toggleBtn.innerHTML = '&#9776;';
    }
  }
</script>

</body>
</html>

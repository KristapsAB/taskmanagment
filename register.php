<?php
require_once 'User.php';

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "taskManager";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = new User($conn, $_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirm_password']);
echo $user->register();
?>

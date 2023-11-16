<?php
require_once 'User.php';

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "task_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = new User($conn, $_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirm_password']);
$result = $user->register();

$response = ['success' => false, 'message' => 'Registration failed'];

if (is_string($result) && $result === 'Registration successful') {
    $response['success'] = true;
    $response['redirect'] = 'http://localhost:8888/ksy/martins/createTask.php';
    $response['message'] = 'Registration successful';
} else {
    $response['message'] = $result;
}

echo json_encode($response);
?>

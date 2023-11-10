<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "taskManager";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Database connection successful";

// Test a simple query
$sql = "SELECT * FROM users LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        print_r($row);
    }
} else {
    echo "No data found";
}

$conn->close();
?>

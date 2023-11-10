<?php
session_start(); // start the session

class User {
    private $conn;
    private $username;
    private $password;

    public function __construct($conn, $username, $password) {
        $this->conn = $conn;
        $this->username = $username;
        $this->password = $password;
    }

    public function login() {
        // Check if username or password is empty
        if (empty($this->username) || empty($this->password)) {
            return "Username or password cannot be empty";
        }

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $this->username, $this->password);

        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return true;
        } else {
            return "Invalid username or password";
        }
    }
}

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "taskManager";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = new User($conn, $_POST['username'], $_POST['password']);
$login = $user->login();
if ($login === true) {
    $_SESSION['username'] = $_POST['username']; // store the username in the session
    echo "Login successful";
} else {
    echo $login; // echo the error message
}
?>

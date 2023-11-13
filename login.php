<?php
session_start(); // Start the session

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

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $this->username);

        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password using password_verify
            if (password_verify($this->password, $user['password'])) {
                return true;
            } else {
                return "Invalid username or password";
            }
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
    $_SESSION['username'] = $_POST['username']; // Store the username in the session
    echo "Login successful";
} else {
    echo $login; // Echo the error message
}
?>

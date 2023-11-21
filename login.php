<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

header('Content-Type: text/html; charset=utf-8');

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
        if (empty($this->username) || empty($this->password)) {
            return "Username or password cannot be empty";
        }
    
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $this->username);
    
        $stmt->execute();
    
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
    
            if (password_verify($this->password, $user['password'])) {
                return $user; 
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
$dbname = "task_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$user = new User($conn, $_POST['username'], $_POST['password']);
$login = $user->login();

if (is_array($login)) {
    $_SESSION['user_id'] = $login['id'];
    $_SESSION['username'] = $_POST['username'];
    echo json_encode(['success' => true, 'redirect' => 'http://localhost:8888/ksy/martins/createTask.php']);
    exit();
} else {
    echo json_encode(['success' => false, 'message' => $login]);
}




?>

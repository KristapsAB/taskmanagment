<?php
class UserAuth {
    private $db;

    public function __construct() {
        $this->db = new mysqli('localhost', 'root', 'root', 'taskManager');
        if ($this->db->connect_error) {
            die('Database connection error: ' . $this->db->connect_error);
        }
    }

    public function loginUser($username, $password) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return 'success';
            }
        }
        return 'error';
    }
}


?>
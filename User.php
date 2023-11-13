<?php
class User {
    private $conn;
    private $username;
    private $email;
    private $password;
    private $avatar_url;

    public function __construct($conn, $username, $email, $password) {
        $this->conn = $conn;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    // Function to start the session (add this to any file where you use sessions)
    private function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login() {
        $this->startSession();

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
                $_SESSION['username'] = $user['username'];
                return true;
            } else {
                return "Invalid username or password";
            }
        } else {
            return "Invalid username or password";
        }
    }

    public function updateProfile($name, $surname, $email, $password) {
        $this->startSession();

        $name = $this->validateInput($name);
        $surname = $this->validateInput($surname);
        $email = $this->validateInput($email);

        $stmt = $this->conn->prepare("UPDATE users SET name = ?, surname = ?, email = ?, password = ? WHERE username = ?");
        $stmt->bind_param("sssss", $name, $surname, $email, password_hash($password, PASSWORD_DEFAULT), $_SESSION['username']);

        if ($stmt->execute()) {
            return "Update successful";
        } else {
            return "Error updating profile: " . $stmt->error;
        }
    }

    // Method to retrieve user information
    public function getUserInfo() {
        $this->startSession();

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $_SESSION['username']);

        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null; // User not found
        }
    }
    public function getName() {
        $stmt = $this->conn->prepare("SELECT name FROM users WHERE username = ?");
        $stmt->bind_param("s", $this->username);

        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return $user['name'];
        } else {
            return null;
        }
    }
    public function getSurname() {
        $stmt = $this->conn->prepare("SELECT surname FROM users WHERE username = ?");
        $stmt->bind_param("s", $this->username);

        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return $user['surname'];
        } else {
            return null;
        }
    }
    public function getEmail() {
        $stmt = $this->conn->prepare("SELECT email FROM users WHERE username = ?");
        
        // Assign the username to a variable
        $username = $this->username;
        $stmt->bind_param("s", $username);
    
        $stmt->execute();
    
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return $user['email'];
        } else {
            return null;
        }
    }
    
    public function getPassword() {
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE username = ?");
        
        if (!$stmt) {
            return "Error preparing statement: " . $this->conn->error;
        }
    
        // Assign the username to a variable
        $username = $this->username;
        $stmt->bind_param("s", $username);
    
        if (!$stmt->execute()) {
            return "Error executing statement: " . $stmt->error;
        }
    
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return $user['password'];
        } else {
            return null;
        }
    }
    // Inside the User class

public function getAvatarUrl() {
    return $this->avatar_url;
}

public function updateAvatar($avatarUrl) {
    // You may want to add validation for the URL
    $this->avatar_url = $avatarUrl;

    // Update the avatar URL in the database
    $stmt = $this->conn->prepare("UPDATE users SET avatar_url = ? WHERE username = ?");
    $stmt->bind_param("ss", $avatarUrl, $this->username);

    if ($stmt->execute()) {
        return true;
    } else {
        return $stmt->error;
    }
}

    
    
    

    public function register() {
        if (empty($this->username) || empty($this->email) || empty($this->password)) {
            return "Username, email, or password cannot be empty";
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format";
        }

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $this->username, $this->email);

        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return "Username or email already exists";
        } else {
            $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $this->username, $this->email, $hashed_password);

            if ($stmt->execute()) {
                return "Registration successful";
            } else {
                return "Error: " . $stmt->error;
            }
        }
    }

    private function validateInput($input) {
        return $input;
    }
}
?>

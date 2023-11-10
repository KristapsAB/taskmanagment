<?php
session_start(); // start the session

class User {
    private $conn;
    private $username;
    private $email;
    private $password;
    private $confirm_password;

    public function __construct($conn, $username, $email, $password, $confirm_password) {
        $this->conn = $conn;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->confirm_password = $confirm_password;
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
    public function register() {
        // Check if username, email or password is empty
        if (empty($this->username) || empty($this->email) || empty($this->password)) {
            return "Username, email or password cannot be empty";
        }

        // Check if email is valid
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format";
        }

        // Check if passwords match
        if ($this->password != $this->confirm_password) {
            return "Passwords do not match";
        }

        // Check if user already exists
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $this->username, $this->email);

        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return "Username or email already exists";
        } else {
            // Insert new user
            $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $this->username, $this->email, $this->password);

            if ($stmt->execute()) {
                return "Registration successful";
            } else {
                return "Error: " . $stmt->error;
            }
        }
    }
}

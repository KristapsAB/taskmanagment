<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class DatabaseManager {
    private $servername = "localhost";
    private $username = "root";
    private $password = "root";
    private $database = "task_management";
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Error connecting to the database: " . $this->conn->connect_error);
        }
    }
}

?>

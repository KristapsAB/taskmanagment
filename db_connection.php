<?php

class DatabaseManager {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
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

<?php

require_once('db_connection.php');

class TaskManager extends DatabaseManager {

    public function createTask($name, $description, $dueDate, $comments) {
        // You should use prepared statements to prevent SQL injection
        $defaultStatus = "TO DO";
        $stmt = $this->conn->prepare("INSERT INTO tasks (name, description, due_date, comments, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $description, $dueDate, $comments, $defaultStatus);
    
        if ($stmt->execute()) {
            $stmt->close();
            return "";
        } else {
            $stmt->close();
            return "Error creating task: " . $stmt->error;
        }
    }
    

    public function getAllTasks() {
        $tasks = array();

        $result = $this->conn->query("SELECT * FROM tasks");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tasks[] = $row;
            }
        }

        return $tasks;
    }

    public function updateTaskStatus($taskId, $status) {
        // You should use prepared statements to prevent SQL injection
        $stmt = $this->conn->prepare("UPDATE tasks SET status = ? WHERE task_id = ?");
        $stmt->bind_param("si", $status, $taskId);

        if ($stmt->execute()) {
            return "Task status updated successfully"; // Log success message
        } else {
            return "Error updating task status: " . $stmt->error;
        }

        $stmt->close();
    }


}
?>

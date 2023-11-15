<?php
require_once('db_connection.php');

class TaskManager extends DatabaseManager
{
    public function createTask($name, $description, $dueDate, $comments)
    {
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

    public function getAllTasks()
    {
        $tasks = array();

        $result = $this->conn->query("SELECT * FROM tasks");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tasks[] = $row;
            }
        }

        return $tasks;
    }

    public function updateTaskStatus($taskId, $status)
    {
        $stmt = $this->conn->prepare("UPDATE tasks SET status = ? WHERE task_id = ?");
        $stmt->bind_param("si", $status, $taskId);

        if ($stmt->execute()) {
            return "Task status updated successfully";
        } else {
            return "Error updating task status: " . $stmt->error;
        }

        $stmt->close();
    }

    public function getTasksByMonth($month)
    {
        $query = "SELECT * FROM tasks WHERE MONTH(due_date) = MONTH(STR_TO_DATE(?, '%M'))";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $this->conn->errorInfo()[2]);
        }
        $stmt->bind_param("s", $month);
        if (!$stmt->execute()) {
            throw new Exception("Error executing statement: " . $stmt->errorInfo()[2]);
        }
        $result = $stmt->get_result();
        $tasks = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $tasks;
    }

    public function getTasksByDay($month, $day)
    {
        $monthNumber = date('m', strtotime($month));
    
        $query = "SELECT * FROM tasks WHERE MONTH(due_date) = ? AND DAY(due_date) = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $this->conn->errorInfo()[2]);
        }

        $stmt->bind_param("ii", $monthNumber, $day);
    
        if (!$stmt->execute()) {
            throw new Exception("Error executing statement: " . $stmt->errorInfo()[2]);
        }
    
        $result = $stmt->get_result();
        $tasks = $result->fetch_all(MYSQLI_ASSOC);
    
        $stmt->close();
        return $tasks;
    }

    
}
?>

<?php
require_once('db_connection.php');

class TaskManager extends DatabaseManager
{
    public function createTask($userId, $name, $description, $dueDate, $comments)
    {
        $defaultStatus = "TO DO";
        $stmt = $this->conn->prepare("INSERT INTO tasks (user_id, name, description, due_date, comments, status) VALUES (?, ?, ?, ?, ?, ?)");

        if ($stmt === false) {
            return "Error preparing statement: " . $this->conn->error;
        }

        $bindResult = $stmt->bind_param("isssss", $userId, $name, $description, $dueDate, $comments, $defaultStatus);

        if ($bindResult === false) {
            return "Error binding parameters: " . $stmt->error;
        }

        if ($stmt->execute()) {
            $stmt->close();
            return "Task created successfully!";
        } else {
            $stmt->close();
            return "Error creating task: " . $stmt->error;
        }
    }

    public function getAllTasks()
    {
        $result = $this->conn->query("SELECT * FROM tasks");
    
        if ($result === false) {
            return "Error fetching tasks: " . $this->conn->error;
        }
    
        $tasks = [];
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
    
        return $tasks;
    }
    

    public function updateTaskStatus($taskId, $status)
    {
        $stmt = $this->conn->prepare("UPDATE tasks SET status = ? WHERE task_id = ?");
        $stmt->bind_param("si", $status, $taskId);

        if ($stmt->execute()) {
            $stmt->close();
            return "Task status updated successfully";
        } else {
            $stmt->close();
            return "Error updating task status: " . $stmt->error;
        }
    }

    public function getTasksByMonth($userId, $month)
{
    $query = "SELECT * FROM tasks WHERE user_id = ? AND MONTH(due_date) = MONTH(STR_TO_DATE(?, '%M'))";
    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
        return "Error preparing statement: " . $this->conn->error;
    }

    $stmt->bind_param("is", $userId, $month);

    if (!$stmt->execute()) {
        return "Error executing statement: " . $stmt->error;
    }

    $result = $stmt->get_result();
    $tasks = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $tasks;
}

public function getTasksByDay($userId, $month, $day)
{
    $monthNumber = date('m', strtotime($month));

    $query = "SELECT * FROM tasks WHERE user_id = ? AND MONTH(due_date) = ? AND DAY(due_date) = ?";
    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
        return "Error preparing statement: " . $this->conn->error;
    }

    $stmt->bind_param("iii", $userId, $monthNumber, $day);

    if (!$stmt->execute()) {
        return "Error executing statement: " . $stmt->error;
    }

    $result = $stmt->get_result();
    $tasks = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    return $tasks;
}
public function getTasksByUserId($userId)
{
    $stmt = $this->conn->prepare("SELECT * FROM tasks WHERE user_id = ?");
    
    if (!$stmt) {
        return "Error preparing statement: " . $this->conn->error;
    }
    
    $stmt->bind_param("i", $userId);
    
    if (!$stmt->execute()) {
        return "Error executing statement: " . $stmt->error;
    }
    
    $result = $stmt->get_result();
    $tasks = $result->fetch_all(MYSQLI_ASSOC);
    
    $stmt->close();
    
    return $tasks;
}

}
?>

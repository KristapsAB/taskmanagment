<?php
require_once("db_connection.php");

class ProjectManager extends DatabaseManager
{
    public function createProject($userId, $projectName, $projectDescription)
    {
        $stmt = $this->conn->prepare("INSERT INTO projects (user_id, project_name, project_description) VALUES (?, ?, ?)");

        if (!$stmt) {
            return "Error preparing statement: " . $this->conn->error;
        }

        $stmt->bind_param("iss", $userId, $projectName, $projectDescription);

        if ($stmt->execute()) {
            $stmt->close();
        } else {
            $stmt->close();
            return "Failed to create project: " . $this->conn->error;
        }
    }

    public function getAllProjects()
    {
        $result = $this->conn->query("SELECT * FROM projects");

        if ($result === false) {
            return "Error fetching projects: " . $this->conn->error;
        }

        $projects = [];
        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }

        return $projects;
    }

    public function getProjectsByUserId($userId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM projects WHERE user_id = ?");
        
        if (!$stmt) {
            return "Error preparing statement: " . $this->conn->error;
        }
    
        $stmt->bind_param("i", $userId);
    
        if (!$stmt->execute()) {
            return "Error executing statement: " . $stmt->error;
        }
    
        $result = $stmt->get_result();
        
        if ($result === false) {
            return "Error fetching projects: " . $this->conn->error;
        }
    
        $projects = $result->fetch_all(MYSQLI_ASSOC);
    
    
        $stmt->close();
        return $projects;
    }
}
?>

<?php
require_once("db_connection.php");

class ProjectManager extends DatabaseManager{

    public function createProject($projectName, $projectDescription){
        $stmt = $this->conn->prepare("INSERT INTO projects (project_name, project_description) VALUES (?, ?)");
        $stmt->bind_param("ss", $projectName, $projectDescription); 
        
        if($stmt->execute()){
            return "";
        }else{
            return "error";
        }
        $stmt->close();
    }

    public function getAllProjects() {
        $result = $this->conn->query("SELECT project_name FROM projects");

        if ($result === false) {
            return []; 
        }
        $projects = [];
        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }
        return $projects;
    }

    
}
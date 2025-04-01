<?php

class TaskModel extends Model{

    public function getTasksByUserId($user_id) {
        $query = "SELECT * FROM tasks WHERE user_id = :user_id ORDER BY due_date ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTask($id) {
        $query = "SELECT * FROM tasks WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createTask($user_id, $title, $description, $due_date, $completed) {
        $query = "INSERT INTO tasks (user_id, title, description, due_date, completed) VALUES (:user_id, :title, :description, :due_date, :completed)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':due_date', $due_date);
        $stmt->bindParam(':completed', $completed);
        return $stmt->execute();
    }

    public function updateTask($id, $title, $description, $due_date, $completed) {
        $query = "UPDATE tasks SET title = :title, description = :description, due_date = :due_date, completed = :completed WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':due_date', $due_date);
        $stmt->bindParam(':completed', $completed);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function deleteTask($id) {
        $query = "DELETE FROM tasks WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
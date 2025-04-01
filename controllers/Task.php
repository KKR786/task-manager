<?php

class TaskController {
    private $taskModel;

    public function __construct($db) {
        $this->taskModel = new TaskModel($db);
    }

    public function listTasks($user_id) {
        return $this->taskModel->getTasksByUserId($user_id);
    }

    public function createTask($user_id, $title, $description, $due_date, $completed) {
        return $this->taskModel->createTask($user_id, $title, $description, $due_date, $completed);
    }

    public function updateTask($id, $title, $description, $due_date, $completed) {
        return $this->taskModel->updateTask($id, $title, $description, $due_date, $completed);
    }

    public function getTask($id) {
        return $this->taskModel->getTask($id);
    }

    public function deleteTask($id) {
        return $this->taskModel->deleteTask($id);
    }
}
?>
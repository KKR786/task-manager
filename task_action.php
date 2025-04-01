<?php
session_start();
header('Content-Type: application/json');

require_once './config/db.php';
require_once './models/Model.php';
require_once './models/Task.php';
require_once './controllers/Task.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Unauthorized access. Please log in.'
    ]);
    exit;
}

$db = (new Database())->connect();

$taskController = new TaskController($db);

function sendResponse($status, $message, $data = null) {
    $response = [
        'status' => $status,
        'message' => $message
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_task') {
        $task_id = $_GET['task_id'] ?? null;
        
        if (!$task_id) {
            sendResponse('error', 'Invalid task ID.');
        }
        
        $task = $taskController->getTask($task_id);
        
        if ($task) {
            echo json_encode($task);
            exit;
        } else {
            sendResponse('error', 'Task not found.');
        }
    }

    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $task_id = $_POST['task_id'] ?? null;
        
        if (!$task_id) {
            sendResponse('error', 'Invalid task ID.');
        }
        
        $result = $taskController->deleteTask($task_id);
        
        if ($result) {
            sendResponse('success', 'Task deleted successfully.');
        } else {
            sendResponse('error', 'Failed to delete task.');
        }
    }
    
    $task_id = $_POST['task_id'] ?? null;
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $due_date = $_POST['due_date'] ?? null;
    $completed = isset($_POST['completed']) && $_POST['completed'] == '1' ? 1 : 0;

    if (empty($title) || empty($description)) {
        sendResponse('error', 'Title and description are required.');
    }

    if (empty($task_id)) {
        $result = $taskController->createTask(
            $_SESSION['user_id'], 
            $title, 
            $description, 
            $due_date, 
            $completed
        );
        
        if ($result) {
            sendResponse('success', 'Task created successfully.');
        } else {
            sendResponse('error', 'Failed to create task.');
        }
    } else {
        $result = $taskController->updateTask(
            $task_id, 
            $title, 
            $description, 
            $due_date, 
            $completed
        );
        
        if ($result) {
            sendResponse('success', 'Task updated successfully.');
        } else {
            sendResponse('error', 'Failed to update task.');
        }
    }
} catch (Exception $e) {
    sendResponse('error', 'An unexpected error occurred: ' . $e->getMessage());
}
?>
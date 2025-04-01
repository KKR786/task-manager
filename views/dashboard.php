<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once '../config/db.php';
require_once '../models/Model.php';
require_once '../models/Task.php';
require_once '../controllers/Task.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php');
    exit;
}

try {
    $db = (new Database())->connect();
    $taskController = new TaskController($db);
} catch (Exception $e) {
    error_log('Database Connection Error: ' . $e->getMessage());
    header('Location: ../views/error.php');
    exit;
}

if (isset($_GET['delete_id'])) {
    try {
        $task_id = $_GET['delete_id'];
        if ($taskController->deleteTask($task_id)) {
            header("Location: dashboard.php");
            exit;
        }
    } catch (Exception $e) {
        error_log('Task Deletion Error: ' . $e->getMessage());
    }
}

try {
    $tasks = $taskController->listTasks($_SESSION['user_id']);
} catch (Exception $e) {
    error_log('Task Fetching Error: ' . $e->getMessage());
    $tasks = [];
}

$editTask = null;
if (isset($_GET['edit_id'])) {
    try {
        $editTask = $taskController->getTask($_GET['edit_id']);
    } catch (Exception $e) {
        error_log('Edit Task Fetch Error: ' . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="../global.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>
<body>
    <?php include 'top_bar.php'; ?>

    <div class="container">
        <h2 class="mt-4">Welcome to Your Dashboard</h2>

        <div class="d-flex align-items-center justify-content-between">
            <h3 class="my-4">Your Tasks</h3>    
            <button class="btn btn-primary mt-3" data-toggle="modal" data-target="#taskModal" id="createBtn">
                Create New Task
            </button>
        </div>
    
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Due Date</th>
                    <th>Completed</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($tasks) > 0): ?>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?= htmlspecialchars($task['title']) ?></td>
                            <td><?= htmlspecialchars($task['description']) ?></td>
                            <td><?= $task['due_date'] ? htmlspecialchars($task['due_date']) : 'N/A' ?></td>
                            <td><?= $task['completed'] ? 'Yes' : 'No' ?></td>
                            <td class="d-flex align-items-center">
                                <button class="edit mr-4 edit-task" 
                                   data-id="<?= $task['id'] ?>" 
                                   data-toggle="modal" 
                                   data-target="#taskModal">
                                    <i class="fas fa-pen fa-xs"></i>
                    </button>
                                <button title="Delete" 
                                        class="trash mr-4" 
                                        onclick="confirmDelete(<?= $task['id'] ?>)">
                                    <i class="fas fa-trash fa-xs"></i>
                                </button>
                                <?php if ($task['completed']): ?>
                                    <div class="share-dropdown">
                                        <button class="share" title="Share">
                                            <i class="fa-solid fa-share-from-square"></i>
                                        </button>
                                        <div class="share-dropdown-content">
                                            <button class="share-facebook" data-id="<?= $task['id'] ?>" data-platform="facebook">
                                                Share on<i class="fab fa-facebook-f ml-2"></i>
                                            </button>
                                            <button class="share-google" data-task-id="<?= $task['id'] ?>" data-platform="google">
                                                Share on<i class="fab fa-google ml-2"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No tasks found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php include 'task_modal.php'; ?>

    <script>
        const GOOGLE_CLIENT_ID = "<?= GOOGLE_CLIENT_ID ?>";
        const GOOGLE_REDIRECT_URI = "<?= GOOGLE_REDIRECT_URI ?>";
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="../helper.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
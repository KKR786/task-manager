<?php

session_start();

require_once 'config/db.php';
require_once 'models/Model.php';
require_once 'models/User.php';
require_once 'models/Task.php';
require_once 'controllers/User.php';
require_once 'controllers/Task.php';

try {
    $db = (new Database())->connect();
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

setupDatabaseAndTables($db);

$authController = new AuthController($db);
$taskController = new TaskController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($authController->login($username, $password)) {
        header('Location: views/dashboard.php');
        exit;
    } else {
        echo "<script>alert('Invalid username or password');</script>";
    }
} elseif (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $authController->logout();
    header('Location: views/login.php');
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header('Location: views/login.php');
    exit;
}

function setupDatabaseAndTables($connection) {
    try {
        $database_name = 'task_manager';
        $query_check_db = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :db_name";
        $stmt = $connection->prepare($query_check_db);
        $stmt->bindParam(':db_name', $database_name);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            $query_create_db = "CREATE DATABASE `$database_name`";
            $connection->exec($query_create_db);
            echo "Database '$database_name' created successfully.<br>";
        }

        $connection->exec("USE `$database_name`");

        $query_create_users = "
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL
            )
        ";
        $connection->exec($query_create_users);

        $admin_username = 'admin';
        $admin_password = password_hash('admin123', PASSWORD_BCRYPT);

        $query_insert_user = "
            INSERT IGNORE INTO users (username, password)
            VALUES (:username, :password)
        ";
        $stmt = $connection->prepare($query_insert_user);
        $stmt->bindParam(':username', $admin_username);
        $stmt->bindParam(':password', $admin_password);
        $stmt->execute();
        echo "Sample user 'admin' added successfully.<br>";

        $query_create_tasks = "
            CREATE TABLE IF NOT EXISTS tasks (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                due_date DATE,
                completed BOOLEAN DEFAULT FALSE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ";
        $connection->exec($query_create_tasks);
        echo "Table 'tasks' created successfully.<br>";

    } catch (PDOException $e) {
        die("Error during setup: " . $e->getMessage());
    }
}
?>

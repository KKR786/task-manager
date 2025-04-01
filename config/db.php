<?php
require_once __DIR__ . './config.php';

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct() {
        $this->host = DB_HOST;
        $this->db_name = DB_NAME;
        $this->username = DB_USER;
        $this->password = DB_PASS;
    }

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO('mysql:host=' . $this->host, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $query_check_db = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :db_name";
            $stmt = $this->conn->prepare($query_check_db);
            $stmt->bindParam(':db_name', $this->db_name);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                $query_create_db = "CREATE DATABASE IF NOT EXISTS `$this->db_name`";
                $this->conn->exec($query_create_db);
                echo "Database '$this->db_name' created successfully.<br>";
            }

            $this->conn->exec("USE `$this->db_name`");

        } catch(PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }
}
?>

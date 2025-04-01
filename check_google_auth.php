<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['google_access_token'])) {
    echo json_encode(['status' => 'success', 'accessToken' => $_SESSION['google_access_token']]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Not authorized']);
}
?>
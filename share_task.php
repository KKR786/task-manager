<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . './config/config.php';

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

$taskId = $_POST['taskId'] ?? null;
$platform = $_POST['platform'] ?? null;
$accessToken = $_SESSION['google_access_token'] ?? null;

if (!$taskId || !$platform) {
    sendResponse('error', 'Invalid task ID or platform.');
}

if ($platform === 'google' && !$accessToken) {
    sendResponse('error', 'Access token is required for Google My Business.');
}

$task = $taskController->getTask($taskId);
if (!$task) {
    sendResponse('error', 'Task not found.');
}

function postToFacebook($task) {
    $url = FB_GRAPH_PAGE_FEED_API;
    $data = [
        'message' => "Completed task: " . $task['title'] . " - " . $task['description'],
        'access_token' => FB_ACCESS_TOKEN
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);
}

try {
    $accountDetails = getAccountIdAndLocationId($accessToken);
    $accountId = $accountDetails['accountId'];
    $locationId = $accountDetails['locationId'];
} catch (Exception $e) {
    sendResponse('error', 'Failed to retrieve account or location ID: ' . $e->getMessage());
}


function postToGoogleMyBusiness($task, $accessToken, $accountId, $locationId) {
    $url = "https://mybusiness.googleapis.com/v4/accounts/{$accountId}/locations/{$locationId}/localPosts";

    $data = [
        'summary' => "Completed task: " . $task['title'] . " - " . $task['description'],
        'callToAction' => [
            'actionType' => 'LEARN_MORE',
            'url' => 'http://localhost:8000/views/dashboard.php'
        ]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);
}

if ($platform === 'facebook') {
    $response = postToFacebook($task);
    if (isset($response->id)) {
        sendResponse('success', 'Task shared to Facebook!');
    } else {
        sendResponse('error', 'Failed to share task to Facebook.');
    }
} elseif ($platform === 'google') {
    $response = postToGoogleMyBusiness($task, $accessToken, $accountId, $locationId);
    if (isset($response->name)) {
        sendResponse('success', 'Task shared to Google My Business!');
    } else {
        sendResponse('error', 'Failed to share task to Google My Business.');
    }
} else {
    sendResponse('error', 'Invalid platform specified.');
}
?>

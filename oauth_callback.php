<?php
session_start();
require_once __DIR__ . '/config/config.php';

if (!isset($_GET['code'])) {
    die('Authorization code not found.');
}

$tokenUrl = 'https://oauth2.googleapis.com/token';
$data = [
    'code' => $_GET['code'],
    'client_id' => GOOGLE_CLIENT_ID,
    'client_secret' => GOOGLE_CLIENT_SECRET,
    'redirect_uri' => GOOGLE_REDIRECT_URI,
    'grant_type' => 'authorization_code'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $tokenUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    file_put_contents(__DIR__ . '/oauth_debug.log', 'cURL Error: ' . curl_error($ch), FILE_APPEND);
    curl_close($ch);
    die('Error making token request.');
}

curl_close($ch);

file_put_contents(__DIR__ . '/oauth_debug.log', 'Token Response: ' . $response, FILE_APPEND);

$tokenData = json_decode($response, true);

if (isset($tokenData['error'])) {
    file_put_contents(__DIR__ . '/oauth_debug.log', 'Token Error: ' . $tokenData['error'], FILE_APPEND);
    die('Error retrieving access token: ' . $tokenData['error']);
}

if (isset($tokenData['access_token'])) {
    $_SESSION['google_access_token'] = $tokenData['access_token'];
    $_SESSION['google_refresh_token'] = $tokenData['refresh_token'] ?? null;

    header('Location: ' . BASE_URL . '/views/dashboard.php');
    exit;
} else {
    die('Failed to retrieve access token.');
}
?>
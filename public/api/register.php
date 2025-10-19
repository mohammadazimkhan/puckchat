<?php
require_once '../../includes/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$gender = $_POST['gender'] ?? '';
$country = $_POST['country'] ?? '';
$state = $_POST['state'] ?? '';

$result = Auth::registerUser($username, $password, $gender, $country, $state);

if ($result['success']) {
    // Auto-login after registration
    Auth::loginUser($username, $password);
}

echo json_encode($result);
?>
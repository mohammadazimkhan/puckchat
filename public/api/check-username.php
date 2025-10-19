<?php
require_once '../../includes/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$username = $_POST['username'] ?? '';

if (empty($username)) {
    echo json_encode(['available' => false, 'message' => 'Username is required']);
    exit;
}

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    
    if ($stmt->fetch()) {
        echo json_encode(['available' => false, 'message' => 'Username already taken']);
    } else {
        echo json_encode(['available' => true, 'message' => 'Username available']);
    }
    
} catch (Exception $e) {
    echo json_encode(['available' => false, 'message' => 'Error checking username']);
}
?>
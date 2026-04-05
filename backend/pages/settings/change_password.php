<?php
require_once __DIR__ . '/../../global/config/database.php';

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$db = Database::connect();
$user_id = $_SESSION['user_id'];

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['current_password']) || !isset($data['new_password'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing password information']);
    exit;
}

$current_password = $data['current_password'];
$new_password = $data['new_password'];

try {
    // 1. Verify current password
    $stmt = $db->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($current_password, $user['password_hash'])) {
        echo json_encode(['status' => 'error', 'message' => 'Current password incorrect']);
        exit;
    }

    // 2. Hash and update new password
    $new_hash = password_hash($new_password, PASSWORD_BCRYPT);
    $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
    $stmt->execute([$new_hash, $user_id]);

    echo json_encode(['status' => 'ok', 'message' => 'Password updated successfully']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
?>

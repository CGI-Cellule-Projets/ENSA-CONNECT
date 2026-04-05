<?php
require_once __DIR__ . '/../../global/session_start.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

require_once __DIR__ . '/../../global/config/database.php';

try {
    $db = Database::connect();
    
    $postId = (int)($_POST['post_id'] ?? 0);
    $type = $_POST['type'] ?? 'like';
    $action = $_POST['action'] ?? 'add';
    $userId = $_SESSION['user_id'];

    if ($postId <= 0) {
        throw new Exception("Invalid post ID.");
    }

    if ($action === 'add') {
        $stmt = $db->prepare("INSERT IGNORE INTO reactions (user_id, post_id, type) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $postId, $type]);
    } else {
        $stmt = $db->prepare("DELETE FROM reactions WHERE user_id = ? AND post_id = ? AND type = ?");
        $stmt->execute([$userId, $postId, $type]);
    }

    echo json_encode(['status' => 'ok', 'action' => $action]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>

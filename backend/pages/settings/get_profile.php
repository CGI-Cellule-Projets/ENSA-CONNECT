<?php
require_once __DIR__ . '/../../global/session_start.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../../global/config/database.php';

try {
    $db = Database::connect();
    $stmt = $db->prepare("
        SELECT u.username, u.email, p.* 
        FROM users u 
        LEFT JOIN profiles p ON u.id = p.user_id 
        WHERE u.id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $user['avatar_url'] = $user['avatar_url'] ? '/' . $user['avatar_url'] : null;
        echo json_encode(['status' => 'ok', 'user' => $user]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

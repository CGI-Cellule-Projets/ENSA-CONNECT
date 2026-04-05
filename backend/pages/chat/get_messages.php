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
$conversation_id = isset($_GET['conversation_id']) ? (int)$_GET['conversation_id'] : 0;

if ($conversation_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid conversation ID']);
    exit;
}

$stmt = $db->prepare("
    SELECT * FROM messages 
    WHERE conversation_id = ? 
    ORDER BY created_at ASC
");
$stmt->execute([$conversation_id]);
$messages = $stmt->fetchAll();

echo json_encode(['status' => 'ok', 'messages' => $messages]);
?>

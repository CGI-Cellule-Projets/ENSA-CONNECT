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

// Get all conversations for the user with the other participant's details
$stmt = $db->prepare("
    SELECT 
        c.id as conversation_id,
        u.id as other_user_id,
        u.username as other_user_name,
        p.avatar_url as other_user_avatar,
        (SELECT content FROM messages WHERE conversation_id = c.id ORDER BY created_at DESC LIMIT 1) as last_message,
        (SELECT created_at FROM messages WHERE conversation_id = c.id ORDER BY created_at DESC LIMIT 1) as last_message_at
    FROM conversations c
    JOIN users u ON (c.user1_id = u.id OR c.user2_id = u.id)
    LEFT JOIN profiles p ON u.id = p.user_id
    WHERE (c.user1_id = ? OR c.user2_id = ?) AND u.id != ?
    ORDER BY last_message_at DESC
");
$stmt->execute([$user_id, $user_id, $user_id]);
$conversations = $stmt->fetchAll();

echo json_encode(['status' => 'ok', 'conversations' => $conversations]);
?>

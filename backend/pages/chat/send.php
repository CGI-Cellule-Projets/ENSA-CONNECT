<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../global/config/database.php';
require_once __DIR__ . '/../../middleware/XSSProtection.php';

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$db = Database::connect();
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['receiver_id']) || !isset($data['content'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing receiver_id or content']);
    exit;
}

$sender_id = $_SESSION['user_id'];
$receiver_id = (int)$data['receiver_id'];
$content = XSSProtection::sanitize($data['content']);

// 1. Find or create conversation
$u1 = min($sender_id, $receiver_id);
$u2 = max($sender_id, $receiver_id);

$stmt = $db->prepare("SELECT id FROM conversations WHERE user1_id = ? AND user2_id = ?");
$stmt->execute([$u1, $u2]);
$conv = $stmt->fetch();

if (!$conv) {
    $stmt = $db->prepare("INSERT INTO conversations (user1_id, user2_id) VALUES (?, ?)");
    $stmt->execute([$u1, $u2]);
    $conversation_id = $db->lastInsertId();
} else {
    $conversation_id = $conv['id'];
}

// 2. Persist message
$stmt = $db->prepare("INSERT INTO messages (conversation_id, sender_id, content) VALUES (?, ?, ?)");
$stmt->execute([$conversation_id, $sender_id, $content]);
$message_id = $db->lastInsertId();

// 3. Trigger Pusher
$pusher = new Pusher\Pusher(
    getenv('PUSHER_APP_ID') ?: 'c922bfca140061b3ea91',
    getenv('PUSHER_KEY') ?: '4f39fcf9d33d7dcb174a',
    getenv('PUSHER_SECRET') ?: '2130604',
    ['cluster' => getenv('PUSHER_CLUSTER') ?: 'eu']
);

$pusher->trigger('chat-' . $conversation_id, 'message', [
    'id' => $message_id,
    'sender_id' => $sender_id,
    'content' => $content,
    'created_at' => date('Y-m-d H:i:s')
]);

echo json_encode(['status' => 'ok', 'message_id' => $message_id]);
?>

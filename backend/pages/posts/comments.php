<?php
require_once __DIR__ . '/../../global/session_start.php';
header('Content-Type: application/json');

require_once __DIR__ . '/../../global/config/database.php';
require_once __DIR__ . '/../../middleware/XSSProtection.php';

try {
    $db = Database::connect();
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection failed.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch comments for a post
    $postId = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;
    if ($postId <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid post ID.']);
        exit;
    }

    $stmt = $db->prepare("
        SELECT c.*, u.username 
        FROM comments c 
        JOIN users u ON c.user_id = u.id 
        WHERE c.post_id = ? 
        ORDER BY c.created_at ASC
    ");
    $stmt->execute([$postId]);
    $comments = $stmt->fetchAll();

    echo json_encode(['success' => true, 'comments' => $comments]);
    exit;

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add a new comment
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'You must be logged in to comment.']);
        exit;
    }

    $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';

    if ($postId <= 0 || empty($content)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Post ID and content are required.']);
        exit;
    }

    // Sanitize content
    $content = XSSProtection::sanitize($content);

    try {
        $stmt = $db->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$postId, $_SESSION['user_id'], $content]);
        
        echo json_encode(['success' => true, 'message' => 'Comment added successfully.']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to add comment.']);
    }
    exit;
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
}
?>

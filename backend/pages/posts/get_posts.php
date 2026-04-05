<?php
require_once __DIR__ . '/../../global/session_start.php';
header('Content-Type: application/json');

require_once __DIR__ . '/../../global/config/database.php';

try {
    $db = Database::connect();
    
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $perPage = 10;
    $offset = ($page - 1) * $perPage;

    // Filter by category if requested
    $category = $_GET['category'] ?? ($_GET['filter'] ?? null);
    $whereClause = "";
    $params = [':current_user_id' => $_SESSION['user_id'] ?? 0];
    
    if ($category && $category !== 'all') {
        $whereClause = "WHERE (p.category = :category OR p.TYPE = :category)";
        $params[':category'] = $category;
    }

    $sql = "
        SELECT
            p.id, p.author_id, p.content, p.TYPE, p.category, p.created_at,
            u.username, pr.avatar_url,
            (SELECT COUNT(*) FROM reactions WHERE post_id = p.id) as likes_count,
            (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comments_count,
            EXISTS(SELECT 1 FROM reactions WHERE post_id = p.id AND user_id = :current_user_id) as user_liked
        FROM posts p
        JOIN users u ON p.author_id = u.id
        LEFT JOIN profiles pr ON u.id = pr.user_id
        $whereClause
        ORDER BY p.created_at DESC
        LIMIT $perPage OFFSET $offset
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch media
    if (!empty($posts)) {
        $ids = array_map(function($p) { return $p['id']; }, $posts);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmtMedia = $db->prepare("SELECT post_id, media_path, media_type FROM post_media WHERE post_id IN ($placeholders)");
        $stmtMedia->execute($ids);
        $media = $stmtMedia->fetchAll(PDO::FETCH_ASSOC);
        
        $mediaByPost = [];
        foreach ($media as $m) $mediaByPost[$m['post_id']][] = $m;
        
        foreach ($posts as &$p) {
            $p['media'] = $mediaByPost[$p['id']] ?? [];
        }
    }

    $countSql = "SELECT COUNT(*) FROM posts p" . ($whereClause ? " $whereClause" : "");
    $countStmt = $db->prepare($countSql);
    $countStmt->execute($category && $category !== 'all' ? [':category' => $category] : []);
    $total = (int) $countStmt->fetchColumn();

    echo json_encode([
        'status' => 'ok',
        'posts' => $posts,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => ceil($total / $perPage),
            'total_posts' => $total
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

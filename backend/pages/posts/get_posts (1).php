<?php
// ─────────────────────────────────────────────
// get_posts.php — adapted for newsfeed index.php
//
// index.php calls:
//   fetch(`/backend/pages/posts/get_posts.php?filter=...&page=...&limit=...&search=...`)
//
// Uses existing Database::connect() from the team's database.php
// No changes to index.php needed
// ─────────────────────────────────────────────

require_once __DIR__ . '/../../global/session_start.php';
header('Content-Type: application/json');

require_once __DIR__ . '/../../global/config/database.php';

try {
    $db = Database::connect();

    // ── Params from index.php ──────────────────
    // index.php sends: filter, page, limit, search
    $filter  = trim($_GET['filter']  ?? $_GET['category'] ?? 'all');
    $page    = max(1, (int)($_GET['page']  ?? 1));
    $perPage = min(20, (int)($_GET['limit'] ?? 10));
    $search  = trim($_GET['search']  ?? '');
    $offset  = ($page - 1) * $perPage;

    $currentUserId = (int)($_SESSION['user_id'] ?? 0);

    // ── Build WHERE ────────────────────────────
    $whereClause = "WHERE 1=1";
    $params = [':current_user_id' => $currentUserId];

    // filter maps to TYPE enum('status','offer') OR category column
    if ($filter && $filter !== 'all') {
        if (in_array($filter, ['status', 'offer'])) {
            $whereClause .= " AND p.TYPE = :filter";
            $params[':filter'] = $filter;
        } else {
            // internship, pfe, mentorship, experience, general
            $whereClause .= " AND p.category = :filter";
            $params[':filter'] = $filter;
        }
    }

    // search
    if ($search !== '') {
        $whereClause .= " AND (u.username LIKE :search OR p.content LIKE :search)";
        $params[':search'] = "%$search%";
    }

    // ── Main query ─────────────────────────────
    $sql = "
        SELECT
            p.id,
            p.author_id,
            p.content,
            p.TYPE,
            p.category,
            p.created_at,
            u.username,
            u.role_id,
            COALESCE(pr.avatar_url, '')  AS avatar_url,
            COALESCE(pr.first_name, '')  AS first_name,
            COALESCE(pr.last_name, '')   AS last_name,
            COALESCE(pr.position, '')    AS position,
            (SELECT COUNT(*) FROM reactions WHERE post_id = p.id AND type = 'like') AS likes_count,
            (SELECT COUNT(*) FROM comments  WHERE post_id = p.id)                   AS comments_count,
            EXISTS(SELECT 1 FROM reactions WHERE post_id = p.id AND user_id = :current_user_id AND type = 'like') AS user_liked
        FROM posts p
        JOIN  users    u  ON p.author_id = u.id
        LEFT JOIN profiles pr ON u.id    = pr.user_id
        $whereClause
        ORDER BY p.created_at DESC
        LIMIT $perPage OFFSET $offset
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ── Attach media ───────────────────────────
    if (!empty($posts)) {
        $ids = array_column($posts, 'id');
        $ph  = implode(',', array_fill(0, count($ids), '?'));
        $ms  = $db->prepare("SELECT post_id, media_path, media_type FROM post_media WHERE post_id IN ($ph) ORDER BY id ASC");
        $ms->execute($ids);
        $mediaByPost = [];
        foreach ($ms->fetchAll(PDO::FETCH_ASSOC) as $m) {
            $mediaByPost[$m['post_id']][] = $m;
        }
        foreach ($posts as &$p) {
            $p['media']         = $mediaByPost[$p['id']] ?? [];
            $p['user_liked']    = (bool)$p['user_liked'];
            $p['likes_count']   = (int)$p['likes_count'];
            $p['comments_count']= (int)$p['comments_count'];
        }
        unset($p);
    }

    echo json_encode([
        'status' => 'ok',
        'posts'  => $posts,
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>

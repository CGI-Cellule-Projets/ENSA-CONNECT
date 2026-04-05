<?php
/**
 * POST /api/posts
 * Crée un nouveau post (texte + image optionnelle).
 *
 * Paramètres POST attendus :
 *   - content   (string, obligatoire) : texte du post
 *   - image_url (string, optionnel)   : URL de l'image
 *   - TYPE      (string, optionnel)   : 'status' ou 'offer' (défaut: 'status')
 *
 * L'utilisateur connecté est récupéré depuis $_SESSION['user_id']
 * (session créée par login.php de khaoulalaanait-coder)
 *
 * Schéma utilisé (Norhane) :
 *   posts : id, author_id, content, image_url, TYPE, created_at
 */
 
require_once __DIR__ . '/../../global/session_start.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
 
// ── Vérifier la méthode HTTP ───────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(array('success' => false, 'error' => 'Méthode non autorisée. Utilisez POST.'));
    exit;
}
 
// require_once __DIR__ . '/../../global/config/database.php'; (Already in session_start.php)
require_once __DIR__ . '/../../middleware/XSSProtection.php';
require_once __DIR__ . '/../../global/utils/UploadHandler.php';

// ── Connexion BDD ──────────────────────────────────────────────────────────────
try {
    $db = Database::connect();
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array('success' => false, 'error' => 'Erreur de connexion à la base de données.'));
    exit;
}

// ── Vérifier la session (utilisateur connecté) ─────────────────────────────────
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('success' => false, 'error' => 'Vous devez être connecté pour poster.'));
    exit;
}

$userId = (int) $_SESSION['user_id'];

// ── Validation & nettoyage XSS du contenu ─────────────────────────────────────
$rawContent = isset($_POST['content']) ? $_POST['content'] : '';
$validation = XSSProtection::validatePostContent($rawContent);

if (!$validation['valid']) {
    http_response_code(422);
    echo json_encode(array('success' => false, 'error' => $validation['error']));
    exit;
}

$content = $validation['content'];

// TYPE : 'status' ou 'offer'
$allowedTypes = array('status', 'offer');
$postType = isset($_POST['TYPE']) && in_array($_POST['TYPE'], $allowedTypes)
    ? $_POST['TYPE']
    : 'status';

// ── Gérer les fichiers (Uploads) ───────────────────────────────────────────────
$mediaPaths = [];
if (isset($_FILES['attachments'])) {
    try {
        $mediaPaths = UploadHandler::uploadMultiple($_FILES['attachments'], 'posts');
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(array('success' => false, 'error' => $e->getMessage()));
        exit;
    }
}

// ── Insertion en base de données ───────────────────────────────────────────────
try {
    $db->beginTransaction();

    $stmt = $db->prepare("
        INSERT INTO posts (author_id, content, TYPE, created_at)
        VALUES (:author_id, :content, :type, NOW())
    ");

    $stmt->execute(array(
        ':author_id' => $userId,
        ':content'   => $content,
        ':type'      => $postType,
    ));

    $newPostId = (int) $db->lastInsertId();

    // Insertion des medias
    if (!empty($mediaPaths)) {
        $stmtMedia = $db->prepare("INSERT INTO post_media (post_id, media_path, media_type) VALUES (?, ?, ?)");
        foreach ($mediaPaths as $path) {
            $type = str_contains(strtolower($path), 'mp4') || str_contains(strtolower($path), 'webm') ? 'video' : 'image';
            $stmtMedia->execute([$newPostId, $path, $type]);
        }
    }

    $db->commit();

    http_response_code(201);
    echo json_encode(array(
        'success'                    => true,
        'message'                    => 'Post créé avec succès.',
        'post_id'                    => $newPostId
    ));

} catch (PDOException $e) {
    if ($db->inTransaction()) $db->rollBack();
    http_response_code(500);
    echo json_encode(array('success' => false, 'error' => 'Erreur lors de la création du post: ' . $e->getMessage()));
}

<?php
require_once __DIR__ . '/../../global/session_start.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../../global/config/database.php';
require_once __DIR__ . '/../../global/utils/UploadHandler.php';

try {
    $userId = $_SESSION['user_id'];
    $db = Database::connect();
    
    // Handle Avatar Upload
    $avatarUrl = null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $avatarUrl = UploadHandler::uploadFile($_FILES['avatar'], 'avatars');
    }

    // Prepare data
    // FormData provides fields in $_POST
    $firstName = $_POST['first_name'] ?? null;
    $lastName = $_POST['last_name'] ?? null;
    $bio = $_POST['bio'] ?? null;
    $position = $_POST['position'] ?? null;
    $interests = $_POST['interests'] ?? null;
    $entryYear = $_POST['school_entry_year'] ?? null;
    $gradYear = $_POST['graduation_year'] ?? null;

    // Insert or Update profile row
    $sql = "INSERT INTO profiles (user_id, first_name, last_name, bio, position, interests, avatar_url, school_entry_year, graduation_year) 
            VALUES (:id, :fn, :ln, :bio, :pos, :ints, :av, :s_year, :g_year)
            ON DUPLICATE KEY UPDATE 
            first_name = :fn, 
            last_name = :ln, 
            bio = :bio, 
            position = :pos, 
            interests = :ints, 
            school_entry_year = :s_year,
            graduation_year = :g_year,
            avatar_url = IF(:av IS NOT NULL, :av, avatar_url)";
            
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':id' => $userId,
        ':fn' => $firstName,
        ':ln' => $lastName,
        ':bio' => $bio,
        ':pos' => $position,
        ':ints' => $interests,
        ':s_year' => $entryYear,
        ':g_year' => $gradYear,
        ':av' => $avatarUrl
    ]);

    echo json_encode(['status' => 'ok', 'message' => 'Profile updated successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

<?php
function validateToken($token) {
    require_once __DIR__ . '/../config/database.php';
    
    if (!$token) return false;
    
    $stmt = $pdo->prepare("SELECT user_id, school 
                           FROM users 
                           WHERE token = ?");
    $stmt->execute([$token]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
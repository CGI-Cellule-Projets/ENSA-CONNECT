<?php
require_once __DIR__ . '/../../global/config/database.php';
header('Content-Type: text/plain');
try {
    $db = Database::connect();
    echo "Connection OK\n";
    $p = $db->query("SELECT COUNT(*) FROM posts")->fetchColumn();
    $u = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    echo "Posts in DB: $p\n";
    echo "Users in DB: $u\n";
    
    $res = $db->query("SELECT p.id, p.author_id, p.category, u.username FROM posts p JOIN users u ON p.author_id = u.id")->fetchAll();
    echo "Sample Posts with Authors:\n";
    print_r($res);
    echo "\nSession User ID: " . ($_SESSION['user_id'] ?? 'Not set') . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

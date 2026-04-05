<?php
require_once __DIR__ . '/config/database.php';
try {
    $db = Database::connect();
    echo "Posts: " . $db->query("SELECT COUNT(*) FROM posts")->fetchColumn() . "\n";
    echo "Users: " . $db->query("SELECT COUNT(*) FROM users")->fetchColumn() . "\n";
    $p = $db->query("SELECT id, author_id, content, TYPE, category FROM posts LIMIT 5")->fetchAll();
    print_r($p);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>

<?php
require_once __DIR__ . '/config/database.php';
try {
    $db = Database::connect();
    echo "--- Table structure for reactions ---\n";
    $p = $db->query("DESCRIBE reactions")->fetchAll();
    print_r($p);
    echo "--- Indexes for reactions ---\n";
    $p = $db->query("SHOW INDEX FROM reactions")->fetchAll();
    print_r($p);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>

<?php
require_once __DIR__ . '/config/database.php';
try {
    $pdo = Database::connect();
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<?php
require_once __DIR__ . '/../../global/config/database.php';
header('Content-Type: text/plain');
try {
    $db = Database::connect();
    $q = $db->query("DESCRIBE posts");
    while($row = $q->fetch()) {
        print_r($row);
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}

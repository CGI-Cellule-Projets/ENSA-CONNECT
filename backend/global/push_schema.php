<?php
require_once __DIR__ . '/config/database.php';

try {
    $db = Database::connect();
    
    $sqlFile = __DIR__ . '/../../database.sql';
    if (!file_exists($sqlFile)) {
        die("Error: database.sql not found at $sqlFile\n");
    }

    $sql = file_get_contents($sqlFile);
    
    echo "Pushing schema to " . getenv('MYSQLHOST') . " (" . getenv('MYSQL_DATABASE') . ")...\n";
    
    // Execute the SQL
    // Note: This won't work for very large SQL files or those with complex delimiters (like triggers) 
    // but for the consolidated schema provided it's perfect.
    $db->exec($sql);
    
    echo "Schema successfully pushed!\n";
} catch (Exception $e) {
    die("Error Exception: " . $e->getMessage() . "\n");
}
?>

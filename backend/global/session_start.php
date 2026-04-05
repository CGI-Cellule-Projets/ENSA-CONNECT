<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/DbSessionHandler.php';

try {
    $db = Database::connect();
    $handler = new DbSessionHandler($db);
    session_set_save_handler($handler, true);
} catch (Exception $e) {
    // Fallback to default if DB is not available yet
}

if (session_status() === PHP_SESSION_NONE) {
    // Set cookie lifetime to 1 month
    session_set_cookie_params([
        'lifetime' => 2592000, 
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

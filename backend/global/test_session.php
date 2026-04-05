<?php
require_once __DIR__ . '/session_start.php';
$_SESSION['test_val'] = "Hello World";
session_write_close();

session_start();
echo "Session value: " . ($_SESSION['test_val'] ?? 'NOT FOUND') . "\n";
echo "Session DB table count: ";
$db = Database::connect();
$cnt = $db->query("SELECT COUNT(*) FROM sessions")->fetchColumn();
echo $cnt . "\n";
?>

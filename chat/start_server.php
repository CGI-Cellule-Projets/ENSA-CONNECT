<?php
require dirname(__DIR__) . '/vendor/autoload.php';
require __DIR__ . '/token_validator.php';
require __DIR__ . '/PulseHandler.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new PulseHandler()
        )
    ),
    8080
);

echo "🚀 WebSocket server running on port 8080...\n";
$server->run();
<?php
require_once __DIR__ . '/token_validator.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class PulseHandler implements MessageComponentInterface {
    
    protected $channels = [];
    protected $connUsers = [];

    public function onOpen(ConnectionInterface $conn) {
        // Get token from URL: ws://server?token=xxx
        $query = $conn->httpRequest->getUri()->getQuery();
        parse_str($query, $params);
        $token = $params['token'] ?? null;

        // Validate token
        $user = validateToken($token);
        if (!$user) {
            $conn->send(json_encode([
                'error' => 'Unauthorized'
            ]));
            $conn->close();
            return;
        }

        // Store user info
        $this->connUsers[$conn->resourceId] = $user;

        // Add to their school channel
        $school = $user['school'];
        $this->channels[$school][$conn->resourceId] = $conn;

        echo "✅ New connection: user {$user['user_id']} in channel {$school}\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $user = $this->connUsers[$from->resourceId];
        $school = $user['school'];

        $data = json_decode($msg, true);
        
        $broadcast = json_encode([
            'from'    => $user['user_id'],
            'message' => $data['message'],
            'school'  => $school
        ]);

        // Send ONLY to users in the same school channel
        foreach ($this->channels[$school] as $conn) {
            if ($conn !== $from) {
                $conn->send($broadcast);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $user = $this->connUsers[$conn->resourceId] ?? null;
        if ($user) {
            $school = $user['school'];
            unset($this->channels[$school][$conn->resourceId]);
            unset($this->connUsers[$conn->resourceId]);
            echo "❌ Connection closed: user {$user['user_id']}\n";
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "⚠️ Error: {$e->getMessage()}\n";
        $conn->close();
    }
}
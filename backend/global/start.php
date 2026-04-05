<?php
echo "Starting...\n";
// Start Chat (WebSocket) server on 9090 explicitly to avoid clashing with Web Server (4000)
shell_exec('PORT=9090 php backend/pages/chat/bin/chat-server.php > /dev/null 2>&1 &');
sleep(1);
echo "Running web server on 4000\n";
passthru("php -S 0.0.0.0:4000");
?>

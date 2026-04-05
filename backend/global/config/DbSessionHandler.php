<?php
class DbSessionHandler implements SessionHandlerInterface {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function open($savePath, $sessionName): bool {
        return true;
    }

    public function close(): bool {
        return true;
    }

    public function read($id): string|false {
        $stmt = $this->db->prepare("SELECT data FROM sessions WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? $row['data'] : '';
    }

    public function write($id, $data): bool {
        $stmt = $this->db->prepare("REPLACE INTO sessions (id, data, timestamp) VALUES (:id, :data, :ts)");
        return $stmt->execute([':id' => $id, ':data' => $data, ':ts' => time()]);
    }

    public function destroy($id): bool {
        $stmt = $this->db->prepare("DELETE FROM sessions WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function gc($maxLifetime): int|false {
        $old = time() - $maxLifetime;
        $stmt = $this->db->prepare("DELETE FROM sessions WHERE timestamp < :old");
        $stmt->execute([':old' => $old]);
        return $stmt->rowCount();
    }
}

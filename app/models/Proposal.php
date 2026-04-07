<?php
class Proposal extends Model {
    protected $table = 'proposals';

    private const VALID_STATUSES = ['pending', 'approved', 'rejected'];

    private function ensureTable() {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS {$this->table} (
                id INT(11) NOT NULL AUTO_INCREMENT,
                client_name VARCHAR(255) DEFAULT NULL,
                project_name VARCHAR(255) DEFAULT NULL,
                amount DECIMAL(10,2) DEFAULT NULL,
                status ENUM('pending','approved','rejected') DEFAULT 'pending',
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY idx_status (status),
                KEY idx_created_at (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");
        $this->db->execute();
    }

    public function getAll() {
        $this->ensureTable();

        $this->db->query("
            SELECT id, client_name, project_name, amount, status, created_at
            FROM {$this->table}
            ORDER BY created_at DESC, id DESC
        ");

        return $this->db->fetchAll() ?: [];
    }

    public function getLatest($limit = 10) {
        $this->ensureTable();

        $this->db->query("
            SELECT id, client_name, project_name, amount, status, created_at
            FROM {$this->table}
            ORDER BY created_at DESC, id DESC
            LIMIT :limit
        ");
        $this->db->bind(':limit', max(1, (int) $limit), PDO::PARAM_INT);

        return $this->db->fetchAll() ?: [];
    }

    public function findById($id) {
        $this->ensureTable();

        $this->db->query("
            SELECT id, client_name, project_name, amount, status, created_at
            FROM {$this->table}
            WHERE id = :id
            LIMIT 1
        ");
        $this->db->bind(':id', (int) $id, PDO::PARAM_INT);

        return $this->db->fetch() ?: null;
    }

    public function create(array $data) {
        $this->ensureTable();

        $status = strtolower(trim((string) ($data['status'] ?? 'pending')));
        if (!in_array($status, self::VALID_STATUSES, true)) {
            $status = 'pending';
        }

        $this->db->query("
            INSERT INTO {$this->table} (client_name, project_name, amount, status)
            VALUES (:client_name, :project_name, :amount, :status)
        ");
        $this->db->bind(':client_name', $data['client_name']);
        $this->db->bind(':project_name', $data['project_name']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':status', $status);
        $this->db->execute();

        return (int) $this->db->lastInsertId();
    }

    public function updateById($id, array $data) {
        $this->ensureTable();

        $status = strtolower(trim((string) ($data['status'] ?? 'pending')));
        if (!in_array($status, self::VALID_STATUSES, true)) {
            $status = 'pending';
        }

        $this->db->query("
            UPDATE {$this->table}
            SET client_name = :client_name,
                project_name = :project_name,
                amount = :amount,
                status = :status
            WHERE id = :id
        ");
        $this->db->bind(':client_name', $data['client_name']);
        $this->db->bind(':project_name', $data['project_name']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':status', $status);
        $this->db->bind(':id', (int) $id, PDO::PARAM_INT);

        return $this->db->execute();
    }

    public function getSummary() {
        $this->ensureTable();

        $this->db->query("
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN status IN ('pending', 'approved') THEN 1 ELSE 0 END) AS active,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) AS approved,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) AS rejected
            FROM {$this->table}
        ");

        $summary = $this->db->fetch() ?: [];

        return [
            'total' => (int) ($summary['total'] ?? 0),
            'active' => (int) ($summary['active'] ?? 0),
            'pending' => (int) ($summary['pending'] ?? 0),
            'approved' => (int) ($summary['approved'] ?? 0),
            'rejected' => (int) ($summary['rejected'] ?? 0),
        ];
    }

    public function getAdminRecipients() {
        $this->db->query("
            SELECT id
            FROM users
            WHERE role_id IN (1, 2) AND status = 'active'
        ");

        return $this->db->fetchAll() ?: [];
    }
}

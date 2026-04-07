<?php
class AdminDocument extends Model {
    protected $table = 'admin_documents';

    private function ensureTable() {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS {$this->table} (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                original_name VARCHAR(255) NOT NULL,
                stored_name VARCHAR(255) NOT NULL,
                file_path VARCHAR(500) NOT NULL,
                mime_type VARCHAR(150) DEFAULT NULL,
                file_size INT UNSIGNED DEFAULT NULL,
                uploaded_by INT DEFAULT NULL,
                proposal_id INT DEFAULT NULL,
                project_name VARCHAR(255) DEFAULT NULL,
                source_module VARCHAR(100) NOT NULL DEFAULT 'bd',
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY idx_source_module (source_module),
                KEY idx_uploaded_by (uploaded_by),
                KEY idx_proposal_id (proposal_id),
                KEY idx_created_at (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");
        $this->db->execute();

        $this->ensureColumn('proposal_id', "ALTER TABLE {$this->table} ADD COLUMN proposal_id INT DEFAULT NULL AFTER uploaded_by");
        $this->ensureColumn('project_name', "ALTER TABLE {$this->table} ADD COLUMN project_name VARCHAR(255) DEFAULT NULL AFTER proposal_id");
    }

    private function ensureColumn($columnName, $alterSql) {
        $safeColumnName = str_replace("'", "''", $columnName);
        $this->db->query("SHOW COLUMNS FROM {$this->table} LIKE '{$safeColumnName}'");
        $column = $this->db->fetch();

        if (!$column) {
            $this->db->query($alterSql);
            $this->db->execute();
        }
    }

    public function createDocument($data) {
        $this->ensureTable();

        $this->db->query("
            INSERT INTO {$this->table}
            (original_name, stored_name, file_path, mime_type, file_size, uploaded_by, proposal_id, project_name, source_module)
            VALUES (:original_name, :stored_name, :file_path, :mime_type, :file_size, :uploaded_by, :proposal_id, :project_name, :source_module)
        ");
        $this->db->bind(':original_name', $data['original_name']);
        $this->db->bind(':stored_name', $data['stored_name']);
        $this->db->bind(':file_path', $data['file_path']);
        $this->db->bind(':mime_type', $data['mime_type']);
        $this->db->bind(':file_size', $data['file_size']);
        $this->db->bind(':uploaded_by', $data['uploaded_by']);
        $this->db->bind(':proposal_id', $data['proposal_id'] ?? null);
        $this->db->bind(':project_name', $data['project_name'] ?? null);
        $this->db->bind(':source_module', $data['source_module'] ?? 'bd');

        return $this->db->execute();
    }

    public function getRecentDocuments($limit = 10, $sourceModule = null) {
        $this->ensureTable();

        $sql = "
            SELECT ad.*, u.first_name, u.last_name
            FROM {$this->table} ad
            LEFT JOIN users u ON u.id = ad.uploaded_by
        ";

        if ($sourceModule !== null) {
            $sql .= " WHERE ad.source_module = :source_module";
        }

        $sql .= " ORDER BY ad.created_at DESC LIMIT :limit";

        $this->db->query($sql);

        if ($sourceModule !== null) {
            $this->db->bind(':source_module', $sourceModule);
        }

        $this->db->bind(':limit', (int) $limit, PDO::PARAM_INT);
        return $this->db->fetchAll();
    }

    public function findDocumentById($id) {
        $this->ensureTable();

        $this->db->query("
            SELECT ad.*, u.first_name, u.last_name
            FROM {$this->table} ad
            LEFT JOIN users u ON u.id = ad.uploaded_by
            WHERE ad.id = :id
            LIMIT 1
        ");
        $this->db->bind(':id', (int) $id, PDO::PARAM_INT);

        return $this->db->fetch();
    }
}

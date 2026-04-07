<?php
class LeadModel extends Model {
    protected $table = 'leads';
    private $columnCache = null;

    public function getColumns() {
        if ($this->columnCache !== null) {
            return $this->columnCache;
        }

        $this->db->query("SHOW COLUMNS FROM {$this->table}");
        $columns = $this->db->fetchAll();
        $this->columnCache = is_array($columns) ? array_column($columns, 'Field') : [];

        return $this->columnCache;
    }

    public function createLead($data) {
        $columns = $this->getColumns();

        $fieldMap = [
            'company_name' => $data['company_name'] ?? null,
            'contact_person' => $data['contact_person'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'status' => $data['status'] ?? null,
            'created_at' => $data['created_at'] ?? null,
        ];

        $insertFields = [];
        $placeholders = [];

        foreach ($fieldMap as $field => $value) {
            if (in_array($field, $columns, true)) {
                $insertFields[] = $field;
                $placeholders[] = ':' . $field;
            }
        }

        if (empty($insertFields)) {
            throw new RuntimeException('No compatible columns were found in the leads table.');
        }

        $this->db->query(
            "INSERT INTO {$this->table} (" . implode(', ', $insertFields) . ")
             VALUES (" . implode(', ', $placeholders) . ")"
        );

        foreach ($insertFields as $field) {
            $this->db->bind(':' . $field, $fieldMap[$field]);
        }

        return $this->db->execute();
    }
}

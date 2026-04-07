<?php
class EmployeeDocument extends Model {
    protected $table = 'employee_documents';

    public function getByEmployee($employee_id) {
        $this->db->query("SELECT d.*, u.first_name as uploaded_by_name 
                          FROM {$this->table} d
                          JOIN users u ON d.uploaded_by = u.id
                          WHERE d.employee_id = :emp_id ORDER BY d.uploaded_at DESC");
        $this->db->bind(':emp_id', $employee_id);
        return $this->db->fetchAll();
    }

    public function upload($employee_id, $type, $fileName, $filePath, $uploaded_by) {
        $this->db->query("INSERT INTO {$this->table} (employee_id, document_type, file_name, file_path, uploaded_by) 
                          VALUES (:eid, :type, :fname, :fpath, :uid)");
        $this->db->bind(':eid', $employee_id);
        $this->db->bind(':type', $type);
        $this->db->bind(':fname', $fileName);
        $this->db->bind(':fpath', $filePath);
        $this->db->bind(':uid', $uploaded_by);
        return $this->db->execute();
    }
}
<?php
class Employee extends Model {
    
    public function getAllDetailed($filters = []) {
        $sql = "SELECT e.*, u.first_name, u.last_name, u.email, u.phone, u.status, u.avatar, 
                       d.name as department_name, des.name as designation_name,
                       m.first_name as manager_first, m.last_name as manager_last,
                       r.name as role_name
                FROM employees e
                JOIN users u ON e.user_id = u.id
                LEFT JOIN departments d ON e.department_id = d.id
                LEFT JOIN designations des ON e.designation_id = des.id
                LEFT JOIN users m ON e.manager_id = m.id
                LEFT JOIN roles r ON u.role_id = r.id
                WHERE u.deleted_at IS NULL";
        
        // Dynamic AJAX filtering
        if (!empty($filters['department_id'])) {
            $sql .= " AND e.department_id = " . intval($filters['department_id']);
        }
        if (!empty($filters['status'])) {
            $sql .= " AND u.status = '" . sanitize($filters['status']) . "'";
        }
        $sql .= " ORDER BY u.created_at DESC";
        
        $this->db->query($sql);
        return $this->db->fetchAll();
    }

    public function createFullEmployee($userData, $employeeData) {
        try {
            $this->db->beginTransaction();

            // 1. Insert User Credentials
            $this->db->query("INSERT INTO users (role_id, first_name, last_name, email, phone, password_hash, status) 
                              VALUES (:role_id, :fname, :lname, :email, :phone, :pass, :status)");
            $this->db->bind(':role_id', $userData['role_id']);
            $this->db->bind(':fname', $userData['first_name']);
            $this->db->bind(':lname', $userData['last_name']);
            $this->db->bind(':email', $userData['email']);
            $this->db->bind(':phone', $userData['phone']);
            $this->db->bind(':pass', password_hash($userData['password'], PASSWORD_BCRYPT));
            $this->db->bind(':status', $userData['status']);
            $this->db->execute();
            
            $user_id = $this->db->lastInsertId();

            // 2. Insert HR Employee Record
            $this->db->query("INSERT INTO employees (user_id, department_id, designation_id, manager_id, join_date, monthly_salary, emergency_contact) 
                              VALUES (:uid, :dept_id, :desig_id, :mgr_id, :join_date, :salary, :emergency)");
            $this->db->bind(':uid', $user_id);
            $this->db->bind(':dept_id', $employeeData['department_id']);
            $this->db->bind(':desig_id', $employeeData['designation_id']);
            $this->db->bind(':mgr_id', $employeeData['manager_id']);
            $this->db->bind(':join_date', $employeeData['join_date']);
            $this->db->bind(':salary', $employeeData['monthly_salary']);
            $this->db->bind(':emergency', $employeeData['emergency_contact']);
            $this->db->execute();

            $this->db->commit();
            return $user_id;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Employee Creation Failed: " . $e->getMessage());
            return false;
        }
    }

    public function getProfile($user_id) {
        $this->db->query("SELECT e.*, u.*, d.name as department_name, des.name as designation_name, 
                                 m.first_name as manager_first, m.last_name as manager_last, r.name as role_name
                          FROM employees e
                          JOIN users u ON e.user_id = u.id
                          LEFT JOIN departments d ON e.department_id = d.id
                          LEFT JOIN designations des ON e.designation_id = des.id
                          LEFT JOIN users m ON e.manager_id = m.id
                          LEFT JOIN roles r ON u.role_id = r.id
                          WHERE u.id = :uid AND u.deleted_at IS NULL");
        $this->db->bind(':uid', $user_id);
        return $this->db->fetch();
    }
}
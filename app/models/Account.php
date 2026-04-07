<?php
class Account extends Model {
    
    /**
     * Retrieves the current cryptographic hash for the user
     */
    public function getPasswordHash($user_id) {
        $this->db->query("SELECT password FROM users WHERE id = :id");
        $this->db->bind(':id', $user_id);
        $result = $this->db->fetch();
        return $result ? $result['password'] : null;
    }

    /**
     * Safely updates the user's password hash in the database
     */
    public function updatePassword($user_id, $new_hashed_password) {
        $this->db->query("UPDATE users SET password = :pass WHERE id = :id");
        $this->db->bind(':pass', $new_hashed_password);
        $this->db->bind(':id', $user_id);
        return $this->db->execute();
    }
}
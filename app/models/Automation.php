<?php
class Attendance extends Model {
    protected $table = 'attendance';

    public function clockIn($user_id) {
        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        
        // Failsafe: Check if already clocked in today
        $this->db->query("SELECT id FROM {$this->table} WHERE user_id = :uid AND date = :date");
        $this->db->bind(':uid', $user_id);
        $this->db->bind(':date', $date);
        if ($this->db->fetch()) return false;

        $this->db->query("INSERT INTO {$this->table} (user_id, date, clock_in) VALUES (:uid, :date, :time)");
        $this->db->bind(':uid', $user_id);
        $this->db->bind(':date', $date);
        $this->db->bind(':time', $time);
        return $this->db->execute();
    }

    public function clockOut($user_id) {
        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        
        $this->db->query("UPDATE {$this->table} SET clock_out = :time WHERE user_id = :uid AND date = :date");
        $this->db->bind(':uid', $user_id);
        $this->db->bind(':date', $date);
        $this->db->bind(':time', $time);
        return $this->db->execute();
    }
}
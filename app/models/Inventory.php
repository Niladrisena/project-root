<?php
class Inventory extends Model {
    protected $table = 'inventory';

    public function getLowStockAlerts() {
        $this->db->query("SELECT i.*, w.name as warehouse_name 
                          FROM {$this->table} i 
                          JOIN warehouses w ON i.warehouse_id = w.id 
                          WHERE i.quantity <= i.min_stock_level AND i.status != 'inactive'");
        return $this->db->fetchAll();
    }

    public function recordMovement($inventory_id, $warehouse_id, $type, $qty, $ref_id, $notes, $logged_by) {
        try {
            $this->db->beginTransaction();

            // 1. Update Master Stock
            $operator = ($type === 'in' || $type === 'adjustment') ? '+' : '-';
            $this->db->query("UPDATE {$this->table} SET quantity = quantity {$operator} :qty WHERE id = :id");
            $this->db->bind(':qty', $qty);
            $this->db->bind(':id', $inventory_id);
            $this->db->execute();

            // 2. Log Movement
            $this->db->query("INSERT INTO stock_movements (inventory_id, warehouse_id, movement_type, quantity, reference_id, notes, logged_by) 
                              VALUES (:iid, :wid, :type, :qty, :ref, :notes, :uid)");
            $this->db->bind(':iid', $inventory_id);
            $this->db->bind(':wid', $warehouse_id);
            $this->db->bind(':type', $type);
            $this->db->bind(':qty', $qty);
            $this->db->bind(':ref', $ref_id);
            $this->db->bind(':notes', $notes);
            $this->db->bind(':uid', $logged_by);
            $this->db->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
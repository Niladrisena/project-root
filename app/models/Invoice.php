<?php
class Invoice extends Model {
    protected $table = 'invoices';

    public function createInvoice($data, $items) {
        try {
            $this->db->beginTransaction();

            // 1. Create Main Invoice Record
            $this->db->query("INSERT INTO {$this->table} (project_id, client_id, invoice_number, amount, currency_id, status, due_date) 
                              VALUES (:pid, :cid, :inv_num, :amount, :cur_id, :status, :due)");
            $this->db->bind(':pid', $data['project_id']);
            $this->db->bind(':cid', $data['client_id']);
            $this->db->bind(':inv_num', $data['invoice_number']);
            $this->db->bind(':amount', $data['total_amount']);
            $this->db->bind(':cur_id', $data['currency_id']);
            $this->db->bind(':status', 'draft');
            $this->db->bind(':due', $data['due_date']);
            $this->db->execute();
            
            $invoice_id = $this->db->lastInsertId();

            // 2. Insert Invoice Items
            foreach ($items as $item) {
                $this->db->query("INSERT INTO invoice_items (invoice_id, description, quantity, unit_price, tax_percent, discount_amount, total_amount) 
                                  VALUES (:inv_id, :desc, :qty, :price, :tax, :disc, :total)");
                $this->db->bind(':inv_id', $invoice_id);
                $this->db->bind(':desc', sanitize($item['description']));
                $this->db->bind(':qty', $item['quantity']);
                $this->db->bind(':price', $item['unit_price']);
                $this->db->bind(':tax', $item['tax_percent']);
                $this->db->bind(':disc', $item['discount_amount']);
                $this->db->bind(':total', $item['total_amount']);
                $this->db->execute();
            }

            $this->db->commit();
            return $invoice_id;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Invoice Creation Failed: " . $e->getMessage());
            return false;
        }
    }

    public function getInvoiceDetails($id) {
        $this->db->query("SELECT i.*, c.company_name, c.address, c.email, p.name as project_name, curr.symbol, curr.code 
                          FROM {$this->table} i 
                          JOIN clients c ON i.client_id = c.id 
                          LEFT JOIN projects p ON i.project_id = p.id
                          JOIN currencies curr ON i.currency_id = curr.id
                          WHERE i.id = :id");
        $this->db->bind(':id', $id);
        $invoice = $this->db->fetch();

        if ($invoice) {
            $this->db->query("SELECT * FROM invoice_items WHERE invoice_id = :id");
            $this->db->bind(':id', $id);
            $invoice['items'] = $this->db->fetchAll();
            
            $this->db->query("SELECT * FROM payments WHERE invoice_id = :id ORDER BY payment_date DESC");
            $this->db->bind(':id', $id);
            $invoice['payments'] = $this->db->fetchAll();
        }

        return $invoice;
    }
}
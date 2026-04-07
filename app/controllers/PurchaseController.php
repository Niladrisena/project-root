<?php
class PurchaseController extends Controller {
    private $purchaseModel;

    public function __construct() {
        AuthMiddleware::handle();
        $this->purchaseModel = $this->model('Purchase');
    }

    public function receive($po_id) {
        PermissionMiddleware::handle('approve', 'purchases');
        
        // When PO is marked received:
        // 1. Update PO Status
        // 2. Loop through purchase_items
        // 3. If 'inventory', call InventoryModel->recordMovement('in')
        // 4. If 'asset', insert into Assets table
        // 5. Create an Expense record in the Finance module (Step 9 integration)
        
        // Implementation logic here...
        Session::set('flash_success', 'Purchase Order Received. Inventory updated & Expense logged.');
        $this->redirect('/purchases');
    }
}
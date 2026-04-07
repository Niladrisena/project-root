<?php
class InvoiceController extends Controller {
    private $invoiceModel;

    public function __construct() {
        AuthMiddleware::handle();
        $this->invoiceModel = $this->model('Invoice');
    }

    public function create() {
        PermissionMiddleware::handle('create', 'invoices');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token']);
            
            // Generate Invoice Number
            $inv_num = 'INV-' . date('Ymd') . '-' . rand(100, 999);
            
            $data = [
                'client_id' => $_POST['client_id'],
                'project_id' => $_POST['project_id'] ?: null,
                'currency_id' => $_POST['currency_id'],
                'invoice_number' => $inv_num,
                'due_date' => $_POST['due_date'],
                'total_amount' => $_POST['grand_total'] // Calculated securely below in real app, simplified here
            ];

            // Reconstruct items from arrays
            $items = [];
            for($i = 0; $i < count($_POST['item_desc']); $i++) {
                if(!empty($_POST['item_desc'][$i])) {
                    $qty = floatval($_POST['item_qty'][$i]);
                    $price = floatval($_POST['item_price'][$i]);
                    $tax = floatval($_POST['item_tax'][$i]);
                    
                    $subtotal = $qty * $price;
                    $tax_amount = $subtotal * ($tax / 100);
                    $total = $subtotal + $tax_amount;

                    $items[] = [
                        'description' => $_POST['item_desc'][$i],
                        'quantity' => $qty,
                        'unit_price' => $price,
                        'tax_percent' => $tax,
                        'discount_amount' => 0,
                        'total_amount' => $total
                    ];
                }
            }

            // Backend validation of total
            $calculated_grand_total = array_sum(array_column($items, 'total_amount'));
            $data['total_amount'] = $calculated_grand_total;

            $invoice_id = $this->invoiceModel->createInvoice($data, $items);
            
            if ($invoice_id) {
                Session::set('flash_success', 'Invoice Created Successfully.');
                $this->redirect('/invoice/view/' . $invoice_id);
            }
        }
        
        // Load clients, currencies, taxes for the view...
        $this->view('layouts/main', ['view_content' => 'invoice/create', 'title' => 'Create Invoice']);
    }

    public function view($id) {
        PermissionMiddleware::handle('view', 'invoices');
        $invoice = $this->invoiceModel->getInvoiceDetails($id);
        
        if(!$invoice) $this->redirect('/finance/dashboard');

        $this->view('layouts/main', [
            'view_content' => 'invoice/view',
            'invoice' => $invoice,
            'title' => 'Invoice ' . $invoice['invoice_number']
        ]);
    }
}
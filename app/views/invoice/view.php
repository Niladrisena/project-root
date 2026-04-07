<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-10 print:shadow-none print:border-0">
    <div class="flex justify-between items-start border-b border-gray-200 pb-8 mb-8">
        <div>
            <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xl mb-4">E</div>
            <h1 class="text-3xl font-bold text-gray-900">INVOICE</h1>
            <p class="text-sm text-gray-500 mt-1">#<?= sanitize($invoice['invoice_number']) ?></p>
        </div>
        <div class="text-right">
            <h2 class="text-lg font-bold text-gray-900">Enterprise SaaS Corp.</h2>
            <p class="text-sm text-gray-500 mt-1">123 Business Avenue<br>Tech District, NY 10001<br>contact@enterprise.com</p>
        </div>
    </div>

    <div class="flex justify-between mb-8">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Billed To:</p>
            <h3 class="text-lg font-bold text-gray-900"><?= sanitize($invoice['company_name']) ?></h3>
            <p class="text-sm text-gray-600 mt-1"><?= nl2br(sanitize($invoice['address'])) ?><br><?= sanitize($invoice['email']) ?></p>
        </div>
        <div class="text-right">
            <div class="mb-4">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Date Issued:</p>
                <p class="text-sm font-medium text-gray-900"><?= date('M d, Y', strtotime($invoice['created_at'])) ?></p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Due Date:</p>
                <p class="text-sm font-medium text-gray-900"><?= date('M d, Y', strtotime($invoice['due_date'])) ?></p>
            </div>
        </div>
    </div>

    <table class="w-full text-left mb-8">
        <thead>
            <tr class="border-y border-gray-200">
                <th class="py-3 text-sm font-bold text-gray-900">Description</th>
                <th class="py-3 text-sm font-bold text-gray-900 text-center">Qty</th>
                <th class="py-3 text-sm font-bold text-gray-900 text-right">Price</th>
                <th class="py-3 text-sm font-bold text-gray-900 text-right">Tax</th>
                <th class="py-3 text-sm font-bold text-gray-900 text-right">Amount</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php foreach($invoice['items'] as $item): ?>
            <tr>
                <td class="py-4 text-sm text-gray-800"><?= sanitize($item['description']) ?></td>
                <td class="py-4 text-sm text-gray-800 text-center"><?= $item['quantity'] ?></td>
                <td class="py-4 text-sm text-gray-800 text-right"><?= $invoice['symbol'] ?><?= number_format($item['unit_price'], 2) ?></td>
                <td class="py-4 text-sm text-gray-800 text-right"><?= $item['tax_percent'] ?>%</td>
                <td class="py-4 text-sm text-gray-900 font-medium text-right"><?= $invoice['symbol'] ?><?= number_format($item['total_amount'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="flex justify-end border-t border-gray-200 pt-8">
        <div class="w-64 space-y-3">
            <div class="flex justify-between text-sm">
                <span class="font-medium text-gray-500">Subtotal</span>
                <span class="font-medium text-gray-900">--</span> </div>
            <div class="flex justify-between text-sm">
                <span class="font-medium text-gray-500">Total Tax</span>
                <span class="font-medium text-gray-900">--</span>
            </div>
            <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-3">
                <span class="text-gray-900">Total Due</span>
                <span class="text-blue-600"><?= $invoice['symbol'] ?><?= number_format($invoice['amount'], 2) ?> <?= $invoice['code'] ?></span>
            </div>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto mt-6 flex justify-end gap-4 print:hidden">
    <button onclick="window.print()" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg transition-colors">Print / Save PDF</button>
    <?php if(Auth::can('payment_receive', 'finance')): ?>
    <button class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">Record Payment</button>
    <?php endif; ?>
</div>
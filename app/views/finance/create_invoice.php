<div class="max-w-4xl mx-auto pb-12">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Invoice</h1>
            <p class="text-sm text-gray-500">Generate a new billing invoice for a client.</p>
        </div>
        <a href="<?= base_url('/finance') ?>" class="text-sm font-medium text-gray-600 bg-white border border-gray-300 px-4 py-2 rounded-lg shadow-sm">Back</a>
    </div>

    <form action="<?= base_url('/finance/create_invoice') ?>" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Client <span class="text-red-500">*</span></label>
                <select name="client_id" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 outline-none bg-white">
                    <?php if(!empty($clients)): ?>
                        <?php foreach($clients as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= sanitize($c['company_name'] ?? $c['name'] ?? 'Client #'.$c['id']) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="1">⚠️ Default Client (ID: 1)</option>
                    <?php endif; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Project <span class="text-red-500">*</span></label>
                <select name="project_id" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 outline-none bg-white">
                    <?php if(!empty($projects)): ?>
                        <?php foreach($projects as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= sanitize($p['name'] ?? 'Project #'.$p['id']) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="1">⚠️ Default Project (ID: 1)</option>
                    <?php endif; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Currency <span class="text-red-500">*</span></label>
                <select name="currency_id" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 outline-none bg-white">
                    <?php if(!empty($currencies)): ?>
                        <?php foreach($currencies as $curr): ?>
                            <option value="<?= $curr['id'] ?>" <?= ($curr['code'] == 'USD') ? 'selected' : '' ?>>
                                <?= sanitize($curr['code']) ?> (<?= sanitize($curr['symbol']) ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="1">USD ($) Fallback</option>
                    <?php endif; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Due Date <span class="text-red-500">*</span></label>
                <input type="date" name="due_date" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 outline-none text-gray-700">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-1">Invoice Description / Items <span class="text-red-500">*</span></label>
                <input type="text" name="description" required placeholder="e.g., Website Development Phase 1" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <div class="md:col-span-1">
                <label class="block text-sm font-bold text-gray-700 mb-1">Total Amount <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500">$</span>
                    <input type="number" step="0.01" name="amount" required placeholder="0.00" class="w-full pl-8 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-6 flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 shadow-sm transition">
                Generate & Save Invoice
            </button>
        </div>
    </form>
</div>
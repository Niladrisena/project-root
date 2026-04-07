<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 bg-gradient-to-r from-emerald-900 to-teal-900 rounded-2xl p-8 shadow-lg text-white">
        <div>
            <p class="text-emerald-200 text-sm font-bold uppercase tracking-wider mb-1">Corporate Ledger</p>
            <h1 class="text-3xl font-black tracking-tight">Transaction History 📊</h1>
            <p class="text-emerald-100 mt-2 text-sm">Monitor all incoming revenue and outgoing operational expenses.</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= base_url('/finance/dashboard') ?>" class="px-5 py-2.5 bg-white/10 border border-white/20 text-white font-bold rounded-xl shadow-sm hover:bg-white/20 transition backdrop-blur-sm">
                &larr; Back to Hub
            </a>
            <a href="<?= base_url('/finance/log_transaction') ?>" class="px-6 py-3 bg-white text-teal-900 hover:bg-gray-50 font-black rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Log Entry
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-900">All Transactions</h3>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-green-100 text-green-800 text-[10px] font-black uppercase rounded-lg">Income</span>
                <span class="px-3 py-1 bg-red-100 text-red-800 text-[10px] font-black uppercase rounded-lg">Expense</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Reference / Desc</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if(empty($transactions)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <p class="text-gray-500 font-bold">No transactions found.</p>
                                <p class="text-sm text-gray-400 mt-1">Income and expense logs will appear here.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($transactions as $t): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm font-bold text-gray-700">
                                    <?= date('M d, Y', strtotime($t['transaction_date'])) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900"><?= sanitize($t['reference_no'] ?? 'N/A') ?></p>
                                    <p class="text-[11px] text-gray-500 mt-0.5 line-clamp-1 max-w-xs"><?= sanitize($t['description']) ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 bg-gray-100 border border-gray-200 text-gray-600 text-[10px] font-black uppercase tracking-wider rounded">
                                        <?= sanitize($t['category']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <?php if($t['type'] === 'income'): ?>
                                        <span class="text-green-600 font-black text-sm">+ $<?= number_format((float)$t['amount'], 2) ?></span>
                                    <?php else: ?>
                                        <span class="text-red-600 font-black text-sm">- $<?= number_format((float)$t['amount'], 2) ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
    // 🚀 FAULT-TOLERANT ARRAY MAPPING
    // Safely supports both legacy and modern database keys to prevent Fatal Errors
    $revenue = $metrics['revenue'] ?? $metrics['total_revenue'] ?? 0;
    $expenses = $metrics['expenses'] ?? $metrics['total_expenses'] ?? 0;
    $net_profit = $metrics['net_profit'] ?? ($revenue - $expenses);
    $pending = $metrics['pending_invoices'] ?? 0;
    
    // 🚀 SAFE GLOBAL CURRENCY EXTRACTION
    $sys_curr = defined('SYS_CURRENCY') ? SYS_CURRENCY : '$';
?>
<div class="max-w-7xl mx-auto space-y-6 pb-12">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-200 pb-5">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Finance & Accounts</h1>
            <p class="text-sm text-gray-500 mt-1">Manage corporate revenue, expenses, and client invoicing for <span class="font-bold text-gray-700"><?= defined('SYS_COMPANY_NAME') ? SYS_COMPANY_NAME : 'Enterprise' ?></span>.</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= base_url('/finance/report') ?>" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg shadow-sm hover:bg-gray-50 transition text-sm flex items-center justify-center">
                Generate Report
            </a>
            <a href="<?= base_url('/finance/create_invoice') ?>" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-sm transition flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create Invoice
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Revenue</h3>
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-gray-900"><?= $sys_curr ?><?= number_format($revenue, 2) ?></p>
            <p class="text-xs font-medium text-green-600 mt-2 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                +0.0% from last month
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Expenses</h3>
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-gray-900"><?= $sys_curr ?><?= number_format($expenses, 2) ?></p>
            <p class="text-xs font-medium text-gray-400 mt-2 flex items-center gap-1">
                Awaiting expense logs
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Net Profit</h3>
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-gray-900"><?= $sys_curr ?><?= number_format($net_profit, 2) ?></p>
            <p class="text-xs font-medium text-gray-400 mt-2 flex items-center gap-1">
                Calculated Year-to-Date
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Pending Invoices</h3>
                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-gray-900"><?= $pending ?></p>
            <p class="text-xs font-medium text-orange-600 mt-2 flex items-center gap-1">
                Action required
            </p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-8">
        <div class="p-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Recent Transactions & Invoices</h3>
            <a href="<?= base_url('/finance/transactions') ?>" class="text-sm font-bold text-blue-600 hover:text-blue-800">View All</a>
        </div>

        <?php if(empty($transactions)): ?>
            <div class="p-12 text-center flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mb-4 border-2 border-dashed border-gray-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">No Financial Data</h3>
                <p class="text-sm text-gray-500 mb-6">Create your first invoice or log an expense to see activity here.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Client ID</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Amount (<?= $sys_curr ?>)</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Due Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach($transactions as $tx): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-blue-600"><?= sanitize($tx['invoice_number']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-500">Client #<?= sanitize($tx['client_id']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-gray-900"><?= $sys_curr ?><?= number_format($tx['amount'], 2) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-orange-100 text-orange-800 uppercase tracking-wider">
                                    <?= sanitize($tx['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-500">
                                <?= date('M d, Y', strtotime($tx['due_date'])) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</div>
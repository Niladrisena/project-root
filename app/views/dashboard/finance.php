<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 bg-gradient-to-r from-emerald-900 to-teal-900 rounded-2xl p-8 shadow-lg text-white">
        <div>
            <p class="text-sm font-medium"><?= date('l, F j, Y | h:i A') ?></p>
            <h1 class="text-3xl font-black tracking-tight">Finance Command Center 🏦</h1>
            <p class="text-emerald-100 mt-2 text-sm">Oversee corporate cash flow, manage payroll, and process vendor invoices.</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= base_url('/finance/create_invoice') ?>" class="px-6 py-3 bg-white text-teal-900 hover:bg-gray-50 font-black rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                New Invoice
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4 border-b-4 border-green-500 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-lg bg-green-50 text-green-600 flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Monthly Revenue</p>
                <p class="text-2xl font-black"><?= defined('SYS_CURRENCY') ? SYS_CURRENCY : '$' ?><?= number_format($metrics['revenue'] ?? 0, 2) ?></p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4 border-b-4 border-red-500 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-lg bg-red-50 text-red-600 flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg></div>
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Monthly Expenses</p>
                <p class="text-2xl font-black text-gray-900"><?= defined('SYS_CURRENCY') ? SYS_CURRENCY : '$' ?><?= number_format($metrics['expenses'] ?? 0, 2) ?></p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Pending Invoices</p>
                <p class="text-2xl font-black text-gray-900"><?= $metrics['pending_invoices'] ?? 0 ?></p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></div>
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Payroll Queue</p>
                <p class="text-2xl font-black text-gray-900"><?= $metrics['payroll_queue'] ?? 0 ?></p>
            </div>
        </div>
    </div>

    <div class="mb-8">
        <h3 class="text-lg font-black text-gray-900 mb-4">Core Finance Modules</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            
            <a href="<?= base_url('/finance/payroll') ?>" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-teal-500 transition group flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h4 class="font-bold text-gray-900">Payroll Engine</h4>
                <p class="text-xs text-gray-500 mt-1">Manage salaries & payslips</p>
            </a>

            <a href="<?= base_url('/finance/transactions') ?>" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-blue-500 transition group flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
                <h4 class="font-bold text-gray-900">Transactions</h4>
                <p class="text-xs text-gray-500 mt-1">Income & Expense Ledger</p>
            </a>

            <a href="<?= base_url('/finance/report') ?>" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-purple-500 transition group flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h4 class="font-bold text-gray-900">Financial Reports</h4>
                <p class="text-xs text-gray-500 mt-1">Export P&L & CSVs</p>
            </a>

            <a href="<?= base_url('/finance/security') ?>" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-red-500 transition group flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-red-50 text-red-600 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h4 class="font-bold text-gray-900">My Security</h4>
                <p class="text-xs text-gray-500 mt-1">Change Account Password</p>
            </a>

            <a href="<?= base_url('/finance/salary_structures') ?>" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-emerald-500 transition group flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h4 class="font-bold text-gray-900">Salary Structure</h4>
                <p class="text-xs text-gray-500 mt-1">Base, Bonus & Allowances</p>
            </a>

        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-gray-900">Recent Invoices</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-white border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Invoice #</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Amount (<?= defined('SYS_CURRENCY') ? SYS_CURRENCY : '$' ?>)</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if(empty($recent_invoices)): ?>
                            <tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">No recent invoices found.</td></tr>
                        <?php else: ?>
                            <?php foreach($recent_invoices as $inv): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-bold text-indigo-600"><?= sanitize($inv['invoice_number'] ?? 'N/A') ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-600"><?= date('M d, Y', strtotime($inv['issue_date'] ?? $inv['created_at'] ?? 'now')) ?></td>
                                    
                                    <td class="px-6 py-4 text-sm font-black text-gray-900">
                                        <?= sanitize($inv['symbol'] ?? (defined('SYS_CURRENCY') ? SYS_CURRENCY : '$')) ?><?= number_format((float)($inv['amount'] ?? 0), 2) ?>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 bg-gray-100 text-gray-700 text-[10px] font-black uppercase rounded"><?= sanitize($inv['status'] ?? 'draft') ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <div class="bg-gradient-to-br from-indigo-800 to-indigo-900 rounded-xl shadow-sm p-6 text-white relative overflow-hidden">
                <h3 class="font-bold mb-4 relative z-10">Personal Workspace</h3>
                <div class="space-y-3 relative z-10">
                    <a href="<?= base_url('/finance/attendance') ?>" class="block w-full py-3 px-4 bg-white/10 hover:bg-white/20 border border-white/20 rounded-lg text-sm font-bold text-center transition">
                        Clock In / My Attendance
                    </a>
                    <a href="<?= base_url('/finance/leaves') ?>" class="block w-full py-3 px-4 bg-white/10 hover:bg-white/20 border border-white/20 rounded-lg text-sm font-bold text-center transition">
                        Request Time Off
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
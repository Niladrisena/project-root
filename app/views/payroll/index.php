<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Payroll & Compensation</h1>
            <p class="text-sm text-gray-500 mt-1">Review monthly compensation, deductions, and initiate direct deposits.</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= base_url('/hr/dashboard') ?>" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-50 transition">
                &larr; Back
            </a>
            <button class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-black rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Run Payroll (<?= date('F Y') ?>)
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-gray-900 to-slate-800 rounded-2xl p-6 shadow-lg text-white border border-slate-700">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Total Gross Liability</p>
            <h2 class="text-4xl font-black tracking-tight">$<?= number_format($metrics['total_liability'], 2) ?></h2>
            <p class="text-slate-400 text-sm mt-2">Across <?= $metrics['headcount'] ?> active employees</p>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Total Deductions (Tax/Ben)</p>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight text-red-500">-$<?= number_format($metrics['total_deductions'], 2) ?></h2>
            <p class="text-gray-400 text-sm mt-2">Automated system calculations</p>
        </div>

        <div class="bg-green-50 rounded-2xl p-6 shadow-sm border border-green-200">
            <p class="text-green-600 text-xs font-bold uppercase tracking-widest mb-1">Net Direct Deposit Payout</p>
            <h2 class="text-3xl font-black text-green-700 tracking-tight">$<?= number_format($metrics['net_payout'], 2) ?></h2>
            <p class="text-green-600 text-sm mt-2">Ready for bank dispatch</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-900">Current Run Ledger</h3>
            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full uppercase tracking-wider">Draft Mode</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <th class="px-6 py-4">Employee Identity</th>
                        <th class="px-6 py-4 text-right">Annual Base</th>
                        <th class="px-6 py-4 text-right">Monthly Gross</th>
                        <th class="px-6 py-4 text-right">Deductions</th>
                        <th class="px-6 py-4 text-right">Net Pay</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if(empty($roster)): ?>
                        <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">No active employees found to process.</td></tr>
                    <?php else: ?>
                        <?php foreach($roster as $emp): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900"><?= sanitize($emp['name']) ?></p>
                                    <p class="text-xs text-gray-400"><?= sanitize($emp['email']) ?></p>
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-gray-500 font-mono">
                                    $<?= number_format($emp['annual_salary'], 2) ?>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-gray-900 font-mono">
                                    $<?= number_format($emp['monthly_base'], 2) ?>
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-red-500 font-mono">
                                    -$<?= number_format($emp['deductions'], 2) ?>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-black text-green-600 font-mono">
                                    $<?= number_format($emp['net_pay'], 2) ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold uppercase tracking-wider rounded-md border border-gray-200">Draft</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
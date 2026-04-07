<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 bg-gradient-to-r from-emerald-900 to-teal-900 rounded-2xl p-8 shadow-lg text-white">
        <div>
            <h1 class="text-3xl font-black tracking-tight">Payroll & Salary Engine 💸</h1>
            <p class="text-emerald-100 mt-2 text-sm">Configure base salaries, view HR attendance data, and process monthly corporate payroll.</p>
        </div>
        <a href="<?= base_url('/finance/dashboard') ?>" class="px-5 py-2.5 bg-white/10 border border-white/20 text-white font-bold rounded-xl shadow-sm hover:bg-white/20 transition backdrop-blur-sm">
            &larr; Back to Hub
        </a>
    </div>

    <?php if (Session::get('flash_success')): ?><div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm"><p class="text-sm text-green-700 font-bold"><?= Session::get('flash_success'); Session::set('flash_success', null); ?></p></div><?php endif; ?>
    <?php if (Session::get('flash_error')): ?><div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm"><p class="text-sm text-red-700 font-bold"><?= Session::get('flash_error'); Session::set('flash_error', null); ?></p></div><?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-900">Corporate Salary Roster</h3>
            <span class="px-3 py-1 bg-teal-100 text-teal-800 text-[10px] font-black uppercase rounded-lg">Current Month: <?= date('F Y') ?></span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <th class="px-6 py-4">Employee</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4 text-center">HR Attendance<br><span class="text-[8px]">(Days Present This Month)</span></th>
                        <th class="px-6 py-4 text-right">Base Salary Setup</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if(empty($employees)): ?>
                        <tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">No active employees found.</td></tr>
                    <?php else: ?>
                        <?php foreach($employees as $emp): ?>
                            <tr class="hover:bg-gray-50 transition group">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900"><?= sanitize($emp['first_name'] . ' ' . $emp['last_name']) ?></p>
                                    <p class="text-[10px] text-gray-400 font-medium"><?= sanitize($emp['email']) ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-600 border border-gray-200 text-[10px] font-black uppercase rounded"><?= sanitize($emp['role_name'] ?? 'Staff') ?></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-lg font-black <?= $emp['days_present'] > 0 ? 'text-teal-600' : 'text-gray-400' ?>"><?= $emp['days_present'] ?></span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="<?= base_url('/finance/update_salary') ?>" method="POST" class="flex items-center justify-end gap-2">
                                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                        <input type="hidden" name="user_id" value="<?= $emp['id'] ?>">
                                        
                                        <div class="relative w-32">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm font-bold">$</span>
                                            </div>
                                            <input type="number" step="0.01" name="base_salary" value="<?= number_format($emp['base_salary'], 2, '.', '') ?>" class="pl-7 pr-3 py-2 w-full border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none font-bold text-gray-900 bg-gray-50 focus:bg-white transition" placeholder="0.00">
                                        </div>
                                        <button type="submit" class="p-2 bg-teal-50 text-teal-600 hover:bg-teal-600 hover:text-white rounded-lg border border-teal-100 shadow-sm transition opacity-0 group-hover:opacity-100 focus:opacity-100">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
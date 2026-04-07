<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="mb-8 flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-teal-900 text-white rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Salary Structure Foundation</h1>
                <p class="text-sm text-gray-500 mt-0.5">Define Base, Allowances, and Deductions prior to Payroll generation.</p>
            </div>
        </div>
        <a href="<?= base_url('/finance/dashboard') ?>" class="px-5 py-2.5 bg-gray-50 border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-100 transition">
            &larr; Back to Hub
        </a>
    </div>

    <?php if (Session::get('flash_error')): ?><div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm"><p class="text-sm text-red-700 font-bold"><?= Session::get('flash_error'); Session::set('flash_error', null); ?></p></div><?php endif; ?>
    <?php if (Session::get('flash_success')): ?><div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm"><p class="text-sm text-green-700 font-bold"><?= Session::get('flash_success'); Session::set('flash_success', null); ?></p></div><?php endif; ?>

    <form action="<?= base_url('/finance/save_salary_structure') ?>" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="font-black text-gray-900">Define Employee Structure</h3>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Select Employee <span class="text-red-500">*</span></label>
                    <select name="user_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-teal-900 outline-none transition">
                        <option value="" disabled selected>-- Choose from Active Roster --</option>
                        <?php foreach($structures as $emp): ?>
                            <option value="<?= $emp['user_id'] ?>"><?= sanitize($emp['first_name'] . ' ' . $emp['last_name']) ?> (<?= sanitize($emp['role_name'] ?? 'Staff') ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Currency <span class="text-red-500">*</span></label>
                    <select name="currency_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-teal-900 outline-none transition">
                        <?php foreach($currencies as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= sanitize($c['code']) ?> (<?= sanitize($c['symbol']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Basic Salary</label>
                    <input type="number" step="0.01" name="basic_salary" value="0.00" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm font-black focus:bg-white focus:ring-2 focus:ring-teal-900 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Allowance</label>
                    <input type="number" step="0.01" name="allowance" value="0.00" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm font-black focus:bg-white focus:ring-2 focus:ring-teal-900 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Bonus</label>
                    <input type="number" step="0.01" name="bonus" value="0.00" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm font-black focus:bg-white focus:ring-2 focus:ring-teal-900 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2 text-red-600">Deduction</label>
                    <input type="number" step="0.01" name="deduction" value="0.00" class="w-full px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm font-black text-red-900 focus:bg-white focus:ring-2 focus:ring-red-500 outline-none transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end border-t border-gray-100 pt-6">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Effective Date <span class="text-red-500">*</span></label>
                    <input type="date" name="effective_date" required value="<?= date('Y-m-d') ?>" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-teal-900 outline-none transition">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-8 py-3.5 w-full md:w-auto bg-teal-900 hover:bg-teal-800 text-white font-black rounded-xl shadow-md transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Assign Structure
                    </button>
                </div>
            </div>
        </div>
    </form>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-900">Current Corporate Salary Assignments</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <th class="px-6 py-4">Employee</th>
                        <th class="px-6 py-4 text-center">Basic</th>
                        <th class="px-6 py-4 text-center">Allow + Bonus</th>
                        <th class="px-6 py-4 text-center text-red-500">Deductions</th>
                        <th class="px-6 py-4 text-right">Net Structure</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if(empty($structures)): ?>
                        <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">No active employees found.</td></tr>
                    <?php else: ?>
                        <?php foreach($structures as $emp): 
                            $sym = $emp['symbol'] ?? '$';
                        ?>
                            <tr class="hover:bg-gray-50 transition group">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900"><?= sanitize($emp['first_name'] . ' ' . $emp['last_name']) ?></p>
                                    <p class="text-[10px] text-gray-400 font-medium"><?= sanitize($emp['role_name'] ?? 'Staff') ?></p>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-gray-600">
                                    <?= $emp['basic_salary'] > 0 ? $sym . number_format($emp['basic_salary'], 2) : '-' ?>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-green-600">
                                    <?php $addons = ($emp['allowance'] ?? 0) + ($emp['bonus'] ?? 0); ?>
                                    <?= $addons > 0 ? '+' . $sym . number_format($addons, 2) : '-' ?>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-red-500">
                                    <?= $emp['deduction'] > 0 ? '-' . $sym . number_format($emp['deduction'], 2) : '-' ?>
                                </td>
                                <td class="px-6 py-4 text-right font-black text-gray-900 text-lg">
                                    <?= $sym . number_format($emp['net_salary'] ?? 0, 2) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
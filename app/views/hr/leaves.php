<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-200 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-teal-900 text-white rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Leave & Balance Management</h1>
                <p class="text-sm text-gray-500 mt-0.5">Approve corporate PTO, track active leave balances, and request your own time off.</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="<?= base_url('/hr/dashboard') ?>" class="px-5 py-2.5 bg-gray-50 border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-100 transition">Dashboard</a>
            <button onclick="document.getElementById('leave-modal').classList.remove('hidden')" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-black rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Apply for Leave
            </button>
        </div>
    </div>

    <?php if (Session::get('flash_success')): ?><div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm"><p class="text-sm text-green-700 font-bold"><?= Session::get('flash_success'); Session::set('flash_success', null); ?></p></div><?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50"><h3 class="font-bold text-gray-900">Pending & Processed Requests</h3></div>
            <div class="overflow-x-auto max-h-96 custom-scrollbar">
                <table class="w-full text-left">
                    <thead class="sticky top-0 bg-white shadow-sm z-10">
                        <tr class="border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Employee</th>
                            <th class="px-6 py-4">Leave Details</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if(empty($leaves)): ?>
                            <tr><td colspan="3" class="px-6 py-12 text-center text-gray-500">No leave requests found.</td></tr>
                        <?php else: ?>
                            <?php foreach($leaves as $l): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold text-gray-900"><?= sanitize($l['first_name'] . ' ' . $l['last_name']) ?></p>
                                        <p class="text-[10px] font-black text-teal-600 mt-0.5"><?= date('M d', strtotime($l['start_date'])) ?> &rarr; <?= date('M d', strtotime($l['end_date'])) ?> (<?= $l['total_days'] ?>d)</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 bg-gray-100 border border-gray-200 text-gray-700 text-[9px] font-black uppercase tracking-widest rounded"><?= sanitize(str_replace('_', ' ', $l['leave_type'])) ?></span>
                                        <?php 
                                            if($l['status'] == 'approved') echo '<span class="ml-2 px-2 py-0.5 bg-green-100 text-green-700 text-[9px] font-black uppercase rounded">Approved</span>';
                                            elseif($l['status'] == 'rejected') echo '<span class="ml-2 px-2 py-0.5 bg-red-100 text-red-700 text-[9px] font-black uppercase rounded">Rejected</span>';
                                        ?>
                                        <p class="text-[11px] text-gray-500 mt-2 line-clamp-1 max-w-[200px]" title="<?= sanitize($l['reason']) ?>"><?= sanitize($l['reason']) ?></p>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <?php if($l['status'] == 'pending'): ?>
                                            <form action="<?= base_url('/hr/process_leave') ?>" method="POST" class="inline-flex gap-2">
                                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                                <input type="hidden" name="leave_id" value="<?= $l['id'] ?>">
                                                <button type="submit" name="status" value="approved" class="p-1.5 bg-green-100 text-green-700 hover:bg-green-600 hover:text-white rounded shadow-sm transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></button>
                                                <button type="submit" name="status" value="rejected" class="p-1.5 bg-red-100 text-red-700 hover:bg-red-600 hover:text-white rounded shadow-sm transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden border-t-4 border-teal-500">
            <div class="p-5 border-b border-gray-100 bg-gray-50"><h3 class="font-bold text-gray-900">Corporate Leave Balances (Current Year)</h3></div>
            <div class="overflow-x-auto max-h-96 custom-scrollbar">
                <table class="w-full text-left">
                    <thead class="sticky top-0 bg-white shadow-sm z-10">
                        <tr class="border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Employee</th>
                            <th class="px-6 py-4 text-center">Remaining PTO</th>
                            <th class="px-6 py-4 text-center">Remaining Sick</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php foreach($balances as $b): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900"><?= sanitize($b['first_name'] . ' ' . $b['last_name']) ?></p>
                                    <p class="text-[10px] text-gray-400 font-medium"><?= sanitize($b['email']) ?></p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-black <?= $b['pto_balance'] > 5 ? 'text-teal-600' : 'text-red-500' ?>"><?= $b['pto_balance'] ?></span>
                                    <span class="text-[10px] text-gray-400 font-bold block">/ 20</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-black <?= $b['sick_balance'] > 2 ? 'text-blue-600' : 'text-red-500' ?>"><?= $b['sick_balance'] ?></span>
                                    <span class="text-[10px] text-gray-400 font-bold block">/ 10</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div id="leave-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-all">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-black text-gray-900">Request Time Off</h3>
                <button onclick="document.getElementById('leave-modal').classList.add('hidden')" class="text-gray-400 hover:text-red-500 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <form action="<?= base_url('/hr/process_leave') ?>" method="POST" class="p-6 space-y-5">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="action_type" value="apply">
                
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Leave Type <span class="text-red-500">*</span></label>
                    <select name="leave_type" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="paid_time_off">Paid Time Off (Vacation)</option>
                        <option value="sick_leave">Sick Leave</option>
                        <option value="unpaid_leave">Unpaid Leave</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Start Date <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">End Date <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Reason <span class="text-red-500">*</span></label>
                    <textarea name="reason" required rows="3" placeholder="Provide a brief explanation..." class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-teal-500 resize-none"></textarea>
                </div>
                <button type="submit" class="w-full py-3 bg-teal-600 hover:bg-teal-700 text-white font-black rounded-lg shadow transition mt-2">Submit Request</button>
            </form>
        </div>
    </div>
</div>
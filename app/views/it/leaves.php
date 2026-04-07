<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-200 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-900 text-white rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Leave Management</h1>
                <p class="text-sm text-gray-500 mt-0.5">Apply for time off and review team PTO requests.</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="<?= base_url('/it/dashboard') ?>" class="px-5 py-2.5 bg-gray-50 border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-100 transition">Dashboard</a>
            <button onclick="document.getElementById('leave-modal').classList.remove('hidden')" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Request PTO
            </button>
        </div>
    </div>

    <?php if (Session::get('flash_success')): ?><div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm"><p class="text-sm text-green-700 font-bold"><?= Session::get('flash_success'); Session::set('flash_success', null); ?></p></div><?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50"><h3 class="font-bold text-gray-900">Corporate Leave Ledger</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <th class="px-6 py-4">Employee</th>
                        <th class="px-6 py-4">Leave Info</th>
                        <th class="px-6 py-4">Duration</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if(empty($leaves)): ?>
                        <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">No leave requests found in the system.</td></tr>
                    <?php else: ?>
                        <?php foreach($leaves as $l): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900"><?= sanitize($l['first_name'] . ' ' . $l['last_name']) ?></p>
                                    <p class="text-[10px] text-gray-400 font-medium"><?= sanitize($l['email']) ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 bg-gray-100 border border-gray-200 text-gray-700 text-[9px] font-black uppercase tracking-widest rounded"><?= sanitize(str_replace('_', ' ', $l['leave_type'])) ?></span>
                                    <p class="text-xs text-gray-500 mt-2 line-clamp-1 max-w-xs" title="<?= sanitize($l['reason']) ?>"><?= sanitize($l['reason']) ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-black text-gray-900"><?= date('M d', strtotime($l['start_date'])) ?> &rarr; <?= date('M d', strtotime($l['end_date'])) ?></p>
                                    <p class="text-[10px] font-bold text-indigo-600 mt-0.5"><?= $l['total_days'] ?> Day(s)</p>
                                </td>
                                <td class="px-6 py-4">
                                    <?php 
                                        if($l['status'] == 'approved') echo '<span class="px-2.5 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase rounded">Approved</span>';
                                        elseif($l['status'] == 'rejected') echo '<span class="px-2.5 py-1 bg-red-100 text-red-700 text-[10px] font-black uppercase rounded">Rejected</span>';
                                        else echo '<span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-black uppercase rounded">Pending</span>';
                                    ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <?php if($l['status'] == 'pending'): ?>
                                        <form action="<?= base_url('/it/process_leave') ?>" method="POST" class="inline-flex gap-2">
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

    <div id="leave-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-all">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-black text-gray-900">Request Paid Time Off</h3>
                <button onclick="document.getElementById('leave-modal').classList.add('hidden')" class="text-gray-400 hover:text-red-500 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <form action="<?= base_url('/it/process_leave') ?>" method="POST" class="p-6 space-y-5">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="action_type" value="apply">
                
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Leave Type <span class="text-red-500">*</span></label>
                    <select name="leave_type" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="sick_leave">Sick Leave</option>
                        <option value="paid_time_off">Paid Time Off (Vacation)</option>
                        <option value="unpaid_leave">Unpaid Leave</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Start Date <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">End Date <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Reason / Handover Notes <span class="text-red-500">*</span></label>
                    <textarea name="reason" required rows="3" placeholder="Explain the reason and who is covering your shift..." class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                </div>
                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-lg shadow transition mt-2">Submit Request</button>
            </form>
        </div>
    </div>
</div>
<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Time-Off Requests</h1>
                <p class="text-sm text-gray-500 mt-0.5">Review, approve, or reject employee leave applications.</p>
            </div>
        </div>
        <a href="<?= base_url('/hr/dashboard') ?>" class="px-5 py-2.5 bg-gray-50 border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-100 transition">
            &larr; Back to HR Hub
        </a>
    </div>

    <?php if (Session::get('flash_success')): ?>
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <p class="text-sm text-green-800 font-bold"><?= Session::get('flash_success'); Session::set('flash_success', null); ?></p>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-black text-gray-500 uppercase tracking-widest">
                        <th class="px-6 py-4">Employee</th>
                        <th class="px-6 py-4">Dates & Duration</th>
                        <th class="px-6 py-4">Leave Type / Reason</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if(empty($leaves)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <span class="font-bold text-gray-900 block">All caught up!</span>
                                <span class="text-sm">There are no pending leave requests.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($leaves as $leave): 
                            // Calculate Duration
                            $start = new DateTime($leave['start_date']);
                            $end = new DateTime($leave['end_date']);
                            $days = $end->diff($start)->days + 1;
                        ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900"><?= sanitize($leave['first_name'] . ' ' . $leave['last_name']) ?></p>
                                    <p class="text-[11px] font-bold text-gray-400 tracking-wider uppercase mt-0.5"><?= sanitize($leave['email']) ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-900"><?= date('M d', strtotime($leave['start_date'])) ?> &rarr; <?= date('M d, Y', strtotime($leave['end_date'])) ?></p>
                                    <p class="text-xs font-bold text-indigo-600 mt-0.5"><?= $days ?> Day(s)</p>
                                </td>
                                <td class="px-6 py-4 max-w-xs">
                                    <p class="text-sm font-bold text-gray-900 uppercase tracking-wider text-[11px]"><?= sanitize($leave['leave_type']) ?></p>
                                    <p class="text-xs text-gray-500 truncate mt-0.5" title="<?= sanitize($leave['reason']) ?>"><?= sanitize($leave['reason']) ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if($leave['status'] == 'pending'): ?>
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-black uppercase tracking-wider rounded-full shadow-sm">Pending</span>
                                    <?php elseif($leave['status'] == 'approved'): ?>
                                        <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase tracking-wider rounded-full shadow-sm">Approved</span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-red-100 text-red-700 text-[10px] font-black uppercase tracking-wider rounded-full shadow-sm">Rejected</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <?php if($leave['status'] == 'pending'): ?>
                                        <form action="<?= base_url('/leave/process') ?>" method="POST" class="inline-flex gap-2">
                                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                            <input type="hidden" name="leave_id" value="<?= $leave['id'] ?>">
                                            
                                            <button type="submit" name="action" value="approved" class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                                Approve
                                            </button>
                                            <button type="submit" name="action" value="rejected" class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-bold rounded-lg transition">
                                                Reject
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-xs font-bold text-gray-400 uppercase">Processed</span>
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
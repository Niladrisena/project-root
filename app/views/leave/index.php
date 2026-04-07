<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Leave Management</h1>
        <p class="text-sm text-gray-500 mt-1">Request time off, view your available balances, and track approvals.</p>
    </div>

    <?php if (Session::get('flash_success')): ?>
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <p class="text-sm text-green-700 font-medium"><?= Session::get('flash_success'); Session::set('flash_success', null); ?></p>
        </div>
    <?php endif; ?>
    <?php if (Session::get('flash_error')): ?>
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm flex items-center gap-3">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            <p class="text-sm text-red-700 font-medium"><?= Session::get('flash_error'); Session::set('flash_error', null); ?></p>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <?php 
            $available = ($balance['total_leaves'] ?? 12) - ($balance['used_leaves'] ?? 0); 
            $used = $balance['used_leaves'] ?? 0;
            $total = $balance['total_leaves'] ?? 12;
        ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex items-center gap-4 border-b-4 border-b-blue-500">
            <div class="w-14 h-14 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
            <div><p class="text-xs font-bold text-gray-500 uppercase">Available Leave</p><p class="text-3xl font-black text-gray-900"><?= $available ?> <span class="text-sm text-gray-400 font-medium">Days</span></p></div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-gray-50 text-gray-500 flex items-center justify-center"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div><p class="text-xs font-bold text-gray-500 uppercase">Used Leave</p><p class="text-3xl font-black text-gray-900"><?= $used ?> <span class="text-sm text-gray-400 font-medium">Days</span></p></div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-gray-50 text-gray-500 flex items-center justify-center"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg></div>
            <div><p class="text-xs font-bold text-gray-500 uppercase">Total Annual Allowance</p><p class="text-3xl font-black text-gray-900"><?= $total ?> <span class="text-sm text-gray-400 font-medium">Days</span></p></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-900">Request Time Off</h3>
                </div>
                
                <form action="<?= base_url('/leave/store') ?>" method="POST" class="p-6 space-y-5">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Leave Type</label>
                        <select name="leave_type" required class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition shadow-sm">
                            <option value="annual">Annual / Vacation Leave</option>
                            <option value="sick">Sick Leave</option>
                            <option value="casual">Casual / Personal Leave</option>
                            <option value="unpaid">Unpaid Leave</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Start Date</label>
                            <input type="date" name="start_date" min="<?= date('Y-m-d') ?>" required class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">End Date</label>
                            <input type="date" name="end_date" min="<?= date('Y-m-d') ?>" required class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Reason</label>
                        <textarea name="reason" rows="3" required placeholder="Please provide a brief reason..." class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition shadow-sm resize-none"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-xl shadow-md hover:shadow-lg transition-all">
                        Submit Request
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-900">Leave Application History</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 text-xs font-black text-gray-400 uppercase tracking-wider">
                                <th class="px-6 py-4">Type & Duration</th>
                                <th class="px-6 py-4">Reason</th>
                                <th class="px-6 py-4">Applied On</th>
                                <th class="px-6 py-4 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php if(empty($history)): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500 text-sm">No leave history found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($history as $h): ?>
                                    <tr class="hover:bg-gray-50 transition cursor-default">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-900 capitalize"><?= sanitize($h['leave_type']) ?> Leave</p>
                                            <p class="text-xs text-gray-500 mt-0.5 font-medium">
                                                <?= date('M d', strtotime($h['start_date'])) ?> - <?= date('M d, Y', strtotime($h['end_date'])) ?>
                                            </p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-600 truncate max-w-[200px]" title="<?= sanitize($h['reason']) ?>">
                                                <?= sanitize($h['reason']) ?>
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-500">
                                            <?= date('M d, Y', strtotime($h['created_at'])) ?>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <?php 
                                                $statusColors = [
                                                    'approved' => 'bg-green-100 text-green-700 border-green-200',
                                                    'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                    'rejected' => 'bg-red-100 text-red-700 border-red-200'
                                                ];
                                                $color = $statusColors[$h['status']] ?? $statusColors['pending'];
                                            ?>
                                            <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full border <?= $color ?>">
                                                <?= sanitize($h['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
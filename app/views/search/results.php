<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="mb-8 bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Search Results</h1>
        <p class="text-gray-500 mt-2">
            Showing omni-search results for: <span class="font-black text-indigo-600 bg-indigo-50 px-2 py-1 rounded">"<?= htmlspecialchars($query) ?>"</span>
        </p>
    </div>

    <?php 
        $has_results = false;
        foreach($results as $category => $items) {
            if(!empty($items)) $has_results = true;
        }
    ?>

    <?php if(!$has_results || empty(trim($query))): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-16 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <h3 class="text-xl font-black text-gray-900">No results found</h3>
            <p class="text-gray-500 mt-2">We couldn't find anything matching your query across the authorized databases.</p>
        </div>
    <?php else: ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <?php if(!empty($results['modules'])): ?>
                <div class="bg-gradient-to-br from-indigo-900 to-blue-900 rounded-2xl shadow-md border border-indigo-800 overflow-hidden lg:col-span-2 mb-2">
                    <div class="bg-black/20 border-b border-white/10 p-4 font-black text-indigo-200 uppercase tracking-widest text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Quick Navigation Links
                    </div>
                    <ul class="divide-y divide-white/5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                        <?php foreach($results['modules'] as $mod): ?>
                            <li>
                                <a href="<?= base_url($mod['url']) ?>" class="block p-4 hover:bg-white/10 transition group h-full">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white group-hover:bg-indigo-500 group-hover:scale-110 transition flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-white"><?= sanitize($mod['name']) ?></p>
                                            <p class="text-[11px] text-indigo-200 leading-tight mt-0.5"><?= sanitize($mod['desc']) ?></p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if(!empty($results['employees'])): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-100 p-4 font-black text-gray-700 uppercase tracking-widest text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Employees Found
                    </div>
                    <ul class="divide-y divide-gray-50">
                        <?php foreach($results['employees'] as $emp): ?>
                            <li>
                                <a href="<?= base_url('/employee/show/' . $emp['id']) ?>" class="block p-4 hover:bg-gray-50 transition">
                                    <p class="font-bold text-gray-900"><?= sanitize($emp['first_name'] . ' ' . $emp['last_name']) ?></p>
                                    <p class="text-xs text-gray-500"><?= sanitize($emp['email']) ?></p>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if(!empty($results['projects'])): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-100 p-4 font-black text-gray-700 uppercase tracking-widest text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Projects Found
                    </div>
                    <ul class="divide-y divide-gray-50">
                        <?php foreach($results['projects'] as $proj): ?>
                            <li>
                                <a href="<?= base_url('/project/show/' . $proj['id']) ?>" class="p-4 hover:bg-gray-50 transition flex justify-between items-center">
                                    <p class="font-bold text-gray-900"><?= sanitize($proj['name']) ?></p>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-[10px] font-black uppercase rounded"><?= sanitize($proj['status']) ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if(!empty($results['tasks'])): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-100 p-4 font-black text-gray-700 uppercase tracking-widest text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        My Tasks
                    </div>
                    <ul class="divide-y divide-gray-50">
                        <?php foreach($results['tasks'] as $task): ?>
                            <li>
                                <a href="<?= base_url('/task/board') ?>" class="block p-4 hover:bg-gray-50 transition">
                                    <div class="flex justify-between items-start gap-4">
                                        <div>
                                            <p class="font-bold text-gray-900"><?= sanitize($task['title']) ?></p>
                                            <p class="text-xs text-gray-500"><?= sanitize(ucwords(str_replace('_', ' ', $task['status'] ?? 'pending'))) ?></p>
                                        </div>
                                        <span class="text-xs font-bold text-gray-400"><?= !empty($task['deadline']) ? date('M d', strtotime($task['deadline'])) : 'No deadline' ?></span>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if(!empty($results['timesheets'])): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-100 p-4 font-black text-gray-700 uppercase tracking-widest text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Timesheet Entries
                    </div>
                    <ul class="divide-y divide-gray-50">
                        <?php foreach($results['timesheets'] as $entry): ?>
                            <li>
                                <a href="<?= base_url('/timesheet') ?>" class="block p-4 hover:bg-gray-50 transition">
                                    <div class="flex justify-between items-start gap-4">
                                        <div>
                                            <p class="font-bold text-gray-900"><?= sanitize($entry['project_name'] ?? 'General') ?></p>
                                            <p class="text-xs text-gray-500"><?= sanitize(ucwords($entry['status'] ?? 'pending')) ?> · <?= number_format((float) ($entry['hours_worked'] ?? 0), 1) ?>h</p>
                                        </div>
                                        <span class="text-xs font-bold text-gray-400"><?= !empty($entry['date']) ? date('M d, Y', strtotime($entry['date'])) : '' ?></span>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if(!empty($results['leaves'])): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-100 p-4 font-black text-gray-700 uppercase tracking-widest text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Leave Requests
                    </div>
                    <ul class="divide-y divide-gray-50">
                        <?php foreach($results['leaves'] as $leave): ?>
                            <li>
                                <a href="<?= base_url('/leave') ?>" class="block p-4 hover:bg-gray-50 transition">
                                    <div class="flex justify-between items-start gap-4">
                                        <div>
                                            <p class="font-bold text-gray-900"><?= sanitize(ucwords($leave['leave_type'] ?? 'leave')) ?></p>
                                            <p class="text-xs text-gray-500"><?= sanitize(ucwords($leave['status'] ?? 'pending')) ?></p>
                                        </div>
                                        <span class="text-xs font-bold text-gray-400">
                                            <?= !empty($leave['start_date']) ? date('M d', strtotime($leave['start_date'])) : '' ?>
                                            <?php if(!empty($leave['end_date'])): ?> - <?= date('M d', strtotime($leave['end_date'])) ?><?php endif; ?>
                                        </span>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if(!empty($results['assets'])): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-100 p-4 font-black text-gray-700 uppercase tracking-widest text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        My Assets
                    </div>
                    <ul class="divide-y divide-gray-50">
                        <?php foreach($results['assets'] as $asset): ?>
                            <li>
                                <a href="<?= base_url('/asset/my') ?>" class="block p-4 hover:bg-gray-50 transition">
                                    <div class="flex justify-between items-start gap-4">
                                        <div>
                                            <p class="font-bold text-gray-900"><?= sanitize($asset['brand_model'] ?? $asset['asset_tag']) ?></p>
                                            <p class="text-xs text-gray-500"><?= sanitize(($asset['category'] ?? 'Asset') . ' · ' . ($asset['asset_tag'] ?? '')) ?></p>
                                        </div>
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 text-[10px] font-black uppercase rounded"><?= sanitize($asset['status'] ?? 'assigned') ?></span>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if(!empty($results['invoices'])): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-100 p-4 font-black text-gray-700 uppercase tracking-widest text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Invoices Found
                    </div>
                    <ul class="divide-y divide-gray-50">
                        <?php foreach($results['invoices'] as $inv): ?>
                            <li class="p-4 hover:bg-gray-50 transition flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-indigo-600"><?= sanitize($inv['invoice_number']) ?></p>
                                    <p class="text-xs text-gray-500"><?= date('M d, Y', strtotime($inv['issue_date'] ?? 'now')) ?></p>
                                </div>
                                <p class="font-black text-gray-900"><?= defined('SYS_CURRENCY') ? SYS_CURRENCY : '$' ?><?= number_format($inv['amount'], 2) ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if(!empty($results['transactions'])): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-100 p-4 font-black text-gray-700 uppercase tracking-widest text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                        Ledger Transactions
                    </div>
                    <ul class="divide-y divide-gray-50">
                        <?php foreach($results['transactions'] as $txn): ?>
                            <li class="p-4 hover:bg-gray-50 transition flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-gray-900"><?= sanitize($txn['category']) ?></p>
                                    <p class="text-xs text-gray-500">Ref: <?= sanitize($txn['reference_no']) ?></p>
                                </div>
                                <?php if($txn['type'] === 'income'): ?>
                                    <span class="text-green-600 font-black">+ <?= defined('SYS_CURRENCY') ? SYS_CURRENCY : '$' ?><?= number_format($txn['amount'], 2) ?></span>
                                <?php else: ?>
                                    <span class="text-red-600 font-black">- <?= defined('SYS_CURRENCY') ? SYS_CURRENCY : '$' ?><?= number_format($txn['amount'], 2) ?></span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

        </div>
    <?php endif; ?>
</div>

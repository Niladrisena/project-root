<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl p-8 shadow-lg text-white">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <p class="text-slate-300 text-xs font-bold uppercase tracking-widest">All Systems Operational</p>
            </div>
            <h1 class="text-3xl font-black tracking-tight">IT & Infrastructure Ops 🌐</h1>
            <p class="text-slate-400 mt-2 text-sm">Monitor company hardware, resolve technical issues, and provision assets.</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= base_url('/it/assets') ?>" class="px-6 py-3 bg-white text-slate-900 font-black rounded-xl shadow-md transition flex items-center gap-2 hover:scale-105 transform duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                Asset Management Hub
            </a>
            <button onclick="window.location.reload();" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl border border-white/20 shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Refresh Logs
            </button>
        </div>
    </div>

    <?php if (Session::get('flash_success')): ?>
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm">
            <p class="text-sm text-green-700 font-bold"><?= Session::get('flash_success'); Session::set('flash_success', null); ?></p>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center justify-between border-b-4 border-blue-500">
            <div><p class="text-xs font-bold text-gray-500 uppercase">Active Tickets</p><p class="text-2xl font-black text-gray-900"><?= $stats['active'] ?></p></div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center justify-between border-b-4 border-purple-500">
            <div><p class="text-xs font-bold text-gray-500 uppercase">Pending Assets</p><p class="text-2xl font-black text-gray-900"><?= $stats['pending_assets'] ?></p></div>
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center justify-between border-b-4 <?= $stats['critical_issues'] > 0 ? 'border-red-500 bg-red-50' : 'border-gray-200' ?>">
            <div><p class="text-xs font-bold <?= $stats['critical_issues'] > 0 ? 'text-red-500' : 'text-gray-500' ?> uppercase">Critical Outages</p><p class="text-2xl font-black text-gray-900"><?= $stats['critical_issues'] ?></p></div>
            <div class="w-12 h-12 <?= $stats['critical_issues'] > 0 ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-400' ?> rounded-full flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center justify-between border-b-4 border-green-500">
            <div><p class="text-xs font-bold text-gray-500 uppercase">Resolved</p><p class="text-2xl font-black text-gray-900"><?= $stats['resolved'] ?></p></div>
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-full flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
        </div>
        
    </div>
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <a href="<?= base_url('/it/attendance') ?>" class="bg-gradient-to-r from-emerald-800 to-emerald-900 rounded-2xl p-6 shadow-sm hover:shadow-lg transition transform hover:-translate-y-1 group flex flex-col items-center justify-center text-center">
            <div class="w-14 h-14 bg-white/10 rounded-full flex items-center justify-center text-emerald-300 group-hover:scale-110 transition mb-3"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <h3 class="text-lg font-black text-white tracking-tight">Attendance</h3>
            <p class="text-emerald-200 text-xs mt-1">Clock in & timesheets.</p>
        </a>

        <a href="<?= base_url('/it/leaves') ?>" class="bg-gradient-to-r from-indigo-800 to-indigo-900 rounded-2xl p-6 shadow-sm hover:shadow-lg transition transform hover:-translate-y-1 group flex flex-col items-center justify-center text-center">
            <div class="w-14 h-14 bg-white/10 rounded-full flex items-center justify-center text-indigo-300 group-hover:scale-110 transition mb-3"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
            <h3 class="text-lg font-black text-white tracking-tight">Leaves</h3>
            <p class="text-indigo-200 text-xs mt-1">Manage corporate PTO.</p>
        </a>

        <a href="<?= base_url('/it/settings') ?>" class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-2xl p-6 shadow-sm hover:shadow-lg transition transform hover:-translate-y-1 group flex flex-col items-center justify-center text-center border border-gray-700">
            <div class="w-14 h-14 bg-white/10 rounded-full flex items-center justify-center text-gray-300 group-hover:scale-110 transition mb-3"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></div>
            <h3 class="text-lg font-black text-white tracking-tight">Global Settings</h3>
            <p class="text-gray-400 text-xs mt-1">System Configurations.</p>
        </a>

        <a href="<?= base_url('/it/security') ?>" class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl p-6 shadow-sm hover:shadow-lg transition transform hover:-translate-y-1 group flex flex-col items-center justify-center text-center border border-slate-700">
            <div class="w-14 h-14 bg-white/10 rounded-full flex items-center justify-center text-slate-300 group-hover:scale-110 transition mb-3"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></div>
            <h3 class="text-lg font-black text-white tracking-tight">My Security</h3>
            <p class="text-slate-400 text-xs mt-1">Change Account Password.</p>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-900">Global Ticket Queue</h3>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Sorted by Priority</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <th class="px-6 py-4">Requester</th>
                        <th class="px-6 py-4">Issue / Asset</th>
                        <th class="px-6 py-4 text-center">Priority</th>
                        <th class="px-6 py-4">Submitted</th>
                        <th class="px-6 py-4 text-right">Action / Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if(empty($tickets)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="font-bold text-gray-900 block text-lg">Zero Active Tickets</span>
                                <span class="text-sm">The IT queue is completely clear.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($tickets as $t): ?>
                            <?php $is_resolved = in_array($t['status'], ['resolved', 'closed']); ?>
                            <tr class="hover:bg-slate-50 transition-colors <?= $is_resolved ? 'opacity-60 bg-gray-50' : '' ?>">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-xs">
                                            <?= substr($t['first_name'], 0, 1) . substr($t['last_name'], 0, 1) ?>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900"><?= sanitize($t['first_name'] . ' ' . $t['last_name']) ?></p>
                                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">IT-<?= str_pad($t['id'], 4, '0', STR_PAD_LEFT) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900 <?= $is_resolved ? 'line-through' : '' ?>"><?= sanitize($t['subject']) ?></p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider"><?= str_replace('_', ' ', $t['ticket_type']) ?></p>
                                        <?php if($t['ticket_type'] === 'asset_request' && !empty($t['asset_type'])): ?>
                                            <span class="px-2 py-0.5 bg-blue-50 border border-blue-200 text-blue-700 text-[9px] font-black uppercase tracking-widest rounded flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                                                <?= str_replace('_', ' ', $t['asset_type']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php 
                                        if($t['priority'] == 'critical') echo '<span class="px-2.5 py-1 bg-red-100 text-red-700 border border-red-200 text-[10px] font-black uppercase rounded shadow-sm flex items-center justify-center gap-1"><span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span> Critical</span>';
                                        elseif($t['priority'] == 'high') echo '<span class="px-2.5 py-1 bg-orange-100 text-orange-700 text-[10px] font-black uppercase rounded">High</span>';
                                        else echo '<span class="px-2.5 py-1 text-gray-500 text-[10px] font-bold uppercase">'.$t['priority'].'</span>';
                                    ?>
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-gray-500">
                                    <?= date('M d, g:i A', strtotime($t['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <?php if($is_resolved): ?>
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-green-600 bg-green-50 px-3 py-1.5 rounded-lg border border-green-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Resolved
                                        </span>
                                    <?php else: ?>
                                        <form action="<?= base_url('/it/update_status') ?>" method="POST" class="inline-block">
                                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                            <input type="hidden" name="ticket_id" value="<?= $t['id'] ?>">
                                            <div class="flex items-center justify-end gap-2">
                                                <select name="status" class="text-xs font-bold bg-white border border-gray-300 text-gray-700 rounded-lg px-2 py-1.5 outline-none focus:ring-2 focus:ring-slate-800">
                                                    <option value="open" <?= $t['status'] == 'open' ? 'selected' : '' ?>>Open</option>
                                                    <option value="in_progress" <?= $t['status'] == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                                    <option value="resolved">Mark Resolved</option>
                                                </select>
                                                <button type="submit" class="p-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-lg shadow-sm transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </div>
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
</div>
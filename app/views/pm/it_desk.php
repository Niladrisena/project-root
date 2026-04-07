<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="mb-8 flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-slate-800 text-white rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">IT Help Desk & Assets</h1>
                <p class="text-sm text-gray-500 mt-0.5">Manage technical issues, software access, and hardware provisioning.</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="<?= base_url('/pm/dashboard') ?>" class="px-5 py-2.5 bg-gray-50 border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-100 transition">
                &larr; Back to Command Center
            </a>
            <a href="<?= base_url('/pm/request_it_support') ?>" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-black rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New IT Request
            </a>
        </div>
    </div>

    <?php if (Session::get('flash_success')): ?>
        <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
            <p class="text-sm text-green-700 font-bold"><?= Session::get('flash_success'); Session::set('flash_success', null); ?></p>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center justify-between">
            <div><p class="text-xs font-bold text-gray-500 uppercase">Total Requests</p><p class="text-3xl font-black text-gray-900"><?= $stats['total'] ?></p></div>
            <div class="w-12 h-12 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center justify-between border-b-4 border-yellow-500">
            <div><p class="text-xs font-bold text-gray-500 uppercase">Active / Pending</p><p class="text-3xl font-black text-gray-900"><?= $stats['active'] ?></p></div>
            <div class="w-12 h-12 bg-yellow-50 text-yellow-600 rounded-full flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center justify-between border-b-4 border-green-500">
            <div><p class="text-xs font-bold text-gray-500 uppercase">Resolved</p><p class="text-3xl font-black text-gray-900"><?= $stats['resolved'] ?></p></div>
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-full flex items-center justify-center"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50"><h3 class="font-bold text-gray-900">Support & Asset History</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <th class="px-6 py-4">Ticket ID</th>
                        <th class="px-6 py-4">Subject & Type</th>
                        <th class="px-6 py-4">Priority</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if(empty($tickets)): ?>
                        <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">No IT requests found. Click 'New IT Request' to submit one.</td></tr>
                    <?php else: ?>
                        <?php foreach($tickets as $t): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-mono text-sm font-bold text-slate-600">IT-<?= str_pad($t['id'], 4, '0', STR_PAD_LEFT) ?></td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900"><?= sanitize($t['subject']) ?></p>
                                    
                                    <div class="flex items-center gap-2 mt-1">
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider"><?= str_replace('_', ' ', $t['ticket_type']) ?></p>
                                        <?php if($t['ticket_type'] === 'asset_request' && !empty($t['asset_type'])): ?>
                                            <span class="px-2 py-0.5 bg-blue-50 border border-blue-200 text-blue-700 text-[9px] font-black uppercase tracking-widest rounded flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                                                <?= str_replace('_', ' ', $t['asset_type']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                </td>
                                <td class="px-6 py-4">
                                    <?php $p_color = $t['priority'] == 'critical' ? 'text-red-600' : ($t['priority'] == 'high' ? 'text-orange-500' : 'text-blue-500'); ?>
                                    <span class="text-xs font-black uppercase tracking-wider <?= $p_color ?>"><?= sanitize($t['priority']) ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if($t['status'] == 'open'): ?><span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-bold uppercase rounded-md">Open</span>
                                    <?php elseif($t['status'] == 'in_progress'): ?><span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase rounded-md">In Progress</span>
                                    <?php else: ?><span class="px-2.5 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase rounded-md">Resolved</span><?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= date('M d, Y', strtotime($t['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    <?php if (Session::get('flash_success')): ?>
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm">
            <p class="text-sm text-green-700 font-bold"><?= Session::get('flash_success'); Session::set('flash_success', null); ?></p>
        </div>
    <?php endif; ?>

    <?php if (Session::get('flash_error')): ?>
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
            <p class="text-sm text-red-700 font-bold"><?= Session::get('flash_error'); Session::set('flash_error', null); ?></p>
        </div>
    <?php endif; ?>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 bg-gradient-to-r from-indigo-700 to-purple-600 rounded-2xl p-8 shadow-lg text-white">
        <div>
            <p class="text-indigo-200 text-sm font-bold uppercase tracking-wider mb-1"><?= date('l, F j, Y') ?></p>
            <h1 class="text-3xl font-black tracking-tight">Business Development Dashboard 🚀</h1>
            <p class="text-indigo-100 mt-2 text-sm font-medium">Drive growth, manage high-value leads, and monitor conversion velocity.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="<?= base_url('/leads/create') ?>" class="px-6 py-3 bg-white text-indigo-700 hover:bg-indigo-50 font-black rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create New Lead
            </a>
            <a href="<?= base_url('/bd/createProposal') ?>" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-black rounded-xl transition flex items-center gap-2 border border-white/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                New Proposal
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase">Total Leads</p>
                <p class="text-2xl font-black text-gray-900"><?= (int) ($stats['total'] ?? 0) ?></p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4 border-b-4 border-yellow-400">
            <div class="w-12 h-12 rounded-lg bg-yellow-50 text-yellow-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase">Conversion Rate</p>
                <p class="text-2xl font-black text-gray-900"><?= (int) ($stats['conversion'] ?? 0) ?>%</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase">Active Proposals</p>
                <p class="text-2xl font-black text-gray-900"><?= (int) ($stats['active'] ?? 0) ?></p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-red-50 text-red-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase">Meetings Today</p>
                <p class="text-2xl font-black text-gray-900"><?= count($upcoming_meetings ?? []) ?></p>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="<?= base_url('/bd/leads') ?>" class="bg-gradient-to-r from-indigo-800 to-indigo-900 rounded-2xl p-6 shadow-sm hover:shadow-lg transition transform hover:-translate-y-1 group flex flex-col items-center justify-center text-center">
            <div class="w-14 h-14 bg-white/10 rounded-full flex items-center justify-center text-indigo-300 group-hover:scale-110 transition mb-3">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l2 2h1a2 2 0 012 2v10a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-lg font-black text-white tracking-tight">Leads Manager</h3>
            <p class="text-indigo-200 text-xs mt-1">Nurture and track prospects.</p>
        </a>

        <a href="<?= base_url('/bd/proposals') ?>" class="bg-gradient-to-r from-purple-800 to-purple-900 rounded-2xl p-6 shadow-sm hover:shadow-lg transition transform hover:-translate-y-1 group flex flex-col items-center justify-center text-center">
            <div class="w-14 h-14 bg-white/10 rounded-full flex items-center justify-center text-purple-300 group-hover:scale-110 transition mb-3">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-lg font-black text-white tracking-tight">Proposals</h3>
            <p class="text-purple-200 text-xs mt-1">Review and status updates.</p>
        </a>

        <a href="<?= base_url('/bd/security') ?>" class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl p-6 shadow-sm hover:shadow-lg transition transform hover:-translate-y-1 group flex flex-col items-center justify-center text-center border border-slate-700">
            <div class="w-14 h-14 bg-white/10 rounded-full flex items-center justify-center text-slate-300 group-hover:scale-110 transition mb-3">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h3 class="text-lg font-black text-white tracking-tight">Account Security</h3>
            <p class="text-slate-200 text-xs mt-1">Password & credentials.</p>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-gradient-to-br from-indigo-50 to-white rounded-xl shadow-sm border border-indigo-100 p-6">
                <h3 class="font-bold text-indigo-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Growth Suggestions
                </h3>
                <ul class="space-y-3">
                    <li class="flex items-start gap-2 text-sm text-indigo-800"><span class="text-green-500 font-black">&#8226;</span> Lead conversion is up 5% this week.</li>
                    <li class="flex items-start gap-2 text-sm text-indigo-800"><span class="text-amber-500 font-black">&#8226;</span> <?= (int) ($proposal_summary['pending'] ?? 0) ?> proposals are currently awaiting approval.</li>
                </ul>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-900">BD Management Hub</h3>
                </div>
                <div class="p-3 grid grid-cols-2 gap-2">
                    <a href="<?= base_url('/bd/projects') ?>" class="p-4 rounded-xl hover:bg-blue-50 transition flex flex-col items-center justify-center text-center gap-3 border border-transparent hover:border-blue-100">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path></svg></div>
                        <span class="text-xs font-black text-gray-700 uppercase tracking-wider">Project List</span>
                    </a>
                    <a href="<?= base_url('/bd/clients') ?>" class="p-4 rounded-xl hover:bg-emerald-50 transition flex flex-col items-center justify-center text-center gap-3 border border-transparent hover:border-emerald-100">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857"></path></svg></div>
                        <span class="text-xs font-black text-gray-700 uppercase tracking-wider">Clients</span>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="font-bold text-gray-900 mb-1">Proposal Documents</h4>
                <p class="text-[10px] text-gray-400 font-bold uppercase mb-4 tracking-wider">Upload from each proposal row</p>
                <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 p-5">
                    <p class="text-sm font-semibold text-gray-700">Documents are now attached directly to proposals.</p>
                    <p class="mt-2 text-[11px] text-gray-500">Use the Upload Doc button beside each proposal so the admin panel receives the file together with the correct project name.</p>
                    <a href="<?= base_url('/bd/proposals') ?>" class="mt-4 inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-700 transition">
                        Open Proposal Pipeline
                    </a>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-gray-900">Project Proposal Tracker</h3>
                    <span class="text-xs font-bold text-gray-400 uppercase">Live Pipeline</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                <th class="px-6 py-4">Client / Company</th>
                                <th class="px-6 py-4">Project</th>
                                <th class="px-6 py-4">Amount</th>
                                <th class="px-6 py-4">Proposal Date</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php if (empty($proposals)): ?>
                                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">No active proposals found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($proposals as $p): ?>
                                    <?php
                                        $status = strtolower((string) ($p['status'] ?? 'pending'));
                                        $statusColor = 'bg-amber-100 text-amber-700';
                                        if ($status === 'approved') {
                                            $statusColor = 'bg-emerald-100 text-emerald-700';
                                        } elseif ($status === 'rejected') {
                                            $statusColor = 'bg-rose-100 text-rose-700';
                                        }
                                    ?>
                                    <tr class="hover:bg-gray-50 transition group">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-900"><?= sanitize($p['client_name'] ?? 'Unknown Client') ?></div>
                                            <div class="text-[11px] text-gray-500">Proposal #<?= (int) ($p['id'] ?? 0) ?></div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 font-medium"><?= sanitize($p['project_name'] ?? 'Untitled Project') ?></td>
                                        <td class="px-6 py-4 text-sm font-bold text-gray-900">Rs. <?= number_format((float) ($p['amount'] ?? 0), 2) ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?= !empty($p['created_at']) ? date('M d, Y', strtotime($p['created_at'])) : 'N/A' ?></td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase <?= $statusColor ?>">
                                                <?= sanitize($status) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <form action="<?= base_url('/bd/uploadProposalDocument/' . (int) ($p['id'] ?? 0)) ?>" method="POST" enctype="multipart/form-data" class="inline-flex items-center">
                                                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                                    <label class="text-xs font-bold text-emerald-700 hover:text-white hover:bg-emerald-600 border border-emerald-200 px-3 py-1.5 rounded-lg transition cursor-pointer">
                                                        Upload Doc
                                                        <input type="file" class="hidden" name="proposal_doc" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="this.form.submit()">
                                                    </label>
                                                </form>
                                                <a href="<?= base_url('/bd/editProposal/' . (int) ($p['id'] ?? 0)) ?>" class="text-xs font-bold text-slate-700 hover:text-white hover:bg-slate-700 border border-slate-200 px-3 py-1.5 rounded-lg transition">
                                                    Edit
                                                </a>
                                                <?php if ($status === 'approved'): ?>
                                                    <form action="<?= base_url('/bd/sendToAdmin') ?>" method="POST">
                                                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                                        <input type="hidden" name="proposal_id" value="<?= (int) ($p['id'] ?? 0) ?>">
                                                        <button type="submit" class="text-xs font-bold text-indigo-600 hover:text-white hover:bg-indigo-600 border border-indigo-200 px-3 py-1.5 rounded-lg transition">
                                                            Send to Admin
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="text-xs text-gray-300 italic font-medium"><?= $status === 'rejected' ? 'Not Eligible' : 'Awaiting Approval' ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end">
                    <a href="<?= base_url('/bd/proposals') ?>" class="text-sm font-bold text-indigo-700 hover:text-indigo-900 transition">View full proposal pipeline</a>
                </div>
            </div>
        </div>
    </div>
</div>

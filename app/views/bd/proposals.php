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

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 bg-gradient-to-r from-purple-800 to-indigo-800 rounded-2xl p-8 shadow-lg text-white">
        <div>
            <p class="text-purple-200 text-sm font-bold uppercase tracking-wider mb-1">Proposal Pipeline</p>
            <h1 class="text-3xl font-black tracking-tight">Manage Client Proposals</h1>
            <p class="text-purple-100 mt-2 text-sm font-medium">Create, review, and track every proposal from one reliable queue.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="<?= base_url('/bd/dashboard') ?>" class="px-5 py-3 bg-white/10 hover:bg-white/20 text-white font-black rounded-xl transition">
                Back to Dashboard
            </a>
            <a href="<?= base_url('/bd/createProposal') ?>" class="px-5 py-3 bg-white text-purple-800 hover:bg-purple-50 font-black rounded-xl shadow-md transition">
                + Create Proposal
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <p class="text-xs font-bold text-gray-500 uppercase">Total Proposals</p>
            <p class="mt-2 text-3xl font-black text-gray-900"><?= (int) ($proposal_summary['total'] ?? 0) ?></p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-amber-100 p-5">
            <p class="text-xs font-bold text-amber-700 uppercase">Pending</p>
            <p class="mt-2 text-3xl font-black text-amber-600"><?= (int) ($proposal_summary['pending'] ?? 0) ?></p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-emerald-100 p-5">
            <p class="text-xs font-bold text-emerald-700 uppercase">Approved</p>
            <p class="mt-2 text-3xl font-black text-emerald-600"><?= (int) ($proposal_summary['approved'] ?? 0) ?></p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-rose-100 p-5">
            <p class="text-xs font-bold text-rose-700 uppercase">Rejected</p>
            <p class="mt-2 text-3xl font-black text-rose-600"><?= (int) ($proposal_summary['rejected'] ?? 0) ?></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/80 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-gray-900">Proposal Register</h2>
                <p class="text-sm text-gray-500">Every proposal record is shown below with client, project, value, status, and creation date.</p>
            </div>
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400"><?= count($proposals ?? []) ?> record(s)</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4">Client / Company</th>
                        <th class="px-6 py-4">Project</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Created</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($proposals)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center">
                                <p class="text-gray-500 font-semibold">No proposals found yet.</p>
                                <p class="text-sm text-gray-400 mt-1">Create the first proposal to start populating the pipeline.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($proposals as $proposal): ?>
                            <?php
                                $status = strtolower((string) ($proposal['status'] ?? 'pending'));
                                $statusClasses = 'bg-amber-100 text-amber-700';
                                if ($status === 'approved') {
                                    $statusClasses = 'bg-emerald-100 text-emerald-700';
                                } elseif ($status === 'rejected') {
                                    $statusClasses = 'bg-rose-100 text-rose-700';
                                }
                            ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900"><?= sanitize($proposal['client_name'] ?? 'Unknown Client') ?></div>
                                    <div class="text-xs text-gray-500 mt-1">Proposal #<?= (int) ($proposal['id'] ?? 0) ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800"><?= sanitize($proposal['project_name'] ?? 'Untitled Project') ?></div>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900">Rs. <?= number_format((float) ($proposal['amount'] ?? 0), 2) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?= !empty($proposal['created_at']) ? date('M d, Y h:i A', strtotime($proposal['created_at'])) : 'N/A' ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase <?= $statusClasses ?>">
                                        <?= sanitize($status) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <form action="<?= base_url('/bd/uploadProposalDocument/' . (int) ($proposal['id'] ?? 0)) ?>" method="POST" enctype="multipart/form-data" class="inline-flex items-center">
                                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                            <label class="inline-flex items-center px-4 py-2 rounded-lg border border-emerald-200 text-emerald-700 hover:bg-emerald-600 hover:text-white transition text-sm font-bold cursor-pointer">
                                                Upload Doc
                                                <input type="file" class="hidden" name="proposal_doc" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="this.form.submit()">
                                            </label>
                                        </form>
                                        <a href="<?= base_url('/bd/editProposal/' . (int) ($proposal['id'] ?? 0)) ?>" class="inline-flex items-center px-4 py-2 rounded-lg border border-indigo-200 text-indigo-700 hover:bg-indigo-700 hover:text-white transition text-sm font-bold">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

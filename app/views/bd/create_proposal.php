<div class="max-w-5xl mx-auto pb-12 px-4 md:px-0">
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

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <p class="text-sm font-bold uppercase tracking-wider text-purple-600"><?= sanitize($page_label ?? 'New Proposal') ?></p>
            <h1 class="text-3xl font-black text-gray-900 mt-1"><?= sanitize($page_heading ?? 'Create a client-ready proposal') ?></h1>
            <p class="text-sm text-gray-500 mt-2">This form writes directly to the production proposal tracker used on the BD dashboard.</p>
        </div>
        <a href="<?= base_url('/bd/proposals') ?>" class="px-5 py-3 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 font-black rounded-xl transition">
            View All Proposals
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
            <form method="POST" action="<?= $form_action ?? base_url('/bd/storeProposal') ?>" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="client_name" class="block text-sm font-bold text-gray-700 mb-2">Client / Company</label>
                        <input
                            id="client_name"
                            type="text"
                            name="client_name"
                            placeholder="Enter client or company name"
                            value="<?= sanitize($proposal['client_name'] ?? '') ?>"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-900 focus:border-purple-500 focus:ring-2 focus:ring-purple-100 outline-none transition"
                            required
                        >
                    </div>

                    <div>
                        <label for="project_name" class="block text-sm font-bold text-gray-700 mb-2">Project Name</label>
                        <input
                            id="project_name"
                            type="text"
                            name="project_name"
                            placeholder="Enter project name"
                            value="<?= sanitize($proposal['project_name'] ?? '') ?>"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-900 focus:border-purple-500 focus:ring-2 focus:ring-purple-100 outline-none transition"
                            required
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="amount" class="block text-sm font-bold text-gray-700 mb-2">Proposal Amount</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rs.</span>
                            <input
                                id="amount"
                                type="number"
                                name="amount"
                                min="0"
                                step="0.01"
                                placeholder="0.00"
                                value="<?= isset($proposal['amount']) ? number_format((float) $proposal['amount'], 2, '.', '') : '' ?>"
                                class="w-full rounded-xl border border-gray-300 bg-white pl-12 pr-4 py-3 text-gray-900 focus:border-purple-500 focus:ring-2 focus:ring-purple-100 outline-none transition"
                                required
                            >
                        </div>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-bold text-gray-700 mb-2">Initial Status</label>
                        <select
                            id="status"
                            name="status"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-900 focus:border-purple-500 focus:ring-2 focus:ring-purple-100 outline-none transition"
                        >
                            <option value="pending" <?= (($proposal['status'] ?? 'pending') === 'pending') ? 'selected' : '' ?>>Pending</option>
                            <option value="approved" <?= (($proposal['status'] ?? '') === 'approved') ? 'selected' : '' ?>>Approved</option>
                            <option value="rejected" <?= (($proposal['status'] ?? '') === 'rejected') ? 'selected' : '' ?>>Rejected</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="px-6 py-3 bg-purple-700 text-white hover:bg-purple-800 font-black rounded-xl shadow-sm transition">
                        <?= sanitize($submit_label ?? 'Save Proposal') ?>
                    </button>
                    <a href="<?= base_url('/bd/dashboard') ?>" class="px-6 py-3 bg-gray-100 text-gray-700 hover:bg-gray-200 font-black rounded-xl transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <div class="space-y-6">
            <div class="bg-gradient-to-br from-purple-700 to-indigo-800 rounded-2xl p-6 text-white shadow-lg">
                <p class="text-sm font-bold uppercase tracking-wider text-purple-200">Pipeline Summary</p>
                <div class="mt-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-purple-100">Total proposals</span>
                        <span class="text-2xl font-black"><?= (int) ($proposal_summary['total'] ?? 0) ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-purple-100">Pending</span>
                        <span class="text-xl font-black"><?= (int) ($proposal_summary['pending'] ?? 0) ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-purple-100">Approved</span>
                        <span class="text-xl font-black"><?= (int) ($proposal_summary['approved'] ?? 0) ?></span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-black text-gray-900">What this updates</h2>
                <ul class="mt-4 space-y-3 text-sm text-gray-600">
                    <li>The proposal appears in the Project Proposal Tracker on the BD dashboard.</li>
                    <li>The proposal also appears in the dedicated proposal pipeline page.</li>
                    <li>Approved proposals can be pushed to admin review directly from the tracker.</li>
                    <li>You can revisit any saved proposal later and update it from the edit action.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

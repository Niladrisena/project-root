<div class="max-w-4xl mx-auto pb-12">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Create New Project</h1>
            <p class="text-sm text-gray-500 mt-1">Initialize project parameters and management assignments.</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= base_url('/project') ?>" class="text-sm font-medium text-gray-600 hover:text-gray-900 bg-white border border-gray-300 px-4 py-2 rounded-lg shadow-sm transition">
                &larr; Back to Projects
            </a>
        </div>
    </div>

    <?php if (Session::get('flash_error')): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md shadow-sm">
            <p class="text-sm text-red-700 font-medium"><?= Session::get('flash_error'); Session::set('flash_error', null); ?></p>
        </div>
    <?php endif; ?>

    <?php
        /**
         * ✅ FIX: Derive currency display values ONCE at the top of the view,
         * reading exclusively from the SYS_ global constants set by SystemConfig.
         * Never hardcode '$' or 'USD' anywhere below this block.
         */
        $view_currency_symbol = defined('SYS_CURRENCY')      ? htmlspecialchars(SYS_CURRENCY, ENT_QUOTES, 'UTF-8')      : '$';
        $view_currency_code   = defined('SYS_CURRENCY_CODE') ? htmlspecialchars(SYS_CURRENCY_CODE, ENT_QUOTES, 'UTF-8') : 'USD';
    ?>

    <form action="<?= base_url('/project/create') ?>" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

        <div class="p-8 space-y-10">

            <!-- SECTION 1: Project Information -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-5">1. Project Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Project Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" required placeholder="e.g., Q3 Marketing Campaign"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Project Description</label>
                        <textarea name="description" rows="3" placeholder="Brief overview of the project scope..."
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition custom-scrollbar"></textarea>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Assignments & Workflow -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-5">2. Assignments & Workflow</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Assign to Client <span class="text-red-500">*</span>
                        </label>
                        <select name="client_id" id="client_id_select" onchange="toggleNewClient()" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                            <option value="" disabled selected>-- Select an Existing Client --</option>
                            <option value="new" class="font-bold text-blue-700 bg-blue-50">➕ Create New Client Inline</option>
                            <?php if (!empty($clients)): ?>
                                <?php foreach ($clients as $client): ?>
                                    <option value="<?= sanitize($client['id']) ?>">
                                        <?= sanitize($client['display_name'] ?? 'Client #' . $client['id']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>

                        <div id="new_client_box" class="hidden mt-4 p-4 bg-blue-50/50 border border-blue-200 rounded-xl shadow-inner space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-blue-800 mb-1">
                                    New Client / Company Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="new_client_name" id="new_client_name"
                                       placeholder="e.g., Acme Corporation"
                                       class="w-full px-3 py-2 border border-blue-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-blue-800 mb-1">
                                        Contact Person <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="new_client_contact_person" id="new_client_contact_person"
                                           placeholder="e.g., John Smith"
                                           class="w-full px-3 py-2 border border-blue-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-blue-800 mb-1">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="new_client_email" id="new_client_email"
                                           placeholder="e.g., contact@acme.com"
                                           class="w-full px-3 py-2 border border-blue-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Assign Project Manager <span class="text-red-500">*</span>
                        </label>
                        <select name="project_manager_id" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                            <?php if (!empty($managers)): ?>
                                <?php foreach ($managers as $mgr): ?>
                                    <option value="<?= sanitize($mgr['id']) ?>"
                                        <?= Session::get('user_id') == $mgr['id'] ? 'selected' : '' ?>>
                                        <?= sanitize($mgr['first_name'] . ' ' . $mgr['last_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="<?= Session::get('user_id') ?>" selected>Current Logged In User</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Project Scope</label>
                        <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                            <option value="planning">Planning & Strategy</option>
                            <option value="in_progress">In Progress</option>
                            <option value="on_hold">On Hold</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Priority Level</label>
                        <select name="priority" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent" class="text-red-600 font-bold">Firstrack</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Timeline & Financials -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-5">3. Timeline & Financials</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" required value="<?= date('Y-m-d') ?>"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-gray-700">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Deadline <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="deadline" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-gray-700">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Total Estimated Hours</label>
                        <input type="number" step="0.5" name="total_hours" placeholder="0.0"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Estimated Budget</label>
                        <div class="relative">
                            
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-900 font-bold text-lg">
                                <?= $view_currency_symbol ?>
                            </span>
                            <input type="number" step="0.01" name="estimated_budget" placeholder="0.00"
                                   class="w-full pl-9 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">System Currency Link</label>
                       
                        <input type="text"
                               value="<?= $view_currency_code ?> - (Global Database Link)"
                               readonly
                               class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50 rounded-lg text-sm font-bold text-gray-600 cursor-not-allowed">
                    </div>

                </div>
            </div>

        </div>

        <div class="bg-gray-50 px-8 py-5 border-t border-gray-200 flex justify-end gap-3">
            <a href="<?= base_url('/project') ?>"
               class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg shadow-sm text-sm font-bold hover:bg-gray-100 transition">
                Cancel
            </a>
            <button type="submit"
                    class="px-8 py-2.5 bg-blue-600 text-white rounded-lg shadow-sm text-sm font-bold hover:bg-blue-700 transition focus:ring-4 focus:ring-blue-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Initialize & Assign Project
            </button>
        </div>

    </form>
</div>

<script>
    function toggleNewClient() {
        const select = document.getElementById('client_id_select');
        const box    = document.getElementById('new_client_box');
        const nameInput = document.getElementById('new_client_name');
        const contactInput = document.getElementById('new_client_contact_person');
        const emailInput = document.getElementById('new_client_email');

        if (select.value === 'new') {
            box.classList.remove('hidden');
            nameInput.required = true;
            contactInput.required = true;
            emailInput.required = true;
        } else {
            box.classList.add('hidden');
            nameInput.required = false;
            contactInput.required = false;
            emailInput.required = false;
            nameInput.value    = '';
            contactInput.value = '';
            emailInput.value   = '';
        }
    }
</script>

<div class="max-w-4xl mx-auto pb-12 px-4 md:px-0">
    <?php
        $employeeSuggestions = $employeeSuggestions ?? [];
        $employeeSuggestionItems = array_values(array_filter(array_map(function ($employee) {
            $first = trim((string) ($employee['first_name'] ?? ''));
            $last = trim((string) ($employee['last_name'] ?? ''));
            $name = trim($first . ' ' . $last);
            $email = trim((string) ($employee['email'] ?? ''));

            if ($name === '' && $email === '') {
                return null;
            }

            return [
                'id' => (int) ($employee['id'] ?? 0),
                'name' => $name !== '' ? $name : $email,
                'email' => $email,
                'label' => $email !== '' ? (($name !== '' ? $name : $email) . ' (' . $email . ')') : ($name !== '' ? $name : 'Employee')
            ];
        }, $employeeSuggestions)));
    ?>
    <div class="mb-8 flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-slate-800 text-white rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Submit IT or Asset Request</h1>
                <p class="text-sm text-gray-500 mt-0.5">Provision hardware, request software licenses, or report outages.</p>
            </div>
        </div>
        <a href="<?= base_url('/pm/it_desk') ?>" class="px-5 py-2.5 bg-gray-50 border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-100 transition">Cancel</a>
    </div>

    <form action="<?= base_url('/pm/request_it_support') ?>" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <div class="p-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Request Category <span class="text-red-500">*</span></label>
                    <select name="ticket_type" id="ticket_type" required onchange="toggleAssetCatalog()" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-slate-800 outline-none transition cursor-pointer">
                        <option value="technical_issue">Technical Support (Bugs, Crashes)</option>
                        <option value="asset_request">Hardware / Asset Provisioning</option>
                        <option value="software_access">Software License / Access Request</option>
                        <option value="other">Other Inquiry</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Impact Priority <span class="text-red-500">*</span></label>
                    <select name="priority" required class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-slate-800 outline-none transition cursor-pointer">
                        <option value="low">Low (Non-urgent request)</option>
                        <option value="medium" selected>Medium (Standard issue)</option>
                        <option value="high">High (Blocking work for 1 person)</option>
                        <option value="critical">Critical (System down / Blocking team)</option>
                    </select>
                </div>

                <div id="asset_catalog" class="md:col-span-2 hidden bg-blue-50 border border-blue-200 p-5 rounded-xl transition-all duration-300">
                    <label class="block text-xs font-bold text-blue-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                        Hardware Asset Selection <span class="text-red-500">*</span>
                    </label>
                    <select name="asset_type" id="asset_type" class="w-full px-4 py-3 bg-white border border-blue-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-600 outline-none transition cursor-pointer custom-scrollbar">
                        <option value="">-- Select Specific Asset Required --</option>
                        <optgroup label="Computers & Displays">
                            <option value="laptop_mac">Laptop (Apple MacBook)</option>
                            <option value="laptop_pc">Laptop (Windows PC)</option>
                            <option value="monitor_single">External Monitor (Single)</option>
                            <option value="monitor_dual">External Monitors (Dual Setup)</option>
                            <option value="tablet">Tablet / iPad</option>
                        </optgroup>
                        <optgroup label="Peripherals & Input">
                            <option value="mouse_wireless">Wireless Mouse</option>
                            <option value="keyboard_mechanical">Keyboard (Mechanical)</option>
                            <option value="keyboard_standard">Keyboard (Standard)</option>
                            <option value="headset_noise_cancelling">Headset (Noise Cancelling)</option>
                        </optgroup>
                        <optgroup label="Accessories & Power">
                            <option value="charger_laptop">Laptop Charger / Adapter</option>
                            <option value="mobile_phone">Corporate Mobile Phone</option>
                            <option value="extension_board">Power Extension Board / Surge Protector</option>
                            <option value="laptop_bag">Corporate Laptop Bag</option>
                            <option value="usb_hub">USB-C Hub / Docking Station</option>
                        </optgroup>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Subject / Employee Name <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input
                            type="text"
                            id="subject"
                            name="subject"
                            required
                            autocomplete="off"
                            placeholder="e.g., Provisioning Apple MacBook for New Hire (John Doe)"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-slate-800 outline-none transition"
                        >
                        <div
                            id="employee_suggestions"
                            class="hidden absolute z-20 mt-2 w-full max-h-80 overflow-y-auto bg-white border border-gray-200 rounded-xl shadow-lg"
                        ></div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Click the field or type an employee name/email to pick quickly.</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Detailed Justification & Specs <span class="text-red-500">*</span></label>
                    <textarea name="description" required rows="5" placeholder="Provide URLs, hardware specs (e.g., 16GB RAM), or shipping address..." class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-slate-800 outline-none transition resize-none"></textarea>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 px-8 py-5 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-8 py-3.5 bg-slate-800 hover:bg-slate-900 text-white font-black rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                Submit Request to IT
            </button>
        </div>
    </form>
</div>

<script>
    const employeeSuggestionData = <?= json_encode($employeeSuggestionItems, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

    function toggleAssetCatalog() {
        const typeSelect = document.getElementById('ticket_type');
        const catalogDiv = document.getElementById('asset_catalog');
        const assetSelect = document.getElementById('asset_type');

        if (typeSelect.value === 'asset_request') {
            catalogDiv.classList.remove('hidden');
            assetSelect.setAttribute('required', 'required'); // Force them to pick an item!
        } else {
            catalogDiv.classList.add('hidden');
            assetSelect.removeAttribute('required');
            assetSelect.value = ''; // Clear it out
        }
    }

    (function setupEmployeeSuggestions() {
        const subjectInput = document.getElementById('subject');
        const suggestionBox = document.getElementById('employee_suggestions');

        if (!subjectInput || !suggestionBox) {
            return;
        }

        function escapeHtml(value) {
            return String(value).replace(/[&<>"']/g, function (char) {
                return ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                })[char];
            });
        }

        function renderSuggestions(query) {
            const normalizedQuery = (query || '').trim().toLowerCase();
            const filtered = employeeSuggestionData.filter(function (employee) {
                if (!normalizedQuery) {
                    return true;
                }

                return employee.label.toLowerCase().includes(normalizedQuery)
                    || employee.name.toLowerCase().includes(normalizedQuery)
                    || employee.email.toLowerCase().includes(normalizedQuery);
            });

            if (!filtered.length) {
                suggestionBox.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500">No employee found.</div>';
                suggestionBox.classList.remove('hidden');
                return;
            }

            suggestionBox.innerHTML = filtered.map(function (employee) {
                return '<button type="button" class="employee-suggestion-item w-full px-4 py-3 text-left hover:bg-slate-50 transition border-b border-gray-100 last:border-b-0" data-value="' + escapeHtml(employee.name) + '">' +
                    '<div class="text-sm font-semibold text-gray-900">' + escapeHtml(employee.name) + '</div>' +
                    '<div class="text-xs text-gray-500">' + escapeHtml(employee.email) + '</div>' +
                '</button>';
            }).join('');

            suggestionBox.classList.remove('hidden');
        }

        subjectInput.addEventListener('focus', function () {
            renderSuggestions(subjectInput.value);
        });

        subjectInput.addEventListener('input', function () {
            renderSuggestions(subjectInput.value);
        });

        suggestionBox.addEventListener('click', function (event) {
            const option = event.target.closest('.employee-suggestion-item');
            if (!option) {
                return;
            }

            subjectInput.value = option.getAttribute('data-value') || '';
            suggestionBox.classList.add('hidden');
            subjectInput.focus();
        });

        document.addEventListener('click', function (event) {
            if (!subjectInput.contains(event.target) && !suggestionBox.contains(event.target)) {
                suggestionBox.classList.add('hidden');
            }
        });
    })();
</script>

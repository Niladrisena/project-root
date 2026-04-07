<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0 relative">
    
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-200 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-900 text-white rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Asset Fleet Management</h1>
                <p class="text-sm text-gray-500 mt-0.5">Track hardware lifecycle, assignments, and damage reports.</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="<?= base_url('/it/dashboard') ?>" class="px-5 py-2.5 bg-gray-50 border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-100 transition">Dashboard</a>
            <button onclick="toggleModal('register-modal')" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Register Asset
            </button>
        </div>
    </div>

    <?php if (Session::get('flash_success')): ?>
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm"><p class="text-sm text-green-700 font-bold"><?= Session::get('flash_success'); Session::set('flash_success', null); ?></p></div>
    <?php endif; ?>
    <?php if (Session::get('flash_error')): ?>
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm"><p class="text-sm text-red-700 font-bold"><?= Session::get('flash_error'); Session::set('flash_error', null); ?></p></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-b-4 border-gray-800">
            <p class="text-xs font-bold text-gray-500 uppercase mb-1">Total Assets</p><p class="text-3xl font-black text-gray-900"><?= $stats['total'] ?? 0 ?></p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-b-4 border-blue-500">
            <p class="text-xs font-bold text-gray-500 uppercase mb-1">Currently Deployed</p><p class="text-3xl font-black text-gray-900"><?= $stats['deployed'] ?? 0 ?></p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-b-4 border-green-500">
            <p class="text-xs font-bold text-gray-500 uppercase mb-1">In Inventory (Ready)</p><p class="text-3xl font-black text-gray-900"><?= $stats['available'] ?? 0 ?></p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-b-4 border-red-500">
            <p class="text-xs font-bold text-gray-500 uppercase mb-1">Needs Repair / Damaged</p><p class="text-3xl font-black text-gray-900"><?= $stats['repairs'] ?? 0 ?></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-900">Corporate Hardware Ledger</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <th class="px-6 py-4">Asset Tag</th>
                        <th class="px-6 py-4">Hardware Details</th>
                        <th class="px-6 py-4">Current Status</th>
                        <th class="px-6 py-4">Assignment</th>
                        <th class="px-6 py-4 text-right">Lifecycle Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if(empty($assets)): ?>
                        <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500 font-medium">No assets registered in the corporate ledger.</td></tr>
                    <?php else: ?>
                        <?php foreach($assets as $a): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-mono text-sm font-bold text-blue-600"><?= $a['asset_tag'] ?></td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900"><?= sanitize($a['brand_model']) ?></p>
                                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mt-0.5"><?= sanitize(str_replace('_', ' ', $a['category'])) ?> &bull; S/N: <?= sanitize($a['serial_number'] ?: 'N/A') ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <?php 
                                        if($a['status'] == 'available') echo '<span class="px-2.5 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase rounded">Available</span>';
                                        elseif($a['status'] == 'deployed') echo '<span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-[10px] font-black uppercase rounded">Deployed</span>';
                                        elseif($a['status'] == 'damaged') echo '<span class="px-2.5 py-1 bg-red-100 text-red-700 text-[10px] font-black uppercase rounded animate-pulse">Damaged</span>';
                                        else echo '<span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-[10px] font-black uppercase rounded">'.$a['status'].'</span>';
                                    ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if($a['assigned_to']): ?>
                                        <p class="text-sm font-bold text-gray-900"><?= sanitize($a['first_name'].' '.$a['last_name']) ?></p>
                                        <p class="text-[10px] text-gray-400 font-medium"><?= sanitize($a['email']) ?></p>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400 italic">In IT Storage</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <?php if($a['status'] == 'available'): ?>
                                        <button onclick="openActionModal(<?= $a['id'] ?>, 'assign', '<?= $a['asset_tag'] ?>')" class="text-xs font-bold text-blue-600 hover:underline">Deploy Asset</button>
                                    <?php elseif($a['status'] == 'deployed'): ?>
                                        <button onclick="openActionModal(<?= $a['id'] ?>, 'return', '<?= $a['asset_tag'] ?>')" class="text-xs font-bold text-green-600 hover:underline">Log Return</button>
                                        <button onclick="openActionModal(<?= $a['id'] ?>, 'damage', '<?= $a['asset_tag'] ?>')" class="text-xs font-bold text-red-600 hover:underline ml-2">Report Damage</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="register-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-all">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-black text-gray-900">Register Hardware</h3>
                <button onclick="toggleModal('register-modal')" class="text-gray-400 hover:text-red-500 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <form action="<?= base_url('/it/register_asset') ?>" method="POST" class="p-6 space-y-5">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Category <span class="text-red-500">*</span></label>
                    <select name="category" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="laptop">Laptop / Computer</option>
                        <option value="mobile_device">Mobile / Tablet</option>
                        <option value="peripheral">Peripheral (Monitor, Keyboard)</option>
                        <option value="networking">Networking Gear</option>
                        <option value="furniture">Office Furniture</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Make & Model <span class="text-red-500">*</span></label>
                    <input type="text" name="brand_model" required placeholder="e.g. Apple MacBook Pro M3 16GB" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Serial Number</label>
                        <input type="text" name="serial_number" placeholder="Optional" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Purchase Cost ($)</label>
                        <input type="number" step="0.01" name="purchase_cost" placeholder="0.00" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-lg shadow transition mt-2">Add to Inventory</button>
            </form>
        </div>
    </div>

    <div id="action-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 id="action-title" class="font-black text-gray-900">Process Asset</h3>
                <button onclick="toggleModal('action-modal')" class="text-gray-400 hover:text-red-500 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <form action="<?= base_url('/it/process_asset_action') ?>" method="POST" class="p-6 space-y-5">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="asset_id" id="action-asset-id">
                <input type="hidden" name="action_type" id="action-type">

                <div class="p-3 bg-blue-50 text-blue-800 text-sm font-bold rounded-lg mb-4 text-center">
                    Target Asset: <span id="action-asset-tag" class="font-mono bg-white px-2 py-0.5 rounded shadow-sm ml-1">AST-XXX</span>
                </div>

                <div id="assign-block" class="hidden">
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Assign To Employee <span class="text-red-500">*</span></label>
                    <select name="user_id" id="user-select" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select Personnel --</option>
                        <?php foreach($employees as $emp): ?>
                            <option value="<?= $emp['id'] ?>"><?= sanitize($emp['first_name'].' '.$emp['last_name'].' ('.$emp['email'].')') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Lifecycle Notes / Condition <span class="text-red-500">*</span></label>
                    <textarea name="notes" required rows="3" placeholder="Describe the condition (e.g. 'Screen cracked', 'Mint condition')" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                </div>

                <button type="submit" id="action-btn" class="w-full py-3 text-white font-black rounded-lg shadow transition mt-2">Execute Process</button>
            </form>
        </div>
    </div>

</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
    }

    function openActionModal(assetId, actionType, assetTag) {
        document.getElementById('action-asset-id').value = assetId;
        document.getElementById('action-type').value = actionType;
        document.getElementById('action-asset-tag').innerText = assetTag;

        const assignBlock = document.getElementById('assign-block');
        const userSelect = document.getElementById('user-select');
        const title = document.getElementById('action-title');
        const btn = document.getElementById('action-btn');

        if (actionType === 'assign') {
            title.innerText = "Deploy Hardware";
            assignBlock.classList.remove('hidden');
            userSelect.setAttribute('required', 'required');
            btn.innerText = "Authorize Deployment";
            btn.className = "w-full py-3 text-white font-black rounded-lg shadow transition mt-2 bg-blue-600 hover:bg-blue-700";
        } else if (actionType === 'return') {
            title.innerText = "Log Asset Return";
            assignBlock.classList.add('hidden');
            userSelect.removeAttribute('required');
            btn.innerText = "Confirm Safe Return";
            btn.className = "w-full py-3 text-white font-black rounded-lg shadow transition mt-2 bg-green-600 hover:bg-green-700";
        } else if (actionType === 'damage') {
            title.innerText = "Report Hardware Damage";
            assignBlock.classList.add('hidden');
            userSelect.removeAttribute('required');
            btn.innerText = "Flag as Damaged";
            btn.className = "w-full py-3 text-white font-black rounded-lg shadow transition mt-2 bg-red-600 hover:bg-red-700";
        }

        toggleModal('action-modal');
    }
</script>
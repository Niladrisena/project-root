<div class="max-w-6xl mx-auto pb-12">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manage Permissions: <?= sanitize($role['name']) ?></h1>
            <p class="text-sm text-gray-500 mt-1">Configure module-level access for this role.</p>
        </div>
        <a href="<?= base_url('/roles') ?>" class="text-sm font-medium text-gray-600 hover:text-gray-900">&larr; Back to Roles</a>
    </div>

    <form id="permissionForm" class="space-y-6">
        <input type="hidden" name="csrf_token" id="csrf_token" value="<?= csrf_token() ?>">
        <input type="hidden" name="role_id" value="<?= $role['id'] ?>">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <?php foreach ($modules as $module_name => $permissions): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider"><?= sanitize($module_name) ?></h3>
                    <label class="flex items-center space-x-2 text-xs font-medium text-blue-600 cursor-pointer">
                        <input type="checkbox" class="select-all-module h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500" data-target=".mod-<?= sanitize($module_name) ?>">
                        <span>Select All</span>
                    </label>
                </div>
                <div class="p-6 space-y-4">
                    <?php foreach ($permissions as $perm): ?>
                        <?php $isChecked = in_array($perm['id'], $active_permissions) ? 'checked' : ''; ?>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-700"><?= sanitize(ucwords(str_replace('_', ' ', $perm['action']))) ?></p>
                                <p class="text-xs text-gray-500"><?= sanitize($perm['description']) ?></p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="<?= $perm['id'] ?>" class="sr-only peer mod-<?= sanitize($module_name) ?>" <?= $isChecked ?>>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-40 ml-64">
            <div class="max-w-6xl mx-auto flex justify-end">
                <button type="button" id="savePermissions" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-blue-700 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Save Permissions
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Select All logic
    document.querySelectorAll('.select-all-module').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const targetClass = this.getAttribute('data-target');
            document.querySelectorAll(targetClass).forEach(toggle => {
                toggle.checked = this.checked;
            });
        });
    });

    // AJAX Save
    document.getElementById('savePermissions').addEventListener('click', async function() {
        const form = document.getElementById('permissionForm');
        const formData = new FormData(form);
        const btn = this;
        
        btn.innerHTML = 'Saving...';
        btn.disabled = true;

        try {
            const response = await fetch('<?= base_url('/roles/sync_permissions') ?>', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            if(result.status === 'success') {
                alert('Permissions updated successfully!'); // Replace with a premium toast notification in step 12
            } else {
                alert(result.message);
            }
        } catch (error) {
            alert('A network error occurred.');
        } finally {
            btn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Save Permissions';
            btn.disabled = false;
        }
    });
});
</script>
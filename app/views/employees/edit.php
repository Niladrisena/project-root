<div class="max-w-3xl mx-auto pb-12">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Employee</h1>
        <a href="<?= base_url('/employee') ?>" class="text-sm font-medium text-gray-600 bg-white border border-gray-300 px-4 py-2 rounded-lg shadow-sm">
            Cancel
        </a>
    </div>

    <form action="<?= base_url('/employee/update/' . $employee['id']) ?>" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 space-y-6">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">First Name</label>
                <input type="text" name="first_name" value="<?= sanitize($employee['first_name']) ?>" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Last Name</label>
                <input type="text" name="last_name" value="<?= sanitize($employee['last_name']) ?>" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Phone</label>
                <input type="text" name="phone" value="<?= sanitize($employee['phone']) ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Account Status</label>
                <select name="status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                    <option value="active" <?= $employee['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $employee['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-6 flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-sm transition">
                Save Changes
            </button>
        </div>
    </form>
</div>
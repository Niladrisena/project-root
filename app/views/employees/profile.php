<div class="max-w-7xl mx-auto space-y-6">
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="h-32 bg-gradient-to-r from-blue-700 to-blue-500"></div>
        <div class="px-8 pb-8 relative">
            <div class="flex justify-between items-end -mt-12 mb-6">
                <div class="flex items-end space-x-5">
                    <img src="<?= $profile['avatar'] ?? 'https://ui-avatars.com/api/?name='.urlencode($profile['first_name']) ?>" class="w-24 h-24 rounded-full border-4 border-white shadow-md bg-white">
                    <div class="pb-2">
                        <h1 class="text-2xl font-bold text-gray-900"><?= sanitize($profile['first_name'] . ' ' . $profile['last_name']) ?></h1>
                        <p class="text-sm font-medium text-gray-500"><?= sanitize($profile['designation_name'] ?? 'No Designation') ?> · <?= sanitize($profile['department_name'] ?? 'No Department') ?></p>
                    </div>
                </div>
                <?php if (Auth::can('edit', 'hr')): ?>
                <div class="pb-2">
                    <a href="<?= base_url('/employees/edit/'.$profile['user_id']) ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">Edit Profile</a>
                </div>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 border-t border-gray-100 pt-6">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email Address</p>
                    <p class="text-sm font-medium text-gray-900 mt-1"><?= sanitize($profile['email']) ?></p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</p>
                    <p class="text-sm font-medium text-gray-900 mt-1"><?= sanitize($profile['phone'] ?? 'N/A') ?></p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Joining Date</p>
                    <p class="text-sm font-medium text-gray-900 mt-1"><?= date('F j, Y', strtotime($profile['join_date'])) ?></p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1 <?= $profile['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                        <?= ucfirst($profile['status']) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">Organization Hierarchy</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500">Reporting Manager</p>
                        <p class="text-sm font-medium text-gray-900 flex items-center gap-2 mt-1">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <?= $profile['manager_first'] ? sanitize($profile['manager_first'] . ' ' . $profile['manager_last']) : 'No Manager Assigned' ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">System Role</p>
                        <p class="text-sm font-medium text-gray-900 mt-1"><?= sanitize(ucwords(str_replace('_', ' ', $profile['role_name']))) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-2 space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-gray-900">Document Vault</h3>
                    <?php if (Auth::can('edit', 'hr')): ?>
                    <button onclick="document.getElementById('docModal').classList.remove('hidden')" class="text-sm text-blue-600 font-medium hover:text-blue-800">+ Upload Document</button>
                    <?php endif; ?>
                </div>
                <div class="p-6">
                    <?php if(empty($documents)): ?>
                        <p class="text-sm text-gray-500 text-center py-4">No documents uploaded yet.</p>
                    <?php else: ?>
                        <ul class="divide-y divide-gray-100">
                            <?php foreach($documents as $doc): ?>
                            <li class="py-3 flex justify-between items-center">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900"><?= sanitize($doc['document_type']) ?></p>
                                        <p class="text-xs text-gray-500">Added by <?= sanitize($doc['uploaded_by_name']) ?> on <?= date('M d, Y', strtotime($doc['uploaded_at'])) ?></p>
                                    </div>
                                </div>
                                <a href="<?= base_url('/uploads/employees/'.$doc['file_name']) ?>" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View</a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (Auth::can('view_financials', 'accounts') || Session::get('user_id') == $profile['user_id']): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">Salary Structure Overview</h3>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs text-gray-500">Monthly Base Salary</p>
                        <p class="text-xl font-bold text-gray-900 mt-1">$<?= number_format($profile['monthly_salary'], 2) ?></p>
                    </div>
                    <span class="px-3 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full border border-green-200">Active Pipeline</span>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<div id="docModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Upload Document</h3>
            <button onclick="document.getElementById('docModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form action="<?= base_url('/employees/upload_document') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="employee_id" value="<?= $profile['id'] ?>">
            <input type="hidden" name="user_id" value="<?= $profile['user_id'] ?>">
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Document Type</label>
                <select name="document_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                    <option value="ID Proof">ID Proof (Passport/National ID)</option>
                    <option value="Offer Letter">Offer/Appointment Letter</option>
                    <option value="Resume">Resume / CV</option>
                    <option value="Certificate">Educational Certificate</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">File Attachment</label>
                <input type="file" name="document" required accept=".pdf,.doc,.docx,.jpg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md p-1">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-medium py-2 rounded-lg hover:bg-blue-700 transition">Secure Upload</button>
        </form>
    </div>
</div>
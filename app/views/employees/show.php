<div class="max-w-5xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="flex items-center justify-between mb-6">
        <a href="<?= base_url('/employee') ?>" class="text-sm font-bold text-gray-500 hover:text-gray-900 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Directory
        </a>
        <div class="flex gap-3">
            <?php if(Auth::can('edit', 'hr')): ?>
                <a href="<?= base_url('/employee/edit/' . $employee['id']) ?>" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-50 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    Edit Profile
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        
        <div class="h-32 bg-gradient-to-r from-blue-700 to-indigo-800"></div>
        <div class="px-8 pb-8 relative">
            <div class="flex justify-between items-end -mt-12 mb-6">
                <div class="w-24 h-24 bg-white rounded-2xl p-1 shadow-lg border border-gray-100">
                    <div class="w-full h-full bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-3xl font-black">
                        <?= strtoupper(substr($employee['first_name'] ?? 'U', 0, 1) . substr($employee['last_name'] ?? 'N', 0, 1)) ?>
                    </div>
                </div>
                
                <div class="pb-2">
                    <?php if(($employee['status'] ?? 'active') === 'active'): ?>
                        <span class="px-4 py-1.5 bg-green-50 text-green-700 text-xs font-black uppercase tracking-wider rounded-full border border-green-200 shadow-sm flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Active Employee
                        </span>
                    <?php else: ?>
                        <span class="px-4 py-1.5 bg-red-50 text-red-700 text-xs font-black uppercase tracking-wider rounded-full border border-red-200 shadow-sm">
                            Suspended
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight"><?= sanitize($employee['first_name'] . ' ' . $employee['last_name']) ?></h1>
                <p class="text-lg font-medium text-gray-500 mt-1"><?= sanitize(ucwords(str_replace('_', ' ', $employee['role_slug'] ?? $employee['role_name'] ?? 'Employee'))) ?> &bull; <?= sanitize($employee['department_name'] ?? 'General Dept.') ?></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <div class="space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-5 border-b border-gray-100 pb-3">Contact Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Corporate Email</p>
                        <div class="flex items-center gap-3 text-sm font-medium text-gray-900">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <a href="mailto:<?= sanitize($employee['email']) ?>" class="text-blue-600 hover:underline"><?= sanitize($employee['email']) ?></a>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Phone Number</p>
                        <div class="flex items-center gap-3 text-sm font-medium text-gray-900">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <?= !empty($employee['phone']) ? sanitize($employee['phone']) : '<span class="text-gray-400 italic">Not Provided</span>' ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-5 border-b border-gray-100 pb-3">System Access</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">System Role ID</p>
                        <p class="text-sm font-bold text-gray-900 bg-gray-50 inline-block px-3 py-1 rounded-md border border-gray-200">Role Level <?= sanitize($employee['role_id']) ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Employee ID Number</p>
                        <p class="text-sm font-mono text-gray-900">EMP-<?= str_pad($employee['id'], 4, '0', STR_PAD_LEFT) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:col-span-2 space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6 border-b border-gray-100 pb-3">Corporate & Employment Details</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 flex items-center gap-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Direct Manager
                        </p>
                        <p class="text-sm font-bold text-gray-900">
                            <?= !empty($employee['manager_first']) ? sanitize($employee['manager_first'] . ' ' . $employee['manager_last']) : '<span class="text-gray-400 italic">Top Level (No Manager)</span>' ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 flex items-center gap-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Joining Date
                        </p>
                        <p class="text-sm font-bold text-gray-900">
                            <?= date('F d, Y', strtotime($employee['join_date'] ?? $employee['created_at'] ?? 'now')) ?>
                        </p>
                    </div>

                    <div class="sm:col-span-2 bg-gray-50 rounded-xl p-5 border border-gray-100 mt-2">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Compensation Details
                        </p>
                        <div class="flex items-end gap-3">
                            <h2 class="text-2xl font-black text-gray-900 tracking-tight">$<?= number_format($employee['salary'] ?? 0, 2) ?></h2>
                            <span class="text-sm font-medium text-gray-500 mb-1">/ Annual Base</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
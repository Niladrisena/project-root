<div class="max-w-5xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="flex items-center justify-between mb-8 bg-gradient-to-r from-blue-900 to-indigo-900 rounded-2xl p-8 shadow-lg text-white">
        <div>
            <p class="text-blue-200 text-sm font-bold uppercase tracking-wider mb-1">HR Command Center</p>
            <h1 class="text-3xl font-black tracking-tight">Register New Employee</h1>
            <p class="text-blue-100 mt-2 text-sm">Create a secure system account and map corporate hierarchy.</p>
        </div>
        <a href="<?= base_url('/hr/dashboard') ?>" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl text-sm font-bold text-white transition flex items-center gap-2">
            &larr; Back to Hub
        </a>
    </div>

    <?php if (Session::get('flash_error')): ?>
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm flex items-center gap-3">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <p class="text-sm text-red-700 font-medium"><?= Session::get('flash_error'); Session::set('flash_error', null); ?></p>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/employee/create') ?>" method="POST" class="space-y-8">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></div>
                <h3 class="font-bold text-gray-900">1. Personal Details</h3>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" required placeholder="e.g., Sarah" 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" required placeholder="e.g., Connor" 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Phone Number</label>
                    <input type="tel" name="phone" placeholder="+1 (555) 000-0000" 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></div>
                <h3 class="font-bold text-gray-900">2. System Credentials</h3>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Corporate Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" required placeholder="sarah.c@enterprise.com" 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-green-500 outline-none transition">
                    <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase">Used as Login ID</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Temporary Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required value="Welcome123!" placeholder="••••••••" 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-green-500 outline-none transition">
                    <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase">Forced reset on first login</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></div>
                <h3 class="font-bold text-gray-900">3. Employment & Hierarchy Mapping</h3>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">System Role (RBAC) <span class="text-red-500">*</span></label>
                    <select name="role_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 outline-none transition">
                        <?php if (!empty($roles)): ?>
                            <?php foreach($roles as $role): ?>
                                <option value="<?= $role['id'] ?>"><?= sanitize($role['name']) ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="2" selected>Standard Employee</option>
                            <option value="4">Project Manager / Lead</option>
                            <option value="3">HR Manager</option>
                            <option value="5">IT / Asset Manager</option>
                            <option value="6">Finance / Payroll</option>
                            <option value="1">Global Admin (Owner)</option>
                            <option value="7">Business Development</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Department ID</label>
                    <input type="number" name="department_id" placeholder="e.g., 1 = Engineering" value="1"
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Designation ID <span class="text-red-500">*</span></label>
                    <input type="number" name="designation_id" required placeholder="e.g., 1 = Developer" value="1"
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 outline-none transition">
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Direct Reporting Manager</label>
                    <select name="manager_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 outline-none transition">
                        <option value="">-- No Direct Manager (Top Level) --</option>
                        <?php if(!empty($managers)): ?>
                            <?php foreach($managers as $m): ?>
                                <option value="<?= $m['id'] ?>">
                                    <?= sanitize($m['first_name'] . ' ' . $m['last_name']) ?> (<?= sanitize($m['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Joining Date</label>
                    <input type="date" name="join_date" value="<?= date('Y-m-d') ?>"
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Base Salary (Annual)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-bold">$</span>
                        <input type="number" step="0.01" name="salary" placeholder="0.00" 
                               class="w-full pl-8 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 outline-none transition">
                    </div>
                </div>

            </div>
        </div>

        <div class="flex justify-end gap-4 mt-8">
            <button type="reset" class="px-6 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition">
                Clear Form
            </button>
            <button type="submit" class="px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Register & Provision Access
            </button>
        </div>

    </form>
</div>
<div class="max-w-7xl mx-auto space-y-6 pb-12">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-200 pb-5">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">HR & Employees</h1>
            <p class="text-sm text-gray-500 mt-1">Manage company staff, roles, departments, and access levels.</p>
        </div>
        <div class="flex gap-3">
            <div class="relative">
                <input type="text" placeholder="Search employees..." class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm w-64 outline-none">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
            <?php if (Auth::can('create', 'hr')): ?>
            <a href="<?= base_url('/employee/create') ?>" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                Add Employee
            </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (Session::get('flash_success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
            <p class="text-sm text-green-700 font-medium"><?= Session::get('flash_success'); Session::set('flash_success', null); ?></p>
        </div>
    <?php endif; ?>

    <?php if (empty($employees)): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">No Employees Found</h3>
            <p class="text-sm text-gray-500 max-w-sm mb-6">There are currently no employees registered in the system aside from the master admin.</p>
            <?php if (Auth::can('create', 'hr')): ?>
                <a href="<?= base_url('/employee/create') ?>" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition">Register First Employee</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Employee</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Contact Info</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Role & Dept</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-black text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach($employees as $emp): ?>
                        <tr class="hover:bg-gray-50 transition-colors group">
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold border-2 border-white shadow-sm">
                                            <?= strtoupper(substr($emp['first_name'] ?? 'U', 0, 1) . substr($emp['last_name'] ?? 'N', 0, 1)) ?>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                            <?= sanitize(($emp['first_name'] ?? '') . ' ' . ($emp['last_name'] ?? '')) ?>
                                        </div>
                                        <div class="text-xs font-mono text-gray-500 mt-0.5">ID: EMP-<?= str_pad($emp['id'] ?? 0, 4, '0', STR_PAD_LEFT) ?></div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium"><?= sanitize($emp['email'] ?? 'No email') ?></div>
                                <div class="text-xs text-gray-500 mt-0.5"><?= sanitize($emp['phone'] ?? 'No phone provided') ?></div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-700">
                                    <?= sanitize(ucwords(str_replace('_', ' ', $emp['role_slug'] ?? 'Employee'))) ?>
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    <?= sanitize($emp['department_name'] ?? 'General') ?>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php 
                                    $status = $emp['status'] ?? 'active';
                                    $statusColor = $status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                ?>
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full tracking-wider uppercase <?= $statusColor ?>">
                                    <?= sanitize($status) ?>
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="<?= base_url('/employee/show/' . $emp['id']) ?>" class="text-blue-600 hover:text-blue-900 mr-4 font-bold">Profile</a>
                                <?php if(Auth::can('edit', 'hr')): ?>
                                    <a href="<?= base_url('/employee/edit/' . $emp['id']) ?>" class="text-gray-500 hover:text-gray-900 font-bold">Edit</a>
                                <?php endif; ?>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>
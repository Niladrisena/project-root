<div class="max-w-7xl mx-auto space-y-6">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-200 pb-5">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Projects Overview</h1>
            <p class="text-sm text-gray-500 mt-1">Manage active corporate projects, timelines, and team assignments.</p>
        </div>
        <div class="flex gap-3">
            <div class="relative">
                <input type="text" placeholder="Search projects..." class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm w-64">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
            <?php if (Auth::can('create', 'projects')): ?>
            <a href="<?= base_url('/project/create') ?>" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Project
            </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (empty($projects)): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">No Projects Found</h3>
            <p class="text-sm text-gray-500 max-w-sm mb-6">There are currently no active projects assigned to you. Create a new project to get started.</p>
            <?php if (Auth::can('create', 'projects')): ?>
                <a href="<?= base_url('/project/create') ?>" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition">Create First Project</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <?php foreach($projects as $proj): ?>
            
            <?php 
                // Determine Status Badge Colors
                $statusBg = 'bg-gray-100 text-gray-800';
                if($proj['status'] === 'active' || $proj['status'] === 'in_progress') $statusBg = 'bg-blue-100 text-blue-800';
                if($proj['status'] === 'completed') $statusBg = 'bg-green-100 text-green-800';
                if($proj['status'] === 'on_hold') $statusBg = 'bg-orange-100 text-orange-800';
                if($proj['status'] === 'cancelled') $statusBg = 'bg-red-100 text-red-800';
            ?>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow group relative flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-full <?= $statusBg ?> tracking-wider">
                            <?= str_replace('_', ' ', $proj['status']) ?>
                        </span>
                        <?php if($proj['priority'] === 'high' || $proj['priority'] === 'urgent'): ?>
                            <span class="ml-1 px-2.5 py-1 text-[10px] font-bold uppercase rounded-full bg-red-50 text-red-600 tracking-wider">
                                <?= $proj['priority'] ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <span class="text-xs font-mono font-medium text-gray-400 bg-gray-50 px-2 py-1 rounded border border-gray-100">
                        <?= sanitize($proj['project_code']) ?>
                    </span>
                </div>

                <h3 class="text-lg font-bold text-gray-900 mb-1 leading-tight group-hover:text-blue-600 transition-colors">
                    <a href="<?= base_url('/project/show/' . $proj['id']) ?>" class="focus:outline-none">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        <?= sanitize($proj['name']) ?>
                    </a>
                </h3>
                
                <p class="text-sm text-gray-500 mb-4 line-clamp-2 flex-1">
                    <?= sanitize($proj['description']) ?: 'No description provided for this project.' ?>
                </p>

                <div class="space-y-4 border-t border-gray-100 pt-4 mt-auto">
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1.5">
                            <span class="text-gray-500">Progress</span>
                            <span class="text-gray-900"><?= $proj['progress_pct'] ?? 0 ?>%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: <?= $proj['progress_pct'] ?? 0 ?>%"></div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-blue-100 border-2 border-white shadow-sm flex items-center justify-center text-xs font-bold text-blue-700" title="Project Manager">
                                <?= strtoupper(substr($proj['first_name'] ?? 'M', 0, 1) . substr($proj['last_name'] ?? 'GR', 0, 1)) ?>
                            </div>
                            <div class="text-xs">
                                <p class="font-bold text-gray-900 leading-none"><?= sanitize($proj['first_name'] . ' ' . $proj['last_name']) ?: 'Unassigned' ?></p>
                                <p class="text-gray-500 mt-0.5"><?= date('M d, Y', strtotime($proj['deadline'])) ?></p>
                            </div>
                        </div>
                        
                        <div class="text-gray-400 group-hover:text-blue-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
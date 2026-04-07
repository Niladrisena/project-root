<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="mb-8 flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">My Active Task Force</h1>
                <p class="text-sm text-gray-500 mt-0.5">Manage personnel currently deployed across your project portfolio.</p>
            </div>
        </div>
        <a href="<?= base_url('/pm/dashboard') ?>" class="px-5 py-2.5 bg-gray-50 border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-100 transition">
            &larr; Back to Command Center
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if(empty($team)): ?>
            <div class="col-span-full bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mb-4 border border-gray-100">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">No Active Deployments</h3>
                <p class="text-sm text-gray-500 max-w-sm mb-6">You currently have no personnel assigned to your active projects.</p>
                <a href="<?= base_url('/pm/create_project') ?>" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition">Deploy New Project</a>
            </div>
        <?php else: ?>
            <?php foreach($team as $member): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300 flex flex-col">
                    <div class="p-6 border-b border-gray-100 flex gap-4 items-center bg-gradient-to-r from-gray-50 to-white">
                        <div class="w-14 h-14 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center text-xl font-black shadow-inner border border-blue-200">
                            <?= strtoupper(substr($member['first_name'], 0, 1) . substr($member['last_name'], 0, 1)) ?>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-900 leading-tight"><?= sanitize($member['first_name'] . ' ' . $member['last_name']) ?></h3>
                            <a href="mailto:<?= sanitize($member['email']) ?>" class="text-xs font-bold text-blue-600 hover:underline"><?= sanitize($member['email']) ?></a>
                        </div>
                    </div>
                    
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Deployed Across</p>
                                <p class="text-sm font-bold text-gray-800 bg-gray-50 px-3 py-2 rounded-lg border border-gray-100">
                                    <?= $member['project_count'] ?> Project(s)
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Project Names</p>
                                <p class="text-xs text-gray-600 truncate" title="<?= sanitize($member['assigned_projects']) ?>">
                                    <?= sanitize($member['assigned_projects']) ?>
                                </p>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-2 text-orange-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="text-sm font-black"><?= number_format($member['total_allocated_hours'], 1) ?> Hrs</span>
                            </div>
                            <button class="text-xs font-bold text-gray-500 hover:text-gray-900 transition">View Stats &rarr;</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
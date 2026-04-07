<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 bg-gradient-to-r from-blue-900 to-cyan-800 rounded-2xl p-8 shadow-lg text-white">
        <div>
            <p class="text-blue-200 text-sm font-bold uppercase tracking-wider mb-1"><?= date('l, F j, Y') ?></p>
            <h1 class="text-3xl font-black tracking-tight">Project Command Center 🚀</h1>
            <p class="text-blue-100 mt-2 text-sm">Predictive Analytics, Resource Efficiency, and Portfolio Health.</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= base_url('/pm/create_project') ?>" class="px-6 py-3 bg-white text-blue-900 hover:bg-gray-50 font-black rounded-xl shadow-md transition flex items-center gap-2 hover:scale-105 transform duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add New Memeber 
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
        <a href="<?= base_url('/project') ?>" class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4 border-b-4 border-blue-500 hover:shadow-md hover:-translate-y-0.5 transition">
            <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg></div>
            <div><p class="text-xs font-bold text-gray-500 uppercase">Active Projects</p><p class="text-2xl font-black text-gray-900"><?= $stats['active_projects'] ?></p></div>
        </a>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4 border-b-4 <?= $stats['team_size'] > 0 ? 'border-indigo-500' : 'border-gray-200' ?>">
            <div class="w-12 h-12 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></div>
            <div><p class="text-xs font-bold text-gray-500 uppercase">My Team Size</p><p class="text-2xl font-black text-gray-900"><?= $stats['team_size'] ?></p></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4 border-b-4 <?= $stats['hold_projects'] > 0 ? 'border-yellow-500' : 'border-gray-200' ?>">
            <div class="w-12 h-12 rounded-lg <?= $stats['hold_projects'] > 0 ? 'bg-yellow-50 text-yellow-600' : 'bg-gray-50 text-gray-500' ?> flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6M9 19h6a2 2 0 002-2V7a2 2 0 00-2-2H9a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></div>
            <div><p class="text-xs font-bold text-gray-500 uppercase">Hold Projects</p><p class="text-2xl font-black text-gray-900"><?= $stats['hold_projects'] ?></p></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4 border-b-4 border-green-500">
            <div class="w-12 h-12 rounded-lg bg-green-50 text-green-600 flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10M7 17h6"></path></svg></div>
            <div><p class="text-xs font-bold text-gray-500 uppercase">Number of Projects</p><p class="text-2xl font-black text-gray-900"><?= $stats['total_projects'] ?></p></div>
        </div>
    </div>

    <?php if (Session::get('flash_success')): ?>
        <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
            <p class="text-sm text-green-700 font-bold"><?= Session::get('flash_success'); Session::set('flash_success', null); ?></p>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1 space-y-8">
            
            <div class="bg-gradient-to-br from-indigo-50 to-white rounded-2xl shadow-sm border border-indigo-100 p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-5">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                </div>
                <h3 class="font-black text-indigo-900 mb-5 flex items-center gap-2 tracking-tight">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    System Intelligence
                </h3>
                <ul class="space-y-4 relative z-10">
                    <?php foreach($suggestions as $sug): ?>
                        <?php 
                            $iconColor = $sug['type'] == 'danger' ? 'text-red-500' : ($sug['type'] == 'warning' ? 'text-yellow-500' : ($sug['type'] == 'success' ? 'text-green-500' : 'text-blue-500'));
                        ?>
                        <li class="flex items-start gap-3 text-sm text-gray-700 bg-white/60 p-3 rounded-xl border border-white">
                            <span class="<?= $iconColor ?> font-black mt-0.5">&bull;</span> 
                            <span class="leading-relaxed"><?= $sug['message'] ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900">Hub Navigation</h3>
                </div>
                <div class="p-3 grid grid-cols-2 gap-2">
                    <a href="<?= base_url('/project') ?>" class="p-4 rounded-xl hover:bg-indigo-50 transition flex flex-col items-center justify-center text-center gap-3 border border-transparent hover:border-indigo-100">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg></div>
                        <span class="text-xs font-black text-gray-700 uppercase tracking-wider">Projects</span>
                    </a>
                    <a href="<?= base_url('/pm/team') ?>" class="p-4 rounded-xl hover:bg-blue-50 transition flex flex-col items-center justify-center text-center gap-3 border border-transparent hover:border-blue-100">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></div>
                        <span class="text-xs font-black text-gray-700 uppercase tracking-wider">My Team</span>
                    </a>
                    <a href="<?= base_url('/performance') ?>" class="p-4 rounded-xl hover:bg-purple-50 transition flex flex-col items-center justify-center text-center gap-3 border border-transparent hover:border-purple-100">
                        <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg></div>
                        <span class="text-xs font-black text-gray-700 uppercase tracking-wider">Appraisals</span>
                    </a>
                    <a href="<?= base_url('/pm/it_desk') ?>" class="p-4 rounded-xl hover:bg-slate-50 transition flex flex-col items-center justify-center text-center gap-3 border border-transparent hover:border-slate-200">
                        <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></div>
                        <span class="text-xs font-black text-gray-700 uppercase tracking-wider">IT Desk</span>
                    </a>
                    
                    <a href="<?= base_url('/pm/settings') ?>" class="p-4 rounded-xl hover:bg-gray-100 transition flex flex-col items-center justify-center text-center gap-3 border border-transparent hover:border-gray-200">
                        <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-800 flex items-center justify-center shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></div>
                        <span class="text-xs font-black text-gray-700 uppercase tracking-wider">Security</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-900">Efficiency & Health Matrix</h3>
                    <span class="text-xs font-bold text-gray-400 uppercase">Active Portfolio</span>
                </div>
                
                <div class="divide-y divide-gray-100">
                    <?php if(empty($health_metrics)): ?>
                        <div class="p-16 text-center text-gray-500">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100 shadow-inner">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                            <span class="font-black text-gray-900 block text-lg tracking-tight">Awaiting Data</span>
                            <span class="text-sm mt-1 block">Deploy a project workspace to begin tracking analytics.</span>
                        </div>
                    <?php else: ?>
                        <?php foreach($health_metrics as $p): ?>
                            <?php 
                                // Dynamic UI Colors based on Algorithm
                                $bg_color = "bg-{$p['health_color']}-50";
                                $text_color = "text-{$p['health_color']}-700";
                                $border_color = "border-{$p['health_color']}-200";
                                $bar_color = "bg-{$p['health_color']}-500";
                            ?>
                            <a href="<?= base_url('/project/show/' . $p['id']) ?>" class="p-6 hover:bg-gray-50 transition block">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="text-lg font-black text-gray-900"><?= sanitize($p['name']) ?></h4>
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mt-1">Deadline: <?= date('M d, Y', strtotime($p['deadline'])) ?></p>
                                    </div>
                                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-wider rounded-full border <?= $bg_color ?> <?= $text_color ?> <?= $border_color ?> shadow-sm">
                                        <?= $p['health'] ?>
                                    </span>
                                </div>
                                
                                <div class="space-y-4">
                                    <div>
                                        <div class="flex justify-between text-xs font-bold mb-1">
                                            <span class="text-gray-700">Actual Progress</span>
                                            <span class="<?= $p['progress_pct'] >= $p['time_elapsed_pct'] ? 'text-green-600' : 'text-red-500' ?>"><?= $p['progress_pct'] ?>%</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden border border-gray-200 shadow-inner">
                                            <div class="<?= $bar_color ?> h-2.5 rounded-full" style="width: <?= $p['progress_pct'] ?>%"></div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                            <span>Time Elapsed (Expected Progress)</span>
                                            <span><?= $p['time_elapsed_pct'] ?>%</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-gray-400 h-1.5 rounded-full" style="width: <?= $p['time_elapsed_pct'] ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

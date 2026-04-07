<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    
    <?php if (Session::get('flash_success')): ?>
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <p class="text-sm text-green-700 font-medium"><?= Session::get('flash_success'); Session::set('flash_success', null); ?></p>
        </div>
    <?php endif; ?>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 bg-gradient-to-r from-blue-900 to-indigo-800 rounded-2xl p-8 shadow-lg text-white">
        <div>
            <p class="text-blue-200 text-sm font-bold uppercase tracking-wider mb-1"><?= date('l, F j, Y') ?></p>
            <h1 class="text-3xl font-black tracking-tight">Welcome back, <?= sanitize(Session::get('user_name') ?? 'Team Member') ?> 👋</h1>
            <p class="text-blue-100 mt-2 text-sm">Here is your daily operational overview and pending action items.</p>
        </div>
        
        <div class="flex gap-3">
            <?php if(empty($attendance['clock_in'])): ?>
                <form action="<?= base_url('/attendance/clock_in') ?>" method="POST" class="m-0">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <button type="submit" class="px-6 py-3 bg-green-500 hover:bg-green-400 text-white font-black rounded-xl shadow-md transition flex items-center gap-2 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Clock In Now
                    </button>
                </form>
            <?php elseif(empty($attendance['clock_out'])): ?>
                <form action="<?= base_url('/attendance/clock_out') ?>" method="POST" class="m-0">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <button type="submit" class="px-6 py-3 bg-red-500 hover:bg-red-400 text-white font-black rounded-xl shadow-md transition flex items-center gap-2 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Clock Out
                    </button>
                </form>
            <?php else: ?>
                <div class="px-6 py-3 bg-white/10 text-white font-black rounded-xl border border-white/20 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Shift Completed
                </div>
            <?php endif; ?>
        </div>
    </div> <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg></div>
            <div><p class="text-xs font-bold text-gray-500 uppercase">Active Tasks</p><p class="text-2xl font-black text-gray-900"><?= count($tasks ?? []) ?></p></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-green-50 text-green-600 flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div><p class="text-xs font-bold text-gray-500 uppercase">Weekly Hours</p><p class="text-2xl font-black text-gray-900"><?= number_format($weekly_hours ?? 0, 1) ?>h</p></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
            <div><p class="text-xs font-bold text-gray-500 uppercase">Leave Balance</p><p class="text-2xl font-black text-gray-900"><?= $leave_balance ?? 0 ?></p></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></div>
            <div><p class="text-xs font-bold text-gray-500 uppercase">My Assets</p><p class="text-2xl font-black text-gray-900"><?= count($assets ?? []) ?></p></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900">Priority Task Center</h3>
                    <a href="<?= base_url('/task/board') ?>" class="text-sm font-bold text-blue-600 hover:text-blue-800">View Board &rarr;</a>
                </div>
                <div class="divide-y divide-gray-100">
                    <?php if(empty($tasks)): ?>
                        <div class="p-8 text-center text-gray-500 text-sm">No active tasks assigned to you.</div>
                    <?php else: ?>
                        <?php foreach($tasks as $t): ?>
                        <div class="p-4 hover:bg-gray-50 transition flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900"><?= sanitize($t['title']) ?></h4>
                                    <p class="text-xs font-medium text-gray-500 mt-0.5"><?= sanitize($t['project_name'] ?? 'General') ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <?php $color = ($t['priority'] == 'high') ? 'red' : (($t['priority'] == 'medium') ? 'yellow' : 'blue'); ?>
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-<?= $color ?>-100 text-<?= $color ?>-800 uppercase">
                                    <?= sanitize($t['priority'] ?? 'normal') ?>
                                </span>
                                <p class="text-xs font-bold text-gray-400 mt-1"><?= date('M d', strtotime($t['deadline'])) ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">My Projects Status</h3>
                </div>
                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php if(empty($projects)): ?>
                        <div class="col-span-2 p-4 text-center text-gray-500 text-sm border border-dashed rounded-lg">Not assigned to any active projects.</div>
                    <?php else: ?>
                        <?php foreach($projects as $p): ?>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="text-sm font-bold text-gray-900 mb-2"><?= sanitize($p['name']) ?></h4>
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: <?= $p['progress'] ?? 0 ?>%"></div>
                            </div>
                            <div class="flex justify-between text-xs font-bold text-gray-500">
                                <span>Progress</span>
                                <span><?= number_format($p['progress'] ?? 0, 0) ?>%</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-8">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-900">Workspace Apps</h3>
                </div>
                <div class="p-3 grid grid-cols-2 gap-2">
                    
                    <a href="<?= base_url('/task/board') ?>" class="p-4 rounded-xl hover:bg-blue-50 transition flex flex-col items-center justify-center text-center gap-3 group border border-transparent hover:border-blue-100">
                        <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <span class="text-xs font-black text-gray-700 uppercase tracking-wider">Tasks</span>
                    </a>
                    
                    <a href="<?= base_url('/timesheet') ?>" class="p-4 rounded-xl hover:bg-green-50 transition flex flex-col items-center justify-center text-center gap-3 group border border-transparent hover:border-green-100">
                        <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-xs font-black text-gray-700 uppercase tracking-wider">Timesheet</span>
                    </a>

                    <a href="<?= base_url('/leave') ?>" class="p-4 rounded-xl hover:bg-purple-50 transition flex flex-col items-center justify-center text-center gap-3 group border border-transparent hover:border-purple-100">
                        <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-xs font-black text-gray-700 uppercase tracking-wider">Leave</span>
                    </a>

                    <a href="<?= base_url('/asset/my') ?>" class="p-4 rounded-xl hover:bg-orange-50 transition flex flex-col items-center justify-center text-center gap-3 group border border-transparent hover:border-orange-100">
                        <div class="w-12 h-12 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-xs font-black text-gray-700 uppercase tracking-wider">Assets</span>
                    </a>

                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">Upcoming Deadlines</h3>
                </div>
                <div class="p-4 space-y-4">
                    <?php if(empty($deadlines)): ?>
                        <div class="text-center text-gray-500 text-sm">Clear schedule for the next 7 days!</div>
                    <?php else: ?>
                        <?php foreach($deadlines as $d): ?>
                        <div class="flex gap-4 items-start">
                            <div class="w-12 h-12 rounded bg-red-50 flex flex-col items-center justify-center shrink-0 border border-red-100">
                                <span class="text-xs font-bold text-red-500 uppercase leading-none"><?= date('M', strtotime($d['deadline'])) ?></span>
                                <span class="text-lg font-black text-red-700 leading-none mt-1"><?= date('d', strtotime($d['deadline'])) ?></span>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 leading-tight"><?= sanitize($d['title']) ?></h4>
                                <p class="text-xs font-medium text-gray-500 mt-1"><?= sanitize($d['type']) ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-900">Recent Updates</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    <?php if(empty($notifications)): ?>
                        <div class="p-6 text-center text-gray-500 text-sm">No new notifications.</div>
                    <?php else: ?>
                        <?php foreach($notifications as $n): ?>
                        <div class="p-4 flex gap-3 <?= $n['is_read'] ? 'bg-white' : 'bg-blue-50/50' ?>">
                            <div class="w-2 h-2 mt-1.5 rounded-full shrink-0 <?= $n['is_read'] ? 'bg-gray-300' : 'bg-blue-500' ?>"></div>
                            <div>
                                <p class="text-sm text-gray-800 font-medium leading-snug"><?= sanitize($n['message']) ?></p>
                                <p class="text-xs font-bold text-gray-400 mt-1"><?= date('M d, h:i A', strtotime($n['created_at'])) ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
</div>
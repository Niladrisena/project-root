<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 bg-gradient-to-r from-purple-900 to-indigo-900 rounded-2xl p-8 shadow-lg text-white">
        <div>
            <p class="text-purple-200 text-sm font-bold uppercase tracking-wider mb-1"><?= date('l, F j, Y') ?></p>
            <h1 class="text-3xl font-black tracking-tight">HR Command Center 🎯</h1>
            <p class="text-purple-100 mt-2 text-sm">Oversee workforce performance, manage approvals, and optimize team health.</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= base_url('/employee/create') ?>" class="px-6 py-3 bg-white text-indigo-900 hover:bg-gray-50 font-black rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                Onboard Employee
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></div>
            <div><p class="text-xs font-bold text-gray-500 uppercase">Headcount</p><p class="text-2xl font-black text-gray-900"><?= $attendance['total'] ?? 0 ?></p></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4 border-b-4 <?= ($attendance['rate'] ?? 100) >= 90 ? 'border-green-500' : 'border-yellow-500' ?>">
            <div class="w-12 h-12 rounded-lg <?= ($attendance['rate'] ?? 100) >= 90 ? 'bg-green-50 text-green-600' : 'bg-yellow-50 text-yellow-600' ?> flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div><p class="text-xs font-bold text-gray-500 uppercase">Attendance Rate</p><p class="text-2xl font-black text-gray-900"><?= $attendance['rate'] ?? 0 ?>%</p></div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div><p class="text-xs font-bold text-gray-500 uppercase">Absent Today</p><p class="text-2xl font-black text-gray-900"><?= $attendance['absent'] ?? 0 ?></p></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4 border-b-4 <?= ($pending_leaves ?? 0) > 0 ? 'border-red-500' : 'border-gray-200' ?>">
            <div class="w-12 h-12 rounded-lg bg-red-50 text-red-600 flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg></div>
            <div><p class="text-xs font-bold text-gray-500 uppercase">Pending Leaves</p><p class="text-2xl font-black text-gray-900"><?= $pending_leaves ?? 0 ?></p></div>
        </div>
    </div>
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="<?= base_url('/hr/attendance') ?>" class="bg-gradient-to-r from-rose-800 to-rose-900 rounded-2xl p-6 shadow-sm hover:shadow-lg transition transform hover:-translate-y-1 group flex flex-col items-center justify-center text-center">
            <div class="w-14 h-14 bg-white/10 rounded-full flex items-center justify-center text-rose-300 group-hover:scale-110 transition mb-3"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <h3 class="text-lg font-black text-white tracking-tight">Attendance</h3>
            <p class="text-rose-200 text-xs mt-1">Clock in & timesheets.</p>
        </a>

        <a href="<?= base_url('/hr/leaves') ?>" class="bg-gradient-to-r from-teal-800 to-teal-900 rounded-2xl p-6 shadow-sm hover:shadow-lg transition transform hover:-translate-y-1 group flex flex-col items-center justify-center text-center">
            <div class="w-14 h-14 bg-white/10 rounded-full flex items-center justify-center text-teal-300 group-hover:scale-110 transition mb-3"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
            <h3 class="text-lg font-black text-white tracking-tight">Leaves</h3>
            <p class="text-teal-200 text-xs mt-1">Approve corporate PTO.</p>
        </a>

        <a href="<?= base_url('/hr/security') ?>" class="bg-gradient-to-r from-indigo-800 to-indigo-900 rounded-2xl p-6 shadow-sm hover:shadow-lg transition transform hover:-translate-y-1 group flex flex-col items-center justify-center text-center border border-indigo-700">
            <div class="w-14 h-14 bg-white/10 rounded-full flex items-center justify-center text-indigo-300 group-hover:scale-110 transition mb-3"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></div>
            <h3 class="text-lg font-black text-white tracking-tight">My Security</h3>
            <p class="text-indigo-200 text-xs mt-1">Change Account Password.</p>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1 space-y-8">
            
            <div class="bg-gradient-to-br from-indigo-50 to-white rounded-xl shadow-sm border border-indigo-100 p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A120.153 120.153 0 0121 8c0 3.849-1.45 7.36-3.816 10.134-1.522 1.791-3.236 3.013-4.99 3.613-1.636.56-3.25.56-4.888 0-1.754-.6-3.468-1.822-4.99-3.613C1.45 15.36 0 11.85 0 8a120.153 120.153 0 019.7-6.954C10.224.784 10.776.784 11.3 1.046z" clip-rule="evenodd"></path></svg>
                </div>
                <h3 class="font-bold text-indigo-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    System Suggestions
                </h3>
                <ul class="space-y-3 relative z-10">
                    <?php if(($pending_leaves ?? 0) > 0): ?>
                        <li class="flex items-start gap-2 text-sm text-indigo-800"><span class="text-red-500 font-black">•</span> You have <?= $pending_leaves ?> leave requests awaiting approval. <a href="<?= base_url('/leave/manage') ?>" class="underline font-bold">Review now</a>.</li>
                    <?php endif; ?>
                    <?php if(($attendance['rate'] ?? 100) < 95): ?>
                        <li class="flex items-start gap-2 text-sm text-indigo-800"><span class="text-yellow-500 font-black">•</span> Attendance rate is dropping (<?= $attendance['rate'] ?>%). Consider reviewing the absence report.</li>
                    <?php endif; ?>
                    <?php if(empty($warnings) && ($pending_leaves ?? 0) == 0): ?>
                        <li class="flex items-start gap-2 text-sm text-indigo-800"><span class="text-green-500 font-black">•</span> Operations are running smoothly. No urgent actions required.</li>
                    <?php endif; ?>
                </ul>
            </div>
            

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-900">Management Hub</h3>
                </div>
                <div class="p-3 grid grid-cols-2 gap-2">
                    <a href="<?= base_url('/employee') ?>" class="p-4 rounded-xl hover:bg-blue-50 transition flex flex-col items-center justify-center text-center gap-3 border border-transparent hover:border-blue-100">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></div>
                        <span class="text-xs font-black text-gray-700 uppercase tracking-wider">Directory</span>
                    </a>
                    <a href="<?= base_url('/leave/manage') ?>" class="p-4 rounded-xl hover:bg-red-50 transition flex flex-col items-center justify-center text-center gap-3 border border-transparent hover:border-red-100">
                        <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                        <span class="text-xs font-black text-gray-700 uppercase tracking-wider">Leaves</span>
                    </a>
                    <a href="<?= base_url('/payroll') ?>" class="p-4 rounded-xl hover:bg-green-50 transition flex flex-col items-center justify-center text-center gap-3 border border-transparent hover:border-green-100">
                        <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                        <span class="text-xs font-black text-gray-700 uppercase tracking-wider">Payroll</span>
                    </a>
                    <a href="<?= base_url('/performance') ?>" class="p-4 rounded-xl hover:bg-purple-50 transition flex flex-col items-center justify-center text-center gap-3 border border-transparent hover:border-purple-100">
                        <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg></div>
                        <span class="text-xs font-black text-gray-700 uppercase tracking-wider">Reviews</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-8">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900">Attendance Watchlist (30 Days)</h3>
                    <span class="text-xs font-bold text-gray-400 uppercase">Under 15 Days Present</span>
                </div>
                <div class="divide-y divide-gray-100">
                    <?php if(empty($warnings)): ?>
                        <div class="p-8 text-center text-gray-500 text-sm">All employees are meeting attendance expectations.</div>
                    <?php else: ?>
                        <?php foreach($warnings as $w): ?>
                        <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-600 font-bold flex items-center justify-center">
                                    <?= substr($w['first_name'], 0, 1) . substr($w['last_name'], 0, 1) ?>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900"><?= sanitize($w['first_name'] . ' ' . $w['last_name']) ?></h4>
                                    <p class="text-xs text-red-500 font-medium">Only <?= $w['days_present'] ?> days present</p>
                                </div>
                            </div>
                            
                            <a href="<?= base_url('/employee/show/' . ($w['user_id'] ?? $w['id'])) ?>" class="text-sm font-bold text-blue-600 hover:text-blue-800 border border-blue-200 px-3 py-1.5 rounded-lg hover:bg-blue-50 transition">
                                View Profile
                            </a>

                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">Recent Onboardings</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                <th class="px-6 py-3">Employee</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3">Join Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php if(empty($recent_hires)): ?>
                                <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm">No recent hires.</td></tr>
                            <?php else: ?>
                                <?php foreach($recent_hires as $h): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-3 text-sm font-bold text-gray-900">
                                            <a href="<?= base_url('/employee/show/' . $h['id']) ?>" class="hover:text-blue-600 transition">
                                                <?= sanitize($h['first_name'] . ' ' . $h['last_name']) ?>
                                            </a>
                                        </td>
                                        <td class="px-6 py-3 text-sm text-gray-500"><?= sanitize($h['email']) ?></td>
                                        <td class="px-6 py-3 text-sm text-gray-500"><?= date('M d, Y', strtotime($h['join_date'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    
</div>
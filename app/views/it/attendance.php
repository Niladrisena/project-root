<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-200 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-900 text-white rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Attendance Hub</h1>
                <p class="text-sm text-gray-500 mt-0.5">Manage your daily timesheet and view active team presence.</p>
            </div>
        </div>
        <a href="<?= base_url('/it/dashboard') ?>" class="px-5 py-2.5 bg-gray-50 border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-100 transition">Dashboard</a>
    </div>

    <?php if (Session::get('flash_success')): ?><div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm"><p class="text-sm text-green-700 font-bold"><?= Session::get('flash_success'); Session::set('flash_success', null); ?></p></div><?php endif; ?>
    <?php if (Session::get('flash_error')): ?><div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm"><p class="text-sm text-red-700 font-bold"><?= Session::get('flash_error'); Session::set('flash_error', null); ?></p></div><?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-emerald-800 to-emerald-900 rounded-2xl shadow-lg p-8 text-center text-white relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4IiBoZWlnaHQ9IjgiPjxyZWN0IHdpZHRoPSI4IiBoZWlnaHQ9IjgiIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iLjEiLz48L3N2Zz4=')]"></div>
                <h3 class="font-black text-emerald-200 uppercase tracking-widest text-xs mb-4 relative z-10">Current Time</h3>
                <h1 class="text-5xl font-black tracking-tight mb-2 relative z-10" id="live-clock">00:00:00</h1>
                <p class="text-emerald-300 text-sm font-medium mb-8 relative z-10"><?= date('l, F j, Y') ?></p>

                <form action="<?= base_url('/it/clock_event') ?>" method="POST" class="relative z-10">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <?php if(!$my_attendance): ?>
                        <button type="submit" class="w-full py-4 bg-white text-emerald-900 font-black rounded-xl shadow-xl hover:scale-105 transform transition duration-200">CLOCK IN NOW</button>
                    <?php elseif(!$my_attendance['clock_out']): ?>
                        <div class="mb-4 text-emerald-200 text-sm font-bold bg-emerald-800 py-2 rounded-lg border border-emerald-700">Clocked In: <?= date('h:i A', strtotime($my_attendance['clock_in'])) ?></div>
                        <button type="submit" class="w-full py-4 bg-red-500 hover:bg-red-600 text-white font-black rounded-xl shadow-xl hover:scale-105 transform transition duration-200">CLOCK OUT</button>
                    <?php else: ?>
                        <div class="p-4 bg-emerald-800 rounded-xl border border-emerald-700">
                            <span class="block text-emerald-200 font-bold mb-1">Shift Completed</span>
                            <span class="text-xs text-emerald-300">Out at <?= date('h:i A', strtotime($my_attendance['clock_out'])) ?></span>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden h-full">
                <div class="p-5 border-b border-gray-100 bg-gray-50"><h3 class="font-bold text-gray-900">Today's Global Roster</h3></div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                <th class="px-6 py-4">Employee</th>
                                <th class="px-6 py-4">Clock In</th>
                                <th class="px-6 py-4">Clock Out</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php if(empty($team_attendance)): ?>
                                <tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">No one has clocked in yet today.</td></tr>
                            <?php else: ?>
                                <?php foreach($team_attendance as $t): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-900"><?= sanitize($t['first_name'] . ' ' . $t['last_name']) ?></p>
                                            <p class="text-[10px] text-gray-400 font-medium"><?= sanitize($t['email']) ?></p>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-black text-gray-700"><?= date('h:i A', strtotime($t['clock_in'])) ?></td>
                                        <td class="px-6 py-4 text-sm font-black <?= $t['clock_out'] ? 'text-gray-700' : 'text-orange-400' ?>">
                                            <?= $t['clock_out'] ? date('h:i A', strtotime($t['clock_out'])) : 'Active Shift' ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase rounded">Present</span>
                                        </td>
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

<script>
    // Live JS Clock Engine
    setInterval(() => {
        let d = new Date();
        document.getElementById("live-clock").innerText = 
            d.toLocaleTimeString('en-US', {hour12: false});
    }, 1000);
</script>
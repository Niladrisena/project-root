<div class="max-w-3xl mx-auto pb-12 px-4 md:px-0">
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-200 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-900 text-white rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">My Timesheet</h1>
                <p class="text-sm text-gray-500 mt-0.5">Manage your personal daily attendance record.</p>
            </div>
        </div>
        <a href="<?= base_url('/finance/dashboard') ?>" class="px-5 py-2.5 bg-gray-50 border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-100 transition">Back to Hub</a>
    </div>

    <?php if (Session::get('flash_success')): ?><div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm"><p class="text-sm text-green-700 font-bold"><?= Session::get('flash_success'); Session::set('flash_success', null); ?></p></div><?php endif; ?>
    <?php if (Session::get('flash_error')): ?><div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm"><p class="text-sm text-red-700 font-bold"><?= Session::get('flash_error'); Session::set('flash_error', null); ?></p></div><?php endif; ?>

    <div class="bg-gradient-to-br from-emerald-800 to-emerald-900 rounded-2xl shadow-lg p-8 text-center text-white relative overflow-hidden">
        <h3 class="font-black text-emerald-200 uppercase tracking-widest text-xs mb-4">Current Time</h3>
        <h1 class="text-5xl font-black tracking-tight mb-2" id="live-clock">00:00:00</h1>
        <p class="text-emerald-300 text-sm font-medium mb-8"><?= date('l, F j, Y') ?></p>

        <form action="<?= base_url('/finance/clock_event') ?>" method="POST">
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

<script>
    setInterval(() => {
        let d = new Date();
        document.getElementById("live-clock").innerText = d.toLocaleTimeString('en-US', {hour12: false});
    }, 1000);
</script>
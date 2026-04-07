<div class="max-w-7xl mx-auto space-y-6">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center flex flex-col justify-center items-center">
            <h2 class="text-gray-500 font-medium uppercase tracking-wider text-sm mb-2"><?= date('l, F j, Y') ?></h2>
            <div class="text-4xl font-bold text-gray-900 mb-6 font-mono" id="realtime-clock"><?= date('h:i:s A') ?></div>
            
            <?php 
                // Determine today's state
                $today_record = null;
                foreach($records as $r) { if($r['date'] == date('Y-m-d')) { $today_record = $r; break; } }
            ?>
            
            <form action="<?= base_url('/attendance/my_attendance') ?>" method="POST" class="w-full">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                
                <?php if(!$today_record): ?>
                    <input type="hidden" name="action" value="clock_in">
                    <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-md transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Clock In
                    </button>
                <?php elseif($today_record && !$today_record['clock_out']): ?>
                    <input type="hidden" name="action" value="clock_out">
                    <button type="submit" class="w-full py-3 px-4 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-lg shadow-md transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Clock Out
                    </button>
                    <p class="text-xs text-green-600 mt-3 font-medium">Clocked in at <?= date('h:i A', strtotime($today_record['clock_in'])) ?></p>
                <?php else: ?>
                    <div class="w-full py-3 px-4 bg-gray-100 text-gray-500 font-bold rounded-lg border border-gray-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Shift Completed
                    </div>
                    <p class="text-xs text-gray-500 mt-3 font-medium">Total Hours: <?= $today_record['total_work_hours'] ?> hrs</p>
                <?php endif; ?>
            </form>
        </div>

        <div class="col-span-1 lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Current Month Overview</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php 
                    $present = 0; $late = 0; $ot = 0;
                    foreach($records as $r) {
                        if($r['status'] == 'present' || $r['status'] == 'late') $present++;
                        if($r['status'] == 'late') $late++;
                        $ot += $r['overtime_hours'];
                    }
                ?>
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <p class="text-xs font-bold text-blue-600 uppercase mb-1">Present Days</p>
                    <p class="text-2xl font-black text-gray-900"><?= $present ?></p>
                </div>
                <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                    <p class="text-xs font-bold text-red-600 uppercase mb-1">Late Marks</p>
                    <p class="text-2xl font-black text-gray-900"><?= $late ?></p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                    <p class="text-xs font-bold text-green-600 uppercase mb-1">Overtime Hrs</p>
                    <p class="text-2xl font-black text-gray-900"><?= $ot ?></p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                    <p class="text-xs font-bold text-purple-600 uppercase mb-1">Leave Bal.</p>
                    <p class="text-2xl font-black text-gray-900">12</p> </div>
            </div>
            
            <div class="mt-6 h-32 w-full">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-gray-800">Attendance History (<?= date('F Y') ?>)</h3>
            <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800">Download PDF</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Clock In</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Clock Out</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Hrs</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php foreach($records as $r): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= date('D, M d', strtotime($r['date'])) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $r['clock_in'] ? date('h:i A', strtotime($r['clock_in'])) : '--' ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $r['clock_out'] ? date('h:i A', strtotime($r['clock_out'])) : '--' ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium"><?= $r['total_work_hours'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php 
                                $bg = 'bg-gray-100 text-gray-800';
                                if($r['status'] == 'present') $bg = 'bg-green-100 text-green-800';
                                if($r['status'] == 'late') $bg = 'bg-red-100 text-red-800';
                                if($r['status'] == 'half_day') $bg = 'bg-orange-100 text-orange-800';
                            ?>
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $bg ?>">
                                <?= ucfirst(str_replace('_', ' ', $r['status'])) ?>
                            </span>
                            <?php if($r['late_minutes'] > 0): ?>
                                <span class="text-xs text-red-500 ml-2">(<?= $r['late_minutes'] ?>m late)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($records)): ?>
                    <tr><td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No attendance records found for this month.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Realtime Clock
    setInterval(() => {
        const now = new Date();
        document.getElementById('realtime-clock').innerText = now.toLocaleTimeString('en-US', { hour12: true });
    }, 1000);

    // Chart.js Setup (Visualizing daily hours worked)
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [<?php foreach(array_reverse($records) as $r) echo "'" . date('d', strtotime($r['date'])) . "',"; ?>],
            datasets: [{
                label: 'Hours Worked',
                data: [<?php foreach(array_reverse($records) as $r) echo $r['total_work_hours'] . ","; ?>],
                backgroundColor: 'rgba(37, 99, 235, 0.8)',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, max: 12, grid: { borderDash: [2, 4] } }, x: { grid: { display: false } } }
        }
    });
</script>
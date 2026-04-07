<div class="max-w-7xl mx-auto space-y-8">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-200 pb-5">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Executive Intelligence</h1>
            <p class="text-sm text-gray-500 mt-1">Real-time enterprise overview and risk analytics.</p>
        </div>
        <div class="mt-4 md:mt-0 flex gap-3">
            <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg shadow-sm text-sm font-bold hover:bg-gray-50 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export PDF
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider relative z-10">YTD Revenue</p>
            <p class="text-3xl font-black text-gray-900 mt-2 relative z-10">$<?= number_format($stats['ytd_revenue'], 0) ?></p>
            <p class="text-xs font-semibold text-green-600 mt-2 flex items-center relative z-10"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg> +14.2% vs last year</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-green-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider relative z-10">Net Profit</p>
            <p class="text-3xl font-black text-gray-900 mt-2 relative z-10">$<?= number_format($stats['ytd_profit'], 0) ?></p>
            <p class="text-xs font-semibold text-green-600 mt-2 flex items-center relative z-10"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg> Healthy Margin</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-purple-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider relative z-10">Active Pipeline</p>
            <p class="text-3xl font-black text-gray-900 mt-2 relative z-10"><?= $stats['active_projects'] ?> <span class="text-lg text-gray-400 font-medium">projects</span></p>
            <?php if($stats['delayed_projects'] > 0): ?>
                <p class="text-xs font-semibold text-red-500 mt-2 flex items-center relative z-10"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> <?= $stats['delayed_projects'] ?> delayed</p>
            <?php else: ?>
                <p class="text-xs font-semibold text-green-600 mt-2 relative z-10">On track</p>
            <?php endif; ?>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-orange-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider relative z-10">Total Workforce</p>
            <p class="text-3xl font-black text-gray-900 mt-2 relative z-10"><?= $stats['total_employees'] ?></p>
            <p class="text-xs font-semibold text-gray-500 mt-2 relative z-10">Active Employees</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="col-span-1 lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Financial Growth & Burn Rate</h3>
            <div class="relative h-80 w-full">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">System Alerts</h3>
                <span class="bg-red-100 text-red-600 text-xs font-bold px-2.5 py-1 rounded-full"><?= count($alerts) ?> New</span>
            </div>
            <div class="p-4 flex-1 overflow-y-auto custom-scrollbar space-y-3">
                <?php foreach($alerts as $alert): ?>
                    <?php 
                        $bg = 'bg-blue-50 border-blue-100'; $icon_color = 'text-blue-500';
                        if($alert['alert_type'] == 'critical') { $bg = 'bg-red-50 border-red-100'; $icon_color = 'text-red-500'; }
                        if($alert['alert_type'] == 'warning') { $bg = 'bg-orange-50 border-orange-100'; $icon_color = 'text-orange-500'; }
                    ?>
                    <div class="<?= $bg ?> border rounded-xl p-4 flex gap-4 transition-all hover:shadow-md">
                        <div class="mt-0.5 <?= $icon_color ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900"><?= sanitize($alert['title']) ?></h4>
                            <p class="text-xs text-gray-600 mt-1 leading-relaxed"><?= sanitize($alert['message']) ?></p>
                            <?php if($alert['action_link']): ?>
                                <a href="<?= base_url($alert['action_link']) ?>" class="text-xs font-bold mt-2 inline-block <?= $icon_color ?> hover:underline">Take Action &rarr;</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if(empty($alerts)): ?>
                    <div class="text-center py-10">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm font-medium text-gray-500">System is healthy. No alerts.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
    // Fetch Growth Data
    const res = await fetch('<?= base_url('/analytics/chart_financial_growth') ?>');
    const data = await res.json();
    
    const ctx = document.getElementById('growthChart').getContext('2d');
    
    // Create Premium Spline Chart (Chart.js)
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.month),
            datasets: [
                {
                    label: 'Revenue',
                    data: data.map(d => d.revenue),
                    borderColor: '#10b981', // Emerald 500
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    tension: 0.4, // Smooth curves
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 2,
                    pointRadius: 4
                },
                {
                    label: 'Expenses',
                    data: data.map(d => d.expense),
                    borderColor: '#ef4444', // Red 500
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    tension: 0.4,
                    pointRadius: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8 } },
                tooltip: { backgroundColor: 'rgba(17, 24, 39, 0.9)', titleFont: { size: 13 }, bodyFont: { size: 13 }, padding: 12, cornerRadius: 8 }
            },
            scales: {
                y: { border: { display: false }, grid: { color: '#f3f4f6' }, beginAtZero: true },
                x: { border: { display: false }, grid: { display: false } }
            }
        }
    });
});
</script>
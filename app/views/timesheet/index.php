<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Time & Productivity</h1>
        <p class="text-sm text-gray-500 mt-1">Log billable hours, track project contributions, and monitor manager approvals.</p>
    </div>

    <?php if (Session::get('flash_success')): ?>
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <p class="text-sm text-green-700 font-medium"><?= Session::get('flash_success'); Session::set('flash_success', null); ?></p>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
                <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-900">Log Manual Hours</h3>
                </div>
                
                <form action="<?= base_url('/timesheet/store') ?>" method="POST" class="p-6 space-y-5">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Select Project</label>
                        <select name="project_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition shadow-sm">
                            <option value="" disabled selected>-- Choose a project --</option>
                            <?php if(!empty($projects)): ?>
                                <?php foreach($projects as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= sanitize($p['name']) ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="0">General Administrative</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Date</label>
                            <input type="date" name="date" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Hours</label>
                            <input type="number" name="hours" step="0.25" min="0.25" max="24" placeholder="e.g. 8.5" required class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Work Description</label>
                        <textarea name="description" rows="3" required placeholder="What did you achieve during this time?" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition shadow-sm resize-none"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                        Submit Timesheet
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-gray-900">Weekly Performance Trend</h3>
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase">Live Data</span>
                </div>
                <div id="timesheetChart" class="w-full h-64"></div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-900">Recent Time Logs</h3>
                    <button class="text-sm font-bold text-blue-600 hover:text-blue-800">Export CSV</button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 text-xs font-black text-gray-400 uppercase tracking-wider">
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Project / Task</th>
                                <th class="px-6 py-4 text-center">Hours</th>
                                <th class="px-6 py-4 text-right">Approval Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php if(empty($logs)): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500 text-sm">No time logs found. Start logging your hours!</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($logs as $log): ?>
                                    <tr class="hover:bg-gray-50 transition cursor-default group">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-900"><?= date('M d, Y', strtotime($log['date'])) ?></p>
                                            <p class="text-xs text-gray-400 mt-0.5"><?= date('l', strtotime($log['date'])) ?></p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-800"><?= sanitize($log['project_name'] ?? 'General') ?></p>
                                            <p class="text-xs text-gray-500 mt-0.5 truncate max-w-[200px]" title="<?= sanitize($log['description']) ?>"><?= sanitize($log['description']) ?></p>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-sm font-black text-gray-900 bg-gray-100 px-3 py-1.5 rounded-lg"><?= number_format($log['hours_worked'], 1) ?>h</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <?php 
                                                $statusColors = [
                                                    'approved' => 'bg-green-100 text-green-700 border-green-200',
                                                    'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                    'rejected' => 'bg-red-100 text-red-700 border-red-200'
                                                ];
                                                $color = $statusColors[$log['status']] ?? $statusColors['pending'];
                                            ?>
                                            <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full border <?= $color ?>">
                                                <?= sanitize($log['status']) ?>
                                            </span>
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
    document.addEventListener("DOMContentLoaded", function() {
        // Prepare data from PHP backend (safely JSON encoded)
        const rawChartData = <?= json_encode($chart_data) ?>;
        
        let categories = [];
        let seriesData = [];

        // Map data or generate empty week if no data exists
        if (rawChartData.length > 0) {
            rawChartData.forEach(item => {
                categories.push(item.date);
                seriesData.push(parseFloat(item.total));
            });
        } else {
            // Placeholder data so the chart still looks beautiful when empty
            categories = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            seriesData = [0, 0, 0, 0, 0, 0, 0];
        }

        const options = {
            series: [{
                name: 'Billable Hours',
                data: seriesData
            }],
            chart: {
                type: 'bar',
                height: 280,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '40%',
                    dataLabels: { position: 'top' }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) { return val > 0 ? val + "h" : ""; },
                offsetY: -20,
                style: { fontSize: '10px', colors: ["#6b7280"] }
            },
            colors: ['#3b82f6'], // Professional Blue
            xaxis: {
                categories: categories,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#9ca3af', fontSize: '12px', fontWeight: 600 } }
            },
            yaxis: {
                labels: { style: { colors: '#9ca3af' } }
            },
            grid: {
                borderColor: '#f3f4f6',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            },
            fill: {
                opacity: 1,
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: "vertical",
                    shadeIntensity: 0.2,
                    inverseColors: false,
                    opacityFrom: 0.9,
                    opacityTo: 0.6,
                    stops: [0, 100]
                }
            },
            tooltip: {
                y: { formatter: function (val) { return val + " Hours" } }
            }
        };

        const chart = new ApexCharts(document.querySelector("#timesheetChart"), options);
        chart.render();
    });
</script>
<div class="space-y-6">
    <?php if (Session::get('flash_success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm">
            <p class="text-sm text-green-700 font-bold"><?= Session::get('flash_success'); Session::set('flash_success', null); ?></p>
        </div>
    <?php endif; ?>

    <?php if (Session::get('flash_error')): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
            <p class="text-sm text-red-700 font-bold"><?= Session::get('flash_error'); Session::set('flash_error', null); ?></p>
        </div>
    <?php endif; ?>

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight"><?= defined('SYS_COMPANY_NAME') ? SYS_COMPANY_NAME : 'Master' ?> Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Enterprise overview and system analytics.</p>
        </div>
        <div class="text-sm text-gray-500 font-medium">
            <?= date('l, F j, Y') ?>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-900 mt-1"><?= defined('SYS_CURRENCY') ? SYS_CURRENCY : '$' ?><?= number_format($stats['total_revenue'], 2) ?></p>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-sm font-medium text-gray-500">Active Projects</p>
                <p class="text-2xl font-bold text-gray-900 mt-1"><?= $stats['active_projects'] ?></p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Employees</p>
                <p class="text-2xl font-bold text-gray-900 mt-1"><?= $stats['total_employees'] ?></p>
            </div>
            <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-sm font-medium text-gray-500">Pending Tasks</p>
                <p class="text-2xl font-bold text-gray-900 mt-1"><?= $stats['pending_tasks'] ?></p>
            </div>
            <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Revenue Overview (6 Months)</h2>
            <div class="relative h-72 w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">System Activity</h2>
            <div class="space-y-4">
                <?php foreach ($activities as $log): ?>
                <div class="flex gap-3">
                    <img src="<?= $log['avatar'] ?? 'https://ui-avatars.com/api/?name='.urlencode($log['first_name']) ?>" class="w-8 h-8 rounded-full">
                    <div>
                        <p class="text-sm text-gray-800"><span class="font-semibold"><?= sanitize($log['first_name'] . ' ' . $log['last_name']) ?></span> <?= sanitize($log['action']) ?></p>
                        <p class="text-xs text-gray-500"><?= date('M d, g:i A', strtotime($log['created_at'])) ?> &middot; <?= sanitize($log['module']) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Admin Document Inbox</h2>
                <p class="text-sm text-gray-500 mt-1">Files uploaded from BD are stored privately and only visible here for admin and owner users.</p>
            </div>
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Private</span>
        </div>

        <?php if (empty($admin_documents)): ?>
            <div class="border border-dashed border-gray-200 rounded-xl p-8 text-center">
                <p class="text-sm font-medium text-gray-500">No BD documents have been uploaded yet.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                            <th class="py-3 pr-4">Project</th>
                            <th class="py-3 pr-4">Document</th>
                            <th class="py-3 pr-4">Uploaded By</th>
                            <th class="py-3 pr-4">Date</th>
                            <th class="py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($admin_documents as $document): ?>
                            <tr>
                                <td class="py-4 pr-4">
                                    <div class="font-semibold text-gray-900"><?= sanitize($document['project_name'] ?? 'General BD Document') ?></div>
                                    <div class="text-xs text-gray-500">
                                        <?= !empty($document['proposal_id']) ? 'Proposal #' . (int) $document['proposal_id'] : 'BD Inbox' ?>
                                    </div>
                                </td>
                                <td class="py-4 pr-4">
                                    <div class="font-semibold text-gray-900"><?= sanitize($document['original_name']) ?></div>
                                    <div class="text-xs text-gray-500"><?= number_format(((int) ($document['file_size'] ?? 0)) / 1024, 1) ?> KB</div>
                                </td>
                                <td class="py-4 pr-4 text-sm text-gray-600">
                                    <?= sanitize(trim(($document['first_name'] ?? '') . ' ' . ($document['last_name'] ?? ''))) ?: 'System' ?>
                                </td>
                                <td class="py-4 pr-4 text-sm text-gray-600"><?= date('M d, Y g:i A', strtotime($document['created_at'])) ?></td>
                                <td class="py-4 text-right">
                                    <a href="<?= base_url('/dashboard/downloadDocument/' . $document['id']) ?>" class="inline-flex items-center px-4 py-2 text-sm font-bold text-blue-700 bg-blue-50 border border-blue-100 rounded-lg hover:bg-blue-100 transition">
                                        Download
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch('<?= base_url('/dashboard/chartData') ?>');
        const data = await response.json();
        
        const labels = data.map(item => item.month);
        const revenues = data.map(item => item.total_income);

        // Fetch PHP Constant into Javascript safely
        const sysCurrency = '<?= defined("SYS_CURRENCY") ? SYS_CURRENCY : "$" ?>';

        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    // 🚀 DYNAMIC JAVASCRIPT LABEL
                    label: `Revenue (${sysCurrency})`,
                    data: revenues,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { borderDash: [4, 4], color: '#f3f4f6' },
                        // 🚀 Render dynamic currency on the Y-Axis ticks
                        ticks: {
                            callback: function(value, index, values) {
                                return sysCurrency + value;
                            }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    } catch (error) {
        console.error("Error loading chart data", error);
    }
});
</script>

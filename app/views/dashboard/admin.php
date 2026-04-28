<?php
$stats = $stats ?? [];
$projectStatusBreakdown = $project_status_breakdown ?? [];
$projectUpdates = $project_updates ?? [];
$activities = $activities ?? [];
$adminDocuments = $admin_documents ?? [];

$statusOrder = ['completed', 'in_progress', 'on_hold', 'at_risk', 'not_started'];
$statusBadgeClasses = [
    'completed' => 'bg-green-100 text-green-700',
    'in_progress' => 'bg-blue-100 text-blue-700',
    'on_hold' => 'bg-yellow-100 text-yellow-700',
    'at_risk' => 'bg-orange-100 text-orange-700',
    'not_started' => 'bg-red-100 text-red-700',
];
$statusLabelMap = [
    'completed' => 'Completed',
    'in_progress' => 'In Progress',
    'on_hold' => 'On Hold',
    'at_risk' => 'At Risk',
    'not_started' => 'Not Started',
];

$chartSeries = [];
foreach ($statusOrder as $statusKey) {
    if (!empty($projectStatusBreakdown[$statusKey])) {
        $chartSeries[] = $projectStatusBreakdown[$statusKey];
    }
}

$topStatus = $chartSeries[0] ?? ['label' => 'Projects', 'percentage' => 0];
foreach ($chartSeries as $seriesItem) {
    if (($seriesItem['count'] ?? 0) > ($topStatus['count'] ?? 0)) {
        $topStatus = $seriesItem;
    }
}

$chartJson = json_encode(array_values($chartSeries), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
?>

<div class="space-y-6">
    <?php if (Session::get('flash_success')): ?>
        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">
            <?= Session::get('flash_success'); Session::set('flash_success', null); ?>
        </div>
    <?php endif; ?>

    <?php if (Session::get('flash_error')): ?>
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
            <?= Session::get('flash_error'); Session::set('flash_error', null); ?>
        </div>
    <?php endif; ?>

    <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-gray-900"><?= defined('SYS_COMPANY_NAME') ? SYS_COMPANY_NAME : 'Admin' ?> Dashboard</h1>
            <p class="mt-1 text-sm font-medium text-gray-500">Enterprise overview and system analytics.</p>
        </div>
        <div class="text-sm font-semibold text-gray-500">
            <?= date('l, F j, Y') ?>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500">Total Revenue</p>
                    <p class="mt-2 text-3xl font-black text-gray-900"><?= defined('SYS_CURRENCY') ? SYS_CURRENCY : '$' ?><?= number_format((float) ($stats['total_revenue'] ?? 0), 2) ?></p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-green-50 text-green-600">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500">Active Projects</p>
                    <p class="mt-2 text-3xl font-black text-gray-900"><?= (int) ($stats['active_projects'] ?? 0) ?></p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6m4 6V7m4 10V4M5 20h14"></path></svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500">Total Employees</p>
                    <p class="mt-2 text-3xl font-black text-gray-900"><?= (int) ($stats['total_employees'] ?? 0) ?></p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500">Pending Tasks</p>
                    <p class="mt-2 text-3xl font-black text-gray-900"><?= (int) ($stats['pending_tasks'] ?? 0) ?></p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="mb-4">
                <h2 class="text-xl font-black text-gray-900">Overall Project Status</h2>
            </div>

            <?php if (empty($chartSeries)): ?>
                <div class="flex h-80 items-center justify-center rounded-2xl border border-dashed border-gray-200 bg-gray-50 text-sm font-semibold text-gray-400">
                    No project data available yet.
                </div>
            <?php else: ?>
                <div class="grid gap-6 lg:grid-cols-[300px,1fr] lg:items-center">
                    <div class="relative mx-auto h-64 w-64">
                        <canvas id="projectStatusPieChart"></canvas>
                        <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center text-center">
                            <p id="primaryStatusPercentage" class="text-4xl font-black text-gray-900"><?= (int) ($topStatus['percentage'] ?? 0) ?>%</p>
                            <p id="primaryStatusLabel" class="mt-2 text-xs font-black uppercase tracking-[0.2em] text-blue-600"><?= sanitize($topStatus['label'] ?? 'Projects') ?></p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <?php foreach ($chartSeries as $seriesItem): ?>
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-3">
                                    <span class="mt-1 inline-block h-3.5 w-3.5 rounded-sm" style="background-color: <?= sanitize($seriesItem['color']) ?>"></span>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900"><?= sanitize($seriesItem['label']) ?></p>
                                        <p class="text-sm text-gray-500"><?= (int) $seriesItem['count'] ?> Project<?= (int) $seriesItem['count'] === 1 ? '' : 's' ?> (<?= (int) $seriesItem['percentage'] ?>%)</p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="mb-4">
                <h2 class="text-xl font-black text-gray-900">Project Update Overview</h2>
                <p class="mt-1 text-sm text-gray-500">Projects grouped by current execution status.</p>
            </div>

            <?php if (empty($chartSeries)): ?>
                <div class="flex h-80 items-center justify-center rounded-2xl border border-dashed border-gray-200 bg-gray-50 text-sm font-semibold text-gray-400">
                    No project data available yet.
                </div>
            <?php else: ?>
                <div class="relative h-80">
                    <canvas id="projectStatusBarChart"></canvas>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1.35fr,1fr]">
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="mb-4">
                <h2 class="text-xl font-black text-gray-900">Project Updates</h2>
                <p class="mt-1 text-sm text-gray-500">Latest project activity driven by the project records in the system.</p>
            </div>

            <?php if (empty($projectUpdates)): ?>
                <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-10 text-center text-sm font-semibold text-gray-400">
                    No projects available to display.
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 text-xs uppercase tracking-[0.15em] text-gray-400">
                                <th class="pb-3 pr-4 font-bold">Project Name</th>
                                <th class="pb-3 pr-4 font-bold">Manager</th>
                                <th class="pb-3 pr-4 font-bold">Status</th>
                                <th class="pb-3 pr-4 font-bold">Progress</th>
                                <th class="pb-3 font-bold">Last Update</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($projectUpdates as $projectItem): ?>
                                <?php
                                $dashboardStatus = $projectItem['dashboard_status'] ?? 'in_progress';
                                $badgeClass = $statusBadgeClasses[$dashboardStatus] ?? 'bg-gray-100 text-gray-700';
                                $progressValue = max(0, min(100, (int) ($projectItem['progress_pct'] ?? 0)));
                                $managerName = trim((string) ($projectItem['manager_name'] ?? ''));
                                ?>
                                <tr>
                                    <td class="py-3 pr-4">
                                        <a href="<?= base_url('/project/show/' . (int) $projectItem['id']) ?>" class="font-semibold text-gray-900 hover:text-blue-600">
                                            <?= sanitize($projectItem['name'] ?? 'Untitled Project') ?>
                                        </a>
                                    </td>
                                    <td class="py-3 pr-4 text-gray-600"><?= sanitize($managerName !== '' ? $managerName : 'Unassigned') ?></td>
                                    <td class="py-3 pr-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold <?= $badgeClass ?>">
                                            <?= sanitize($statusLabelMap[$dashboardStatus] ?? 'In Progress') ?>
                                        </span>
                                    </td>
                                    <td class="py-3 pr-4">
                                        <div class="flex items-center gap-3">
                                            <span class="w-10 text-xs font-bold text-gray-600"><?= $progressValue ?>%</span>
                                            <div class="h-2 w-28 rounded-full bg-gray-100">
                                                <div class="h-2 rounded-full <?= $dashboardStatus === 'completed' ? 'bg-green-500' : ($dashboardStatus === 'on_hold' ? 'bg-yellow-400' : ($dashboardStatus === 'at_risk' ? 'bg-orange-500' : ($dashboardStatus === 'not_started' ? 'bg-red-500' : 'bg-blue-500'))) ?>" style="width: <?= $progressValue ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-gray-600"><?= !empty($projectItem['updated_at']) ? date('M d, Y', strtotime($projectItem['updated_at'])) : 'N/A' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="mb-4">
                <h2 class="text-xl font-black text-gray-900">Status Distribution (By Projects)</h2>
            </div>

            <?php if (empty($chartSeries)): ?>
                <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-10 text-center text-sm font-semibold text-gray-400">
                    No status distribution available yet.
                </div>
            <?php else: ?>
                <div class="space-y-5">
                    <div class="grid grid-cols-[1.3fr,1.4fr,0.5fr,0.6fr] gap-4 border-b border-gray-100 pb-3 text-xs font-bold uppercase tracking-[0.15em] text-gray-400">
                        <div>Status</div>
                        <div></div>
                        <div>Projects</div>
                        <div>Percentage</div>
                    </div>
                    <?php foreach ($chartSeries as $seriesItem): ?>
                        <div class="grid grid-cols-[1.3fr,1.4fr,0.5fr,0.6fr] items-center gap-4 text-sm">
                            <div class="font-medium text-gray-700"><?= sanitize($seriesItem['label']) ?></div>
                            <div class="h-2.5 rounded-full bg-gray-100">
                                <div class="h-2.5 rounded-full" style="width: <?= (int) $seriesItem['percentage'] ?>%; background-color: <?= sanitize($seriesItem['color']) ?>"></div>
                            </div>
                            <div class="font-semibold text-gray-700"><?= (int) $seriesItem['count'] ?></div>
                            <div class="font-semibold text-gray-700"><?= (int) $seriesItem['percentage'] ?>%</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr,1.15fr]">
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="mb-4">
                <h2 class="text-xl font-black text-gray-900">System Activity</h2>
            </div>

            <?php if (empty($activities)): ?>
                <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-10 text-center text-sm font-semibold text-gray-400">
                    No recent activity recorded.
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($activities as $log): ?>
                        <div class="flex gap-3">
                            <img src="<?= $log['avatar'] ?? 'https://ui-avatars.com/api/?name=' . urlencode(trim(($log['first_name'] ?? 'System') . ' ' . ($log['last_name'] ?? 'User'))) ?>" alt="User avatar" class="h-10 w-10 rounded-full border border-gray-100 object-cover">
                            <div>
                                <p class="text-sm text-gray-800">
                                    <span class="font-semibold"><?= sanitize(trim(($log['first_name'] ?? '') . ' ' . ($log['last_name'] ?? ''))) ?: 'System User' ?></span>
                                    <?= ' ' . sanitize($log['action'] ?? 'updated the dashboard') ?>
                                </p>
                                <p class="text-xs text-gray-500"><?= !empty($log['created_at']) ? date('M d, g:i A', strtotime($log['created_at'])) : 'N/A' ?> &middot; <?= sanitize($log['module'] ?? 'System') ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-black text-gray-900">Admin Document Inbox</h2>
                    <p class="mt-1 text-sm text-gray-500">Recent BD uploads available for admin and owner roles.</p>
                </div>
                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.15em] text-gray-500">Private</span>
            </div>

            <?php if (empty($adminDocuments)): ?>
                <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-10 text-center text-sm font-semibold text-gray-400">
                    No BD documents have been uploaded yet.
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 text-xs uppercase tracking-[0.15em] text-gray-400">
                                <th class="pb-3 pr-4 font-bold">Project</th>
                                <th class="pb-3 pr-4 font-bold">Document</th>
                                <th class="pb-3 pr-4 font-bold">Uploaded By</th>
                                <th class="pb-3 pr-4 font-bold">Date</th>
                                <th class="pb-3 text-right font-bold">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($adminDocuments as $document): ?>
                                <tr>
                                    <td class="py-3 pr-4">
                                        <div class="font-semibold text-gray-900"><?= sanitize($document['project_name'] ?? 'General BD Document') ?></div>
                                        <div class="text-xs text-gray-500"><?= !empty($document['proposal_id']) ? 'Proposal #' . (int) $document['proposal_id'] : 'BD Inbox' ?></div>
                                    </td>
                                    <td class="py-3 pr-4">
                                        <div class="font-semibold text-gray-900"><?= sanitize($document['original_name'] ?? 'Document') ?></div>
                                        <div class="text-xs text-gray-500"><?= number_format(((int) ($document['file_size'] ?? 0)) / 1024, 1) ?> KB</div>
                                    </td>
                                    <td class="py-3 pr-4 text-gray-600"><?= sanitize(trim(($document['first_name'] ?? '') . ' ' . ($document['last_name'] ?? ''))) ?: 'System' ?></td>
                                    <td class="py-3 pr-4 text-gray-600"><?= !empty($document['created_at']) ? date('M d, Y g:i A', strtotime($document['created_at'])) : 'N/A' ?></td>
                                    <td class="py-3 text-right">
                                        <a href="<?= base_url('/dashboard/downloadDocument/' . (int) $document['id']) ?>" class="inline-flex items-center rounded-lg bg-blue-50 px-4 py-2 text-sm font-bold text-blue-700 hover:bg-blue-100">
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
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusSeries = <?= $chartJson ?: '[]' ?>;

    if (!Array.isArray(statusSeries) || statusSeries.length === 0) {
        return;
    }

    const labels = statusSeries.map(item => item.label);
    const values = statusSeries.map(item => Number(item.count || 0));
    const colors = statusSeries.map(item => item.color);

    const pieCanvas = document.getElementById('projectStatusPieChart');
    if (pieCanvas) {
        new Chart(pieCanvas.getContext('2d'), {
            type: 'pie',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const count = context.raw || 0;
                                const total = values.reduce((sum, value) => sum + value, 0);
                                const percentage = total > 0 ? Math.round((count / total) * 100) : 0;
                                return `${context.label}: ${count} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    const barCanvas = document.getElementById('projectStatusBarChart');
    if (barCanvas) {
        new Chart(barCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Projects',
                    data: values,
                    backgroundColor: colors,
                    borderRadius: 8,
                    maxBarThickness: 48
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            color: '#eef2f7'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
});
</script>

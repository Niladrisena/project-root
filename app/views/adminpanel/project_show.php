<?php
/**
 * Project Details View - Production Ready
 * Normalizes font sizes for standard professional dashboards.
 */
$project = $project ?? [];
$resources = $resources ?? [];
$tasks = $tasks ?? [];
$files = $files ?? [];
$progress = max(0, min(100, (int) ($progress ?? ($project['progress_pct'] ?? 0))));
$statusCounts = $status_counts ?? ['todo' => 0, 'in_progress' => 0, 'review' => 0, 'completed' => 0];

$status = strtolower((string) ($project['status'] ?? 'planning'));
$priority = strtolower((string) ($project['priority'] ?? 'medium'));
$managerName = trim((string) (($project['manager_first_name'] ?? '') . ' ' . ($project['manager_last_name'] ?? '')));
$clientName = trim((string) ($project['company_name'] ?? ''));
$projectCode = (string) ($project['project_code'] ?? 'PRJ');
$deadlineLabel = !empty($project['deadline']) ? date('M d, Y', strtotime($project['deadline'])) : 'No deadline set';
$startLabel = !empty($project['start_date']) ? date('M d, Y', strtotime($project['start_date'])) : 'Not scheduled';
$budgetValue = (float) ($project['estimated_budget'] ?? 0);
$hoursValue = (float) ($project['total_hours'] ?? 0);
$teamCount = count($resources);
$doneTasks = (int) ($statusCounts['completed'] ?? 0);
$totalTasks = count($tasks);

$statusClass = match ($status) {
    'completed'   => 'bg-emerald-50 text-emerald-700 border-emerald-100',
    'in_progress' => 'bg-blue-50 text-blue-700 border-blue-100',
    'on_hold'     => 'bg-amber-50 text-amber-700 border-amber-100',
    'cancelled'   => 'bg-rose-50 text-rose-700 border-rose-100',
    default       => 'bg-gray-50 text-gray-700 border-gray-100',
};

$priorityClass = match ($priority) {
    'urgent' => 'bg-red-50 text-red-700 border-red-100',
    'high'   => 'bg-rose-50 text-rose-700 border-rose-100',
    'medium' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
    default  => 'bg-gray-50 text-gray-700 border-gray-100',
};
?>

<div class="mx-auto max-w-7xl space-y-6 pb-10">
    <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-6 py-6 md:px-8">
            <div class="flex flex-col gap-6 xl:flex-row xl:items-start xl:justify-between">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="rounded-lg bg-blue-50 px-3 py-1 text-xs font-bold tracking-wider text-blue-700 border border-blue-100">
                            <?= sanitize($projectCode) ?>
                        </span>
                        <span class="rounded-lg px-3 py-1 text-xs font-bold uppercase tracking-wider border <?= $priorityClass ?>">
                            <?= sanitize($priority) ?> Priority
                        </span>
                        <span class="rounded-lg px-3 py-1 text-xs font-bold uppercase tracking-wider border <?= $statusClass ?>">
                            <?= sanitize(str_replace('_', ' ', $status)) ?>
                        </span>
                    </div>

                    <h1 class="mt-4 text-2xl font-bold text-gray-900"><?= sanitize($project['name'] ?? 'Untitled Project') ?></h1>

                    <div class="mt-3 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm font-medium text-gray-500">
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z"></path>
                            </svg>
                            Due: <?= $deadlineLabel ?>
                        </span>
                        <span class="text-gray-400">•</span>
                        <span><?= $clientName !== '' ? sanitize($clientName) : 'No client assigned' ?></span>
                    </div>
                </div>

                <div class="w-full max-w-sm rounded-xl border border-gray-100 bg-gray-50/50 p-5">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-bold text-gray-900">Project Progress</h2>
                        <span class="text-lg font-bold text-blue-600"><?= $progress ?>%</span>
                    </div>
                    <div class="mt-2 h-2 rounded-full bg-white border border-gray-100">
                        <div class="h-full rounded-full bg-blue-600" style="width: <?= $progress ?>%"></div>
                    </div>
                    <div class="mt-6 grid grid-cols-2 gap-3 text-center">
                        <div class="rounded-lg bg-white p-3 border border-gray-100">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Team Size</p>
                            <p class="text-xl font-bold text-gray-900"><?= $teamCount ?></p>
                        </div>
                        <div class="rounded-lg bg-white p-3 border border-gray-100">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Tasks</p>
                            <p class="text-xl font-bold text-gray-900"><?= $doneTasks ?>/<?= $totalTasks ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-6 md:px-8">
            <div class="grid grid-cols-1 gap-6 xl:grid-cols-4">
                
                <div class="rounded-xl border border-gray-100 bg-white p-5">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Deadline</p>
                    <p class="mt-4 text-2xl font-bold text-gray-900"><?= !empty($project['deadline']) ? date('M j, Y', strtotime($project['deadline'])) : 'Not set' ?></p>
                    <div class="mt-4 border-t border-gray-50 pt-4 flex justify-between items-center">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Start Date</span>
                        <span class="text-xs font-bold text-gray-700"><?= $startLabel ?></span>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-100 bg-white p-5 xl:col-span-1">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-gray-900">Team Members</h3>
                        <span class="text-[10px] font-bold text-blue-600 uppercase"><?= $teamCount ?> Total</span>
                    </div>
                    <div class="space-y-2.5 max-h-48 overflow-y-auto pr-1">
                        <?php foreach ($resources as $member): ?>
                            <div class="flex items-center gap-2.5 rounded-lg border border-gray-50 bg-gray-50/50 p-2 text-xs">
                                <div class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-100 text-[10px] font-bold text-blue-700">
                                    <?= sanitize(strtoupper(substr($member['first_name'] ?? 'U', 0, 1) . substr($member['last_name'] ?? 'N', 0, 1))) ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-gray-900 truncate"><?= sanitize($member['first_name'] . ' ' . $member['last_name']) ?></p>
                                    <p class="text-[10px] text-gray-400"><?= sanitize(ucfirst($member['role_in_project'] ?? 'Member')) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-100 bg-white p-5">
                    <h3 class="text-sm font-bold text-gray-900 mb-4">Project Manager</h3>
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-full bg-gray-100 border border-gray-200 overflow-hidden">
                            <div class="flex h-full w-full items-center justify-center text-xs font-bold text-gray-400"><?= sanitize(strtoupper(substr($managerName ?: 'PM', 0, 2))) ?></div>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-900 truncate"><?= sanitize($managerName ?: 'Unassigned') ?></p>
                            <p class="text-[11px] text-gray-400 truncate"><?= sanitize($project['manager_email'] ?? 'No email') ?></p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-blue-50 bg-blue-50/30 p-5">
                    <h3 class="text-sm font-bold text-gray-900">Budget Estimate</h3>
                    <div class="mt-4">
                        <p class="text-2xl font-bold text-gray-900"><?= defined('SYS_CURRENCY') ? SYS_CURRENCY : '$' ?><?= number_format($budgetValue, 2) ?></p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Client: <?= sanitize($clientName ?: 'Internal') ?></p>
                    </div>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-2">
                <div class="rounded-xl border border-gray-100 bg-white p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-3">Description</h3>
                    <p class="text-sm leading-relaxed text-gray-600"><?= sanitize($project['description'] ?? 'No description provided.') ?></p>
                </div>

                <div class="rounded-xl border border-gray-100 bg-white p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4">Task Snapshot</h3>
                    <div class="grid grid-cols-2 gap-x-6 gap-y-4">
                        <?php
                        $taskMeta = [
                            'todo' => ['label' => 'To Do', 'color' => 'bg-gray-400'],
                            'in_progress' => ['label' => 'In Progress', 'color' => 'bg-blue-500'],
                            'review' => ['label' => 'In Review', 'color' => 'bg-amber-500'],
                            'completed' => ['label' => 'Completed', 'color' => 'bg-emerald-500'],
                        ];
                        foreach ($taskMeta as $key => $meta):
                            $count = (int) ($statusCounts[$key] ?? 0);
                            $pct = $totalTasks > 0 ? ($count / $totalTasks) * 100 : 0;
                        ?>
                            <div>
                                <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase mb-1.5">
                                    <span><?= $meta['label'] ?></span>
                                    <span class="text-gray-700"><?= $count ?></span>
                                </div>
                                <div class="h-1.5 rounded-full bg-gray-50">
                                    <div class="h-full rounded-full <?= $meta['color'] ?>" style="width: <?= $pct ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="flex justify-start">
        <a href="<?= base_url('/adminpanel/projectManagement') ?>" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-blue-600 transition">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Projects
        </a>
    </div>
</div>
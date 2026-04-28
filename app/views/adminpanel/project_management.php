<?php
/**
 * Project Management Overview - Admin Panel
 * Normalized Font Sizes & Production Ready UI
 */
$projects = $projects ?? [];

$statusClasses = [
    'planning'    => 'bg-slate-100 text-slate-700',
    'in_progress' => 'bg-blue-100 text-blue-700',
    'completed'   => 'bg-emerald-100 text-emerald-700',
    'on_hold'     => 'bg-amber-100 text-amber-700',
    'cancelled'   => 'bg-rose-100 text-rose-700',
];

$priorityClasses = [
    'low'    => 'bg-slate-100 text-slate-700',
    'medium' => 'bg-indigo-100 text-indigo-700',
    'high'   => 'bg-rose-100 text-rose-700',
    'urgent' => 'bg-red-100 text-red-700',
];
?>

<div class="mx-auto max-w-7xl space-y-6">
    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 xl:flex-row xl:items-center xl:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Projects Overview</h1>
            <p class="mt-1 text-sm text-gray-500">Manage active corporate projects, timelines, and team assignments.</p>
        </div>

        <div class="flex flex-col gap-3 md:flex-row md:items-center">
            <div class="relative">
                <input id="admin-project-search" type="text" placeholder="Search projects..." 
                       class="w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-sm font-medium text-gray-700 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 md:w-80">
                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <?php if (Auth::can('create', 'projects')): ?>
                <a href="<?= base_url('/project/create') ?>" 
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Project
                </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (empty($projects)): ?>
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-12 text-center shadow-sm">
            <h2 class="text-lg font-bold text-gray-900">No projects found</h2>
            <p class="mt-1 text-sm text-gray-500">Create a new project to get started.</p>
        </div>
    <?php else: ?>
        <div id="admin-project-grid" class="grid grid-cols-1 gap-6 xl:grid-cols-2 2xl:grid-cols-3">
            <?php foreach ($projects as $project): ?>
                <?php
                $status = strtolower((string) ($project['status'] ?? 'planning'));
                $priority = strtolower((string) ($project['priority'] ?? 'medium'));
                $statusClass = $statusClasses[$status] ?? 'bg-gray-100 text-gray-700';
                $priorityClass = $priorityClasses[$priority] ?? 'bg-gray-100 text-gray-700';
                $managerName = trim((string) (($project['first_name'] ?? '') . ' ' . ($project['last_name'] ?? '')));
                $deadlineLabel = !empty($project['deadline']) ? date('M d, Y', strtotime($project['deadline'])) : 'No deadline';
                $progressValue = max(0, min(100, (int) ($project['progress_pct'] ?? 0)));
                $searchText = strtolower(trim(
                    ($project['name'] ?? '') . ' ' . ($project['description'] ?? '') . ' ' . $managerName
                ));
                ?>

                <article class="admin-project-card rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:shadow-md" data-search="<?= sanitize($searchText) ?>">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-wider <?= $statusClass ?>">
                                <?= sanitize(str_replace('_', ' ', $status)) ?>
                            </span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest"><?= sanitize($project['project_code'] ?? 'PRJ') ?></span>
                        </div>
                        <?php if (!empty($project['priority'])): ?>
                            <span class="inline-flex rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-wider <?= $priorityClass ?>">
                                <?= sanitize($priority) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="mt-5">
                        <h2 class="text-xl font-bold text-gray-900 line-clamp-1"><?= sanitize($project['name'] ?? 'Untitled Project') ?></h2>
                        <p class="mt-1 text-sm text-gray-500 line-clamp-2 min-h-[2.5rem]"><?= sanitize($project['description'] ?? 'No description provided.') ?></p>
                    </div>

                    <div class="mt-5">
                        <div class="flex items-center justify-between text-xs font-bold text-gray-500">
                            <span>Overall Progress</span>
                            <span class="text-gray-900"><?= $progressValue ?>%</span>
                        </div>
                        <div class="mt-2 h-2 rounded-full bg-gray-100">
                            <div class="h-2 rounded-full bg-blue-600" style="width: <?= $progressValue ?>%"></div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between border-t border-gray-50 pt-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-50 text-xs font-bold text-blue-600">
                                <?= sanitize(strtoupper(substr($managerName !== '' ? $managerName : 'PM', 0, 2))) ?>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900"><?= sanitize($managerName !== '' ? $managerName : 'Project Manager') ?></p>
                                <p class="text-[11px] font-medium text-gray-500"><?= $deadlineLabel ?></p>
                            </div>
                        </div>

                        <a href="<?= base_url('/adminpanel/projectShow/' . (int) $project['id']) ?>" 
                           class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-400 transition hover:bg-blue-50 hover:text-blue-600 shadow-sm" aria-label="Open project">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('admin-project-search');
    const cards = document.querySelectorAll('.admin-project-card');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = this.value.trim().toLowerCase();
            cards.forEach(card => {
                const text = card.getAttribute('data-search') || '';
                card.style.display = text.includes(query) ? 'block' : 'none';
            });
        });
    }
});
</script>

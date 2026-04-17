<?php
    $planningMeta = [
        'project_name' => 'Kingdom Tower Phase 3',
        'project_manager' => 'Project Manager (PR)',
        'start_date' => 'April 10, 2026',
        'end_date' => 'July 31, 2026',
        'priority' => 'High',
    ];

    $departments = [
        [
            'name' => 'Mechanical',
            'icon_bg' => 'bg-emerald-100',
            'icon_text' => 'text-emerald-700',
            'required' => 6,
            'assigned' => 3,
            'availability' => '2 available now',
            'members' => ['John Doe', 'Jane Smith', 'Robert Brown'],
        ],
        [
            'name' => 'Electrical',
            'icon_bg' => 'bg-amber-100',
            'icon_text' => 'text-amber-700',
            'required' => 5,
            'assigned' => 2,
            'availability' => '1 available now',
            'members' => ['Megan Hall', 'Aron Blake'],
        ],
        [
            'name' => 'Architecture',
            'icon_bg' => 'bg-blue-100',
            'icon_text' => 'text-blue-700',
            'required' => 4,
            'assigned' => 1,
            'availability' => '3 in design pool',
            'members' => ['Nina White'],
        ],
        [
            'name' => 'Civil & Structural',
            'icon_bg' => 'bg-violet-100',
            'icon_text' => 'text-violet-700',
            'required' => 3,
            'assigned' => 1,
            'availability' => '1 available now',
            'members' => ['Darren Cole'],
        ],
    ];

    $candidateMembers = [
        ['name' => 'John Doe', 'status' => 'Available', 'avatar' => 'JD'],
        ['name' => 'Jane Smith', 'status' => 'Inactive', 'avatar' => 'JS'],
        ['name' => 'Robert Brown', 'status' => 'Available', 'avatar' => 'RB'],
        ['name' => 'Garm Brown', 'status' => 'Inactive', 'avatar' => 'GB'],
    ];

    $totalRequired = array_sum(array_column($departments, 'required'));
    $totalAssigned = array_sum(array_column($departments, 'assigned'));
    $totalGap = max($totalRequired - $totalAssigned, 0);
    $allocationRate = $totalRequired > 0 ? round(($totalAssigned / $totalRequired) * 100, 1) : 0;
?>

<div class="mx-auto max-w-7xl pb-10">
    <section class="overflow-hidden rounded-[30px] border border-slate-200/80 bg-white shadow-[0_24px_80px_-36px_rgba(15,23,42,0.35)]">
        <div class="border-b border-slate-100 px-6 py-6 md:px-8">
            <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-2xl bg-violet-100 text-violet-700">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17h6M10 5h4m-7 4h10M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2zm8 12 4 4m0-4-4 4"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black tracking-tight text-slate-900">Resource Planning Form</h1>
                        <p class="mt-2 max-w-2xl text-sm text-slate-500">Plan and allocate required manpower by department for your project.</p>
                    </div>
                </div>

                <button type="button" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-100">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 16V4m0 12 4-4m-4 4-4-4M4 20h16"></path>
                    </svg>
                    Export PDF
                </button>
            </div>
        </div>

        <div class="border-b border-slate-100 bg-slate-50/70 px-6 py-5 md:px-8">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Project Name</label>
                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm">
                        <span><?= sanitize($planningMeta['project_name']) ?></span>
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Project Manager</label>
                    <div class="rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-400 shadow-sm">
                        <?= sanitize($planningMeta['project_manager']) ?>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Start Date</label>
                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm">
                        <span><?= sanitize($planningMeta['start_date']) ?></span>
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">End Date</label>
                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm">
                        <span><?= sanitize($planningMeta['end_date']) ?></span>
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Priority</label>
                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm">
                        <span class="inline-flex items-center gap-2 text-amber-700">
                            <span class="h-3 w-3 rounded-full bg-amber-500"></span>
                            <?= sanitize($planningMeta['priority']) ?>
                        </span>
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-6 md:px-8">
            <div class="overflow-hidden rounded-[28px] border border-violet-100 bg-[linear-gradient(180deg,rgba(245,243,255,0.95)_0%,rgba(255,255,255,1)_22%)] shadow-[0_20px_60px_-40px_rgba(109,40,217,0.45)]">
                <div class="flex flex-col gap-4 border-b border-violet-100/80 px-6 py-6 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl bg-violet-100 text-violet-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.36-1.86M17 20H7m10 0v-2a5 5 0 00-10 0v2m10-8a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black tracking-tight text-slate-900">Resource Planning by Department</h2>
                            <p class="mt-1 text-sm text-slate-500">Define team demand, track current allocation, and open assignment actions for each delivery discipline.</p>
                        </div>
                    </div>

                    <button type="button" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-violet-200 bg-violet-50 px-4 py-3 text-sm font-bold text-violet-700 transition hover:bg-violet-100">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Department
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-white/70">
                            <tr class="text-left text-sm font-bold text-slate-500">
                                <th class="px-6 py-4">Department</th>
                                <th class="px-6 py-4">Required</th>
                                <th class="px-6 py-4">Assigned</th>
                                <th class="px-6 py-4">Availability</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-violet-100/80 bg-white">
                            <?php foreach ($departments as $index => $department): ?>
                                <?php $gap = max((int) $department['required'] - (int) $department['assigned'], 0); ?>
                                <tr class="align-middle transition hover:bg-violet-50/40">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl <?= $department['icon_bg'] ?> <?= $department['icon_text'] ?>">
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2 2 7l10 5 10-5-10-5Zm0 8.7L4.4 7 12 3.3 19.6 7 12 10.7Zm-8 1.8 8 4 8-4V17l-8 4-8-4v-4.5Z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xl font-black text-slate-900"><?= sanitize($department['name']) ?></p>
                                                <p class="mt-1 text-sm text-slate-500"><?= sanitize(implode(', ', $department['members'])) ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="inline-flex min-w-[68px] justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-base font-bold text-slate-700 shadow-sm">
                                            <?= (int) $department['required'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="space-y-2">
                                            <span class="inline-flex min-w-[68px] justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-base font-bold text-slate-700 shadow-sm">
                                                <?= (int) $department['assigned'] ?>
                                            </span>
                                            <p class="text-xs font-semibold <?= $gap > 0 ? 'text-rose-500' : 'text-emerald-600' ?>">
                                                <?= $gap > 0 ? $gap . ' pending' : 'Fully allocated' ?>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-sm font-bold text-emerald-700">
                                            <?= sanitize($department['availability']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center justify-end gap-3">
                                            <button
                                                type="button"
                                                class="assign-member-btn inline-flex items-center gap-2 rounded-2xl border border-violet-300 bg-white px-4 py-3 text-sm font-bold text-violet-700 transition hover:bg-violet-50"
                                                data-department="<?= sanitize($department['name']) ?>"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Assign Member
                                            </button>
                                            <button type="button" class="rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600" aria-label="More options">
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 7a2 2 0 110-4 2 2 0 010 4Zm0 7a2 2 0 110-4 2 2 0 010 4Zm0 7a2 2 0 110-4 2 2 0 010 4Z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 rounded-[28px] border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <div class="flex items-start gap-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 3v18m0 0h14M5 12h14M12 3v18"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-900">Summary</h3>
                            <p class="mt-1 text-sm text-slate-500">Overview of resource requirement vs allocation.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 px-6 py-6 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-sm font-semibold text-slate-500">Total Required</p>
                        <p class="mt-2 text-4xl font-black text-slate-900"><?= $totalRequired ?></p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-sm font-semibold text-slate-500">Total Assigned</p>
                        <p class="mt-2 text-4xl font-black text-emerald-600"><?= $totalAssigned ?></p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-sm font-semibold text-slate-500">Total Gap</p>
                        <p class="mt-2 text-4xl font-black text-rose-500"><?= $totalGap ?></p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-sm font-semibold text-slate-500">Allocation %</p>
                        <p class="mt-2 text-4xl font-black text-slate-900"><?= $allocationRate ?>%</p>
                        <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-200">
                            <div class="h-full rounded-full bg-gradient-to-r from-rose-400 via-amber-400 to-emerald-500" style="width: <?= $allocationRate ?>%"></div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-100 px-6 py-5 md:flex-row md:items-center md:justify-end">
                    <button type="button" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-500 transition hover:bg-slate-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Draft
                    </button>
                    <button type="button" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-violet-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-violet-200 transition hover:bg-violet-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14m-7-7 7 7-7 7"></path>
                        </svg>
                        Submit Plan
                    </button>
                    <button type="button" class="inline-flex items-center justify-center rounded-2xl px-4 py-3 text-sm font-semibold text-slate-400 transition hover:bg-slate-50 hover:text-slate-600">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="resource-planning-modal" class="pointer-events-none fixed inset-0 z-40 flex items-center justify-center bg-slate-950/40 px-4 opacity-0 transition duration-200">
    <div class="absolute inset-0" data-modal-close></div>
    <div class="relative z-10 w-full max-w-3xl rounded-[28px] bg-white shadow-[0_30px_100px_-30px_rgba(15,23,42,0.55)]">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
            <div>
                <h3 id="resource-planning-modal-title" class="text-2xl font-black text-slate-900">Add Member to Mechanical Department</h3>
            </div>
            <button type="button" class="rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600" data-modal-close aria-label="Close">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18 18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6">
            <div class="overflow-hidden rounded-3xl border border-slate-200">
                <table class="min-w-full">
                    <thead class="bg-violet-50/70">
                        <tr class="text-left text-sm font-bold text-slate-700">
                            <th class="px-5 py-4">Employee Name</th>
                            <th class="px-5 py-4">Current Status</th>
                            <th class="px-5 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <?php foreach ($candidateMembers as $member): ?>
                            <?php
                                $isAvailable = strtolower($member['status']) === 'available';
                                $statusClasses = $isAvailable
                                    ? 'bg-emerald-50 text-emerald-700 border-emerald-100'
                                    : 'bg-rose-50 text-rose-700 border-rose-100';
                                $avatarClasses = $isAvailable
                                    ? 'from-slate-700 to-slate-500'
                                    : 'from-amber-500 to-rose-400';
                            ?>
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br <?= $avatarClasses ?> text-sm font-black text-white shadow-sm">
                                            <?= sanitize($member['avatar']) ?>
                                        </div>
                                        <span class="text-lg font-bold text-slate-900"><?= sanitize($member['name']) ?></span>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-xl border px-3 py-2 text-sm font-bold <?= $statusClasses ?>">
                                        <?= sanitize($member['status']) ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <button type="button" class="inline-flex items-center gap-2 rounded-2xl border border-violet-300 bg-white px-4 py-2.5 text-sm font-bold text-violet-700 transition hover:bg-violet-50">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Assign
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const modal = document.getElementById('resource-planning-modal');
        const modalTitle = document.getElementById('resource-planning-modal-title');
        const openButtons = document.querySelectorAll('.assign-member-btn');
        const closeButtons = document.querySelectorAll('[data-modal-close]');

        const openModal = (departmentName) => {
            modalTitle.textContent = `Add Member to ${departmentName} Department`;
            modal.classList.remove('pointer-events-none', 'opacity-0');
            modal.classList.add('opacity-100');
            document.body.classList.add('overflow-hidden');
        };

        const closeModal = () => {
            modal.classList.add('pointer-events-none', 'opacity-0');
            modal.classList.remove('opacity-100');
            document.body.classList.remove('overflow-hidden');
        };

        openButtons.forEach((button) => {
            button.addEventListener('click', () => openModal(button.dataset.department || 'Selected'));
        });

        closeButtons.forEach((button) => {
            button.addEventListener('click', closeModal);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !modal.classList.contains('pointer-events-none')) {
                closeModal();
            }
        });
    })();
</script>

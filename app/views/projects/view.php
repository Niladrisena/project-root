<div class="max-w-7xl mx-auto pb-12 relative">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php
        $projectManagerName = trim(($project['first_name'] ?? '') . ' ' . ($project['last_name'] ?? ''));
        $projectManagerName = $projectManagerName !== '' ? $projectManagerName : 'Project Manager';
        $resourcePlan = $resourcePlan ?? [];
        $planRows = $resourcePlan['rows'] ?? [];
        $planPriority = strtolower((string) ($resourcePlan['priority'] ?? ($project['priority'] ?? 'medium')));
        $planManagerLabel = $resourcePlan['project_manager_label'] ?? $projectManagerName;
        $planStartDate = $resourcePlan['start_date'] ?? ($project['start_date'] ?? '');
        $planEndDate = $resourcePlan['end_date'] ?? ($project['deadline'] ?? '');

        $departmentMeta = [
            'mechanical' => ['icon_bg' => 'bg-emerald-500', 'emoji' => '⚙'],
            'electrical' => ['icon_bg' => 'bg-amber-500', 'emoji' => '⚡'],
            'architecture' => ['icon_bg' => 'bg-blue-500', 'emoji' => '🏛'],
            'structure' => ['icon_bg' => 'bg-rose-500', 'emoji' => '🏗'],
        ];
        $hourPlan = $hourPlan ?? [];
        $hourPlanRows = $hourPlan['rows'] ?? [];
        $planningMonth = $hourPlan['planning_month'] ?? date('F Y');
        $planningMonthValue = '';
        if (!empty($planningMonth)) {
            $planningTimestamp = strtotime($planningMonth);
            if ($planningTimestamp !== false) {
                $planningMonthValue = date('Y-m', $planningTimestamp);
            }
        }
        if ($planningMonthValue === '') {
            $planningMonthValue = date('Y-m');
        }
         $availableResources = $availableResources ?? [];
    ?>

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="px-3 py-1 text-xs font-bold text-blue-800 bg-blue-100 rounded-md tracking-wider">
                    <?= sanitize($project['project_code'] ?? 'PRJ-UNKNOWN') ?>
                </span>
                <span class="px-3 py-1 text-xs font-bold uppercase rounded-md tracking-wider
                    <?= ($project['priority'] ?? '') == 'high' || ($project['priority'] ?? '') == 'urgent' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' ?>">
                    <?= sanitize($project['priority'] ?? 'Medium') ?> Priority
                </span>
            </div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight"><?= sanitize($project['name']) ?></h1>
            <div class="flex items-center gap-6 mt-3 text-sm text-gray-500 font-medium">
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Due: <?= date('M d, Y', strtotime($project['deadline'])) ?>
                </span>
            </div>
        </div>
        <a href="<?= base_url('/project') ?>" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg shadow-sm text-sm font-bold hover:bg-gray-50 transition">
            &larr; Back to Projects
        </a>
    </div>

    <div class="border-b border-gray-200 mb-6">
        <nav class="flex gap-8" id="project-tabs">
            <button onclick="switchTab('overview')" id="tab-btn-overview" class="pb-4 text-sm font-bold border-b-2 text-blue-600 border-blue-600 transition-colors focus:outline-none">Overview & Analytics</button>
            <button onclick="switchTab('kanban')" id="tab-btn-kanban" class="pb-4 text-sm font-bold border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors focus:outline-none">Kanban Board</button>
            <button onclick="switchTab('tasks')" id="tab-btn-tasks" class="pb-4 text-sm font-bold border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors focus:outline-none">Task List</button>
            <button onclick="switchTab('files')" id="tab-btn-files" class="pb-4 text-sm font-bold border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors focus:outline-none">Files & Docs</button>
            <button onclick="switchTab('resources')" id="tab-btn-resources" class="pb-4 text-sm font-bold border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors focus:outline-none">Resource Planning</button>
            <button onclick="switchTab('hour')" id="tab-btn-hour" class="pb-4 text-sm font-bold border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors focus:outline-none">Hour Planning</button>
        </nav>
    </div>

    <?php if ($flashSuccess = Session::get('flash_success')): ?>
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
            <?= sanitize($flashSuccess) ?>
        </div>
        <?php Session::remove('flash_success'); ?>
    <?php endif; ?>

    <?php if ($flashError = Session::get('flash_error')): ?>
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
            <?= sanitize($flashError) ?>
        </div>
        <?php Session::remove('flash_error'); ?>
    <?php endif; ?>

    <div id="tab-overview" class="tab-content block space-y-6">
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Project Description</h3>
            <p class="text-gray-600 leading-relaxed"><?= sanitize($project['description']) ?: 'No description provided.' ?></p>
            
            <div class="mt-8">
                <div class="flex justify-between text-sm font-bold mb-2">
                    <span class="text-gray-700">Overall Progress</span>
                    <span class="text-blue-600"><?= $progress ?? 0 ?>%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3">
                    <div class="bg-blue-600 h-3 rounded-full transition-all duration-500" style="width: <?= $progress ?? 0 ?>%"></div>
                </div>
            </div>
        </div>

        <?php 
            // 🚀 SMART PHP ANALYTICS ENGINE: Calculate dynamic data for the charts
            $taskStats = ['todo' => 0, 'in_progress' => 0, 'review' => 0, 'completed' => 0];
            $priorityStats = ['low' => 0, 'medium' => 0, 'high' => 0, 'urgent' => 0];
            
            if (!empty($tasks)) {
                foreach ($tasks as $t) {
                    $status = $t['status'] ?? 'todo';
                    $priority = $t['priority'] ?? 'medium';
                    if(isset($taskStats[$status])) $taskStats[$status]++;
                    if(isset($priorityStats[$priority])) $priorityStats[$priority]++;
                }
            }
        ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-bold text-gray-800 mb-6">Task Status Distribution</h3>
                <div class="relative h-64 w-full flex justify-center">
                    <?php if(empty($tasks)): ?>
                        <div class="absolute inset-0 flex items-center justify-center text-sm font-medium text-gray-400">No task data available</div>
                    <?php endif; ?>
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-bold text-gray-800 mb-6">Task Priority Breakdown</h3>
                <div class="relative h-64 w-full flex justify-center">
                    <?php if(empty($tasks)): ?>
                        <div class="absolute inset-0 flex items-center justify-center text-sm font-medium text-gray-400">No task data available</div>
                    <?php endif; ?>
                    <canvas id="priorityChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <div id="tab-kanban" class="tab-content hidden">
        <div class="flex overflow-x-auto pb-4 gap-6 items-start custom-scrollbar">
            <?php 
                $columns = [
                    'todo' => ['title' => 'TO DO', 'color' => 'bg-gray-100'],
                    'in_progress' => ['title' => 'IN PROGRESS', 'color' => 'bg-blue-50'],
                    'review' => ['title' => 'IN REVIEW', 'color' => 'bg-orange-50'],
                    'completed' => ['title' => 'COMPLETED', 'color' => 'bg-green-50']
                ];

                foreach($columns as $status => $col): 
                    $colTasks = array_filter($tasks ?? [], function($t) use ($status) { return ($t['status'] ?? 'todo') === $status; });
            ?>
            <div class="<?= $col['color'] ?> rounded-xl w-80 flex-shrink-0 p-4 border border-gray-200 shadow-sm flex flex-col max-h-[70vh]">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-black text-sm text-gray-700 tracking-wide"><?= $col['title'] ?></h3>
                    <span class="bg-gray-200 text-gray-700 text-xs font-bold px-2 py-0.5 rounded-full"><?= count($colTasks) ?></span>
                </div>

                <div class="flex-1 overflow-y-auto space-y-3 custom-scrollbar pr-1">
                    <?php if(empty($colTasks)): ?>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center text-gray-400 text-sm font-medium">
                            No tasks
                        </div>
                    <?php else: ?>
                        <?php foreach($colTasks as $task): ?>
                        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer hover:border-blue-400 transition-colors group">
                            <h4 class="font-bold text-gray-900 text-sm mb-1 group-hover:text-blue-600"><?= sanitize($task['title'] ?? 'Untitled Task') ?></h4>
                            <div class="flex justify-between items-center text-xs font-medium text-gray-400 mt-3">
                                <span><?= date('M d', strtotime($task['deadline'] ?? date('Y-m-d'))) ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <button onclick="openTaskModal('<?= $status ?>')" class="mt-3 w-full py-2 text-sm text-gray-500 font-bold hover:text-gray-800 hover:bg-gray-200/50 rounded-lg transition-colors flex items-center justify-center gap-1 focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Add Task
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="tab-tasks" class="tab-content hidden">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">All Tasks</h3>
                <button onclick="openTaskModal('todo')" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold shadow hover:bg-blue-700 transition focus:outline-none">
                    Create Task
                </button>
            </div>
            <?php if(empty($tasks)): ?>
                <div class="p-12 text-center text-gray-500 font-medium border-t border-gray-100">No tasks have been created for this project yet.</div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                                <th class="p-4 font-bold">Task Title</th>
                                <th class="p-4 font-bold">Status</th>
                                <th class="p-4 font-bold">Priority</th>
                                <th class="p-4 font-bold">Deadline</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach($tasks as $task): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="p-4 text-sm font-bold text-gray-900"><?= sanitize($task['title']) ?></td>
                                    <td class="p-4 text-sm">
                                        <span class="px-2.5 py-1 rounded-md text-xs font-bold uppercase <?= $task['status'] == 'completed' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' ?>">
                                            <?= str_replace('_', ' ', sanitize($task['status'])) ?>
                                        </span>
                                    </td>
                                    <td class="p-4 text-sm uppercase text-xs font-bold text-gray-600"><?= sanitize($task['priority'] ?? 'Medium') ?></td>
                                    <td class="p-4 text-sm text-gray-600"><?= date('M d, Y', strtotime($task['deadline'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="tab-resources" class="tab-content hidden space-y-6">
        <form method="POST" action="<?= base_url('/project/save_resource_plan/' . $project['id']) ?>" id="resource-plan-form" class="bg-white rounded-[28px] border border-gray-200 shadow-sm overflow-hidden">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="plan_action" id="resource-plan-action" value="draft">

            <div class="px-8 py-6 border-b border-gray-100 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl border border-gray-200 bg-white flex items-center justify-center shrink-0 text-slate-800">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-[2rem] leading-tight font-black text-slate-900">Resource Planning Form</h3>
                        <p class="text-sm text-slate-400 mt-1">Plan and allocate required manpower by department for your project.</p>
                    </div>
                </div>
                <button type="button" class="px-5 py-3 rounded-xl border border-gray-200 bg-white text-sm font-bold text-gray-600 hover:bg-gray-50 transition self-start">
                    Export PDF
                </button>
            </div>

            <div class="p-8 pb-5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Project Name</label>
                    <select name="project_name_display" class="h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option selected><?= sanitize($project['name']) ?></option>
                    </select>
                </div>
                <div>
    <label class="block text-sm font-bold text-gray-700 mb-2">Project Manager</label>
    <input 
        type="text" 
        name="project_manager_label" 
        value="<?= sanitize($planManagerLabel) ?>" 
        placeholder="Enter Manager Name"
        class="h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-200 transition-shadow"
    >
</div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="<?= !empty($planStartDate) ? date('Y-m-d', strtotime($planStartDate)) : '' ?>" class="h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" value="<?= !empty($planEndDate) ? date('Y-m-d', strtotime($planEndDate)) : '' ?>" class="h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Priority</label>
                    <select name="priority" class="h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="low" <?= $planPriority === 'low' ? 'selected' : '' ?>>Low</option>
                        <option value="medium" <?= $planPriority === 'medium' ? 'selected' : '' ?>>Medium</option>
                        <option value="high" <?= $planPriority === 'high' ? 'selected' : '' ?>>High</option>
                        <option value="urgent" <?= $planPriority === 'urgent' ? 'selected' : '' ?>>Urgent</option>
                    </select>
                </div>
            </div>

            <div class="px-8 pb-8">
                <div class="rounded-[26px] border border-gray-200 overflow-hidden bg-white">
                    <div class="px-6 py-5 bg-[#f7f8ff] border-b border-gray-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-[1.85rem] leading-tight font-black text-slate-900">Resource Requirements <span class="text-indigo-600 text-[1.4rem]">(Department-wise)</span></h4>
                                <p class="text-sm text-slate-400 mt-1">Define how many employees are required from each department.</p>
                            </div>
                        </div>
                        <button type="button" id="add-department-btn" class="px-5 py-3 rounded-xl bg-indigo-50 text-indigo-600 text-sm font-bold border border-indigo-100 hover:bg-indigo-100 transition self-start">
                            + Add Department
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[1180px]">
                            <thead class="bg-gray-50 text-gray-400 text-xs">
                                <tr>
                                    <th class="px-5 py-4 text-left font-bold">Department</th>
                                    <th class="px-3 py-4 text-center font-bold">Hours</th>
                                    <th class="px-3 py-4 text-center font-bold">Required<br>(Employees)</th>
                                    <th class="px-3 py-4 text-center font-bold">Assigned<br>(Employees)</th>
                                    <th class="px-3 py-4 text-center font-bold">Notes</th>
                                    <th class="px-3 py-4 text-center font-bold">Availability</th>
                                    <th class="px-3 py-4 text-center font-bold">Action</th>
                                    <th class="px-3 py-4 text-center font-bold"></th>
                                </tr>
                            </thead>
                            <tbody id="resource-plan-rows" class="divide-y divide-gray-100">
    <?php foreach ($planRows as $index => $row): 
        // 1. Get the assigned hours from the Hour Planning tab for this specific department
        // We match by index assuming both tables follow the same department order
        $assignedHoursByManager = (float)($hourPlanRows[$index]['assigned_hours'] ?? 0);
        
        // 2. Calculate suggested employees (Target Hours / 160 hrs capacity)
        // ceil() ensures we have enough people to cover the fractional hours
        $suggestedStaff = $assignedHoursByManager > 0 ? ceil($assignedHoursByManager / 160) : 0;
        
        $departmentKey = strtolower(trim((string) ($row['department'] ?? '')));
        $meta = $departmentMeta[$departmentKey] ?? ['icon_bg' => 'bg-slate-500', 'emoji' => '•'];
        
        $assignedStaff = (int)($row['assigned_employees'] ?? 0);
        $isAvailable = $assignedStaff >= $suggestedStaff;
    ?>
    <tr class="resource-plan-row" data-dept-hours="<?= $assignedHoursByManager ?>">
        <td class="px-5 py-4">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-full <?= $meta['icon_bg'] ?> text-white flex items-center justify-center text-lg font-black shrink-0"><?= $meta['emoji'] ?></div>
                <input type="text" name="department[]" value="<?= sanitize($row['department']) ?>" class="w-full max-w-[220px] bg-transparent text-[1.05rem] font-black text-slate-900 focus:outline-none" readonly>
            </div>
        </td>
        
        <td class="px-3 py-4 text-center">
            <div class="flex flex-col">
                <span class="text-sm font-black text-blue-600"><?= number_format($assignedHoursByManager, 1) ?></span>
                <span class="text-[10px] text-gray-400 uppercase font-bold">Planned Hrs</span>
            </div>
        </td>

        <td class="px-3 py-4 text-center">
            <input type="number" name="required_employees[]" value="<?= $suggestedStaff ?>" 
                   class="resource-required h-11 w-[76px] rounded-xl border-2 border-blue-50 bg-white text-center text-sm font-bold text-gray-700 focus:border-blue-400 outline-none transition-all">
        </td>

        <td class="px-3 py-4 text-center">
            <input type="number" name="assigned_employees[]" value="<?= $assignedStaff ?>" 
                   class="resource-assigned h-11 w-[76px] rounded-xl border border-gray-200 bg-white text-center text-sm font-semibold text-gray-700 focus:ring-2 focus:ring-indigo-200 outline-none">
        </td>

        <td class="px-3 py-4 text-center">
            <input type="text" name="notes[]" value="<?= sanitize($row['notes'] ?? '') ?>" 
                   class="resource-notes h-11 w-full min-w-[180px] rounded-xl border border-gray-200 bg-white px-4 text-sm text-gray-600 focus:ring-2 focus:ring-indigo-200 outline-none">
        </td>

        <td class="px-3 py-4 text-center">
            <span class="resource-availability inline-flex items-center gap-2 px-3 py-2 rounded-xl border text-sm font-bold <?= $isAvailable ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-red-50 text-red-500 border-red-100' ?>">
                <span class="resource-availability-dot w-2.5 h-2.5 rounded-full <?= $isAvailable ? 'bg-emerald-500' : 'bg-red-500' ?>"></span>
                <span class="resource-availability-text"><?= $isAvailable ? 'Available' : 'Understaffed' ?></span>
            </span>
        </td>

        <td class="px-3 py-4 text-center">
            <button type="button" class="assign-member-btn inline-flex items-center gap-2 rounded-xl border border-indigo-200 px-4 py-2.5 text-sm font-bold text-indigo-600 hover:bg-indigo-50 transition">
                <span class="text-lg leading-none">+</span> Manage
            </button>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
                        </table>
                    </div>

                    <div class="m-4 rounded-2xl bg-[#f7f8ff] border border-indigo-100 p-5">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-500 text-white flex items-center justify-center text-lg font-black shrink-0">◔</div>
                            <div>
                                <h5 class="text-xl font-black text-slate-900">Summary</h5>
                                <p class="text-sm text-slate-400">Overview of resource requirement vs allocation.</p>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                            <div class="bg-white rounded-xl border border-gray-200 px-4 py-4">
                                <p class="text-sm text-gray-400 font-semibold">Total Required</p>
                                <p id="summary-required" class="mt-2 text-4xl font-black text-slate-900">0</p>
                            </div>
                            <div class="bg-white rounded-xl border border-gray-200 px-4 py-4">
                                <p class="text-sm text-gray-400 font-semibold">Total Assigned</p>
                                <p id="summary-assigned" class="mt-2 text-4xl font-black text-slate-900">0</p>
                            </div>
                            <div class="bg-white rounded-xl border border-gray-200 px-4 py-4">
                                <p class="text-sm text-gray-400 font-semibold">Total Gap</p>
                                <p id="summary-gap" class="mt-2 text-4xl font-black text-red-500">0</p>
                            </div>
                            <div class="bg-white rounded-xl border border-gray-200 px-4 py-4">
                                <p class="text-sm text-gray-400 font-semibold">Allocation %</p>
                                <p id="summary-allocation" class="mt-2 text-3xl font-black text-indigo-600">0%</p>
                                <div class="mt-3 h-2.5 rounded-full bg-gray-100 overflow-hidden">
                                    <div id="summary-allocation-bar" class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-blue-500" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 flex flex-wrap justify-end gap-3">
                            <button type="submit" onclick="document.getElementById('resource-plan-action').value='draft'" class="px-5 py-3 rounded-xl border border-gray-200 bg-white text-sm font-bold text-gray-600 hover:bg-gray-50 transition">Save Draft</button>
                            <button type="submit" onclick="document.getElementById('resource-plan-action').value='submit'" class="px-5 py-3 rounded-xl bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-700 transition shadow-sm">Submit Plan</button>
                            <button type="reset" id="resource-plan-cancel" class="px-5 py-3 rounded-xl text-sm font-bold text-gray-500 hover:bg-white transition">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div id="resource-member-modal" class="hidden fixed inset-0 z-[120] overflow-y-auto bg-slate-900/45 backdrop-blur-sm p-4 sm:p-6">
            <div class="flex min-h-full items-center justify-center py-6">
            <div class="w-full max-w-3xl overflow-hidden rounded-[26px] border border-white/70 bg-white/95 shadow-[0_30px_80px_rgba(15,23,42,0.24)] ring-1 ring-slate-200/80 backdrop-blur-xl">
                <div class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-100 bg-white/95 px-6 py-5 backdrop-blur">
                    <div>
                        <h3 id="resource-member-modal-title" class="text-2xl font-black text-slate-900">Add Member</h3>
                    </div>
                    <button type="button" id="close-resource-member-modal" class="text-3xl leading-none text-gray-400 hover:text-gray-600">&times;</button>
                </div>

                <div class="max-h-[70vh] overflow-y-auto overflow-x-auto custom-scrollbar bg-gradient-to-b from-white via-white to-slate-50/80">
                    <table class="w-full">
                        <thead class="sticky top-0 z-[5] bg-gray-50/95 text-sm text-slate-500 backdrop-blur">
                            <tr>
                                <th class="px-5 py-4 text-left font-bold">Employee Name</th>
                                <th class="px-4 py-4 text-center font-bold">Current Status</th>
                                <th class="px-4 py-4 text-center font-bold">Action</th>
                            </tr>
                        </thead>
                        <tbody id="member-modal-body">
    <?php foreach ($availableResources as $resource): ?>
        <?php
            $resourceName = trim(($resource['first_name'] ?? '') . ' ' . ($resource['last_name'] ?? ''));
            $statusLabel = strtolower((string) ($resource['status'] ?? 'active')) === 'active' ? 'Available' : 'Inactive';
            $statusClass = $statusLabel === 'Available' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-red-50 text-red-500 border-red-100';
        ?>
        <tr class="border-t border-gray-100 hover:bg-slate-50/50 transition-colors" data-employee-name="<?= sanitize($resourceName) ?>">
            <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($resourceName) ?>&background=f8fafc&color=334155" class="w-10 h-10 rounded-full border border-gray-200">
                    <span class="text-sm font-bold text-slate-800"><?= sanitize($resourceName) ?></span>
                </div>
            </td>
            <td class="px-2 py-4">
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase">Bill Hrs/Day</label>
                    <input type="number" step="0.5" class="employee-bill-hrs h-9 w-24 rounded-lg border border-gray-200 px-2 text-sm font-bold text-blue-600 outline-none focus:ring-2 focus:ring-blue-100" value="8.0">
                </div>
            </td>
            <td class="px-2 py-4">
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase">Duration (Days)</label>
                    <input type="number" class="employee-days h-9 w-20 rounded-lg border border-gray-200 px-2 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-100" value="1">
                </div>
            </td>
            <td class="px-4 py-4 text-center">
                <div class="flex items-center justify-center gap-2">
                    <button type="button" class="resource-assign-action px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-black hover:bg-indigo-700 transition" 
                            data-member-name="<?= sanitize($resourceName) ?>">
                        Assign
                    </button>
                    <button type="button" class="resource-remove-action px-4 py-2 rounded-xl border border-red-200 text-red-500 text-xs font-black hover:bg-red-50 transition" 
                            data-member-name="<?= sanitize($resourceName) ?>">
                        Remove
                    </button>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </div>


    <div id="tab-hour" class="tab-content hidden">
    <form method="POST" action="<?= base_url('/project/save_hour_plan/' . $project['id']) ?>" id="hour-plan-form" class="bg-white rounded-[28px] border border-gray-200 shadow-sm overflow-hidden">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <input type="hidden" name="plan_action" id="hour-plan-action" value="draft">

        <div class="px-8 py-6 border-b border-gray-100 flex flex-col gap-4">
            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl border border-blue-100 bg-blue-50 flex items-center justify-center shrink-0 text-blue-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-[2rem] leading-tight font-black text-slate-900">Hour Planning</h3>
                        <p class="text-sm text-slate-500 mt-1">Allocate total project hours across departments and monitor distribution variance.</p>
                    </div>
                </div>
                <button type="button" class="px-5 py-3 rounded-xl border border-gray-200 bg-white text-sm font-bold text-gray-600 hover:bg-gray-50 transition self-start">Export Data</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Planning Period</label>
                    <input type="month" name="planning_month" value="<?= sanitize($planningMonthValue) ?>" class="h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-200">
                </div>               
                <div class="xl:col-span-3 flex items-end">
                    <p class="text-sm text-slate-400 font-medium italic">Adjust the "Manager Assigned" column to distribute the total project budget.</p>
                </div>
            </div>
            <div class="px-8 pt-8">
    <div class="bg-slate-900 rounded-[22px] p-6 shadow-xl border border-slate-800 flex items-center justify-between overflow-hidden relative">
        <div class="relative z-10">
            <p class="text-[10px] font-black text-blue-400 uppercase tracking-[0.2em] mb-1">Project Budget</p>
            <h2 class="text-4xl font-black text-white flex items-baseline gap-2">
                <?= number_format($project['total_allocated_hours'] ?? 0, 1) ?>
                <span class="text-lg font-bold text-slate-400 uppercase">Total Hours</span>
            </h2>
        </div>
        
        <div class="text-right relative z-10">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Distribution Status</p>
            <div id="top-status-badge" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 text-sm font-black italic">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                Active Distribution
            </div>
        </div>

        <div class="absolute -right-4 -bottom-6 opacity-10">
            <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>
</div>
        </div>

        <div class="px-8 pb-8">
            <div class="rounded-[24px] border border-gray-200 overflow-hidden bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[800px]">
                        <thead class="bg-gray-50 text-slate-500 text-sm">
                            <tr>
                                <th class="px-6 py-5 text-left font-bold">Department</th>
                                <th class="px-4 py-5 text-center font-bold">Required Baseline<br><span class="text-[10px] uppercase tracking-wider opacity-60">Estimated</span></th>
                                <th class="px-4 py-5 text-center font-bold">Manager Allocation<br><span class="text-[10px] uppercase tracking-wider opacity-60">Actual Assigned</span></th>
                                <th class="px-4 py-5 text-center font-bold">Distribution Variance</th>
                            </tr>
                        </thead>
                        <tbody id="hour-plan-rows" class="divide-y divide-gray-100">
                            <?php foreach ($hourPlanRows as $row): 
                                $departmentKey = strtolower(trim((string) ($row['department'] ?? '')));
                                $meta = $departmentMeta[$departmentKey] ?? ['icon_bg' => 'bg-slate-500', 'emoji' => '•'];
                                $estimatedHours = (float) ($row['estimated_hours'] ?? 0);
                                $assignedHours = (float) ($row['assigned_hours'] ?? 0);
                                $varianceHours = $assignedHours - $estimatedHours;
                            ?>
                                <tr class="hour-plan-row hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="w-11 h-11 rounded-full <?= $meta['icon_bg'] ?> text-white flex items-center justify-center text-lg font-black shrink-0"><?= $meta['emoji'] ?></div>
                                            <input type="text" name="department[]" value="<?= sanitize($row['department']) ?>" class="w-full max-w-[220px] bg-transparent text-[1.05rem] font-black text-slate-900 focus:outline-none" readonly>
                                        </div>
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        <div class="text-lg font-bold text-slate-400"><?= number_format($estimatedHours, 1) ?> <span class="text-xs font-normal">hrs</span></div>
                                        <input type="hidden" name="estimated_hours[]" value="<?= $estimatedHours ?>" class="hour-estimated">
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        <input type="number" step="0.5" min="0" name="assigned_hours[]" value="<?= number_format($assignedHours, 1, '.', '') ?>" class="hour-assigned h-12 w-[110px] rounded-xl border-2 border-blue-50 bg-white text-center text-md font-bold text-blue-700 focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all outline-none">
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        <span class="hour-variance inline-flex items-center justify-center min-w-[90px] px-4 py-2 rounded-xl text-sm font-black <?= $varianceHours < 0 ? 'bg-red-50 text-red-500' : 'bg-emerald-50 text-emerald-600' ?>">
                                            <?= $varianceHours >= 0 ? '+' : '' ?><?= number_format($varianceHours, 1) ?> hrs
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="bg-slate-900 border-t border-gray-200">
                            <tr>
                                <td class="px-6 py-6 text-md font-bold text-white uppercase tracking-widest">Total Plan</td>
                                <td class="px-4 py-6 text-center text-2xl font-black text-slate-300"><span id="hour-total-estimated">0</span> <span class="text-xs font-normal opacity-60">hrs</span></td>
                                <td class="px-4 py-6 text-center text-3xl font-black text-blue-400"><span id="hour-total-assigned">0</span> <span class="text-xs font-normal opacity-60">hrs</span></td>
                                <td class="px-4 py-6 text-center text-xl font-black text-white"><span id="hour-total-variance">0</span></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="m-6 rounded-2xl bg-[#f7f8ff] border border-blue-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"></path></svg>
                            </div>
                            <div>
                                <h5 class="text-xl font-black text-slate-900 tracking-tight">Allocation Summary</h5>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Real-time Validation</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Budgeted Baseline</p>
                            <p id="hour-summary-estimated" class="mt-1 text-3xl font-black text-slate-900">0 hrs</p>
                        </div>
                        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Current Allocation</p>
                            <p id="hour-summary-assigned" class="mt-1 text-3xl font-black text-blue-600">0 hrs</p>
                        </div>
                        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Plan Utilization</p>
                            <div class="flex items-end justify-between">
                                <p id="hour-summary-utilization" class="mt-1 text-3xl font-black text-emerald-600">0%</p>
                                <p id="hour-summary-variance-label" class="text-xs font-bold text-red-500 mb-1">0 hrs remaining</p>
                            </div>
                            <div class="mt-3 h-3 rounded-full bg-slate-100 overflow-hidden">
                                <div id="hour-summary-utilization-bar" class="h-full rounded-full bg-gradient-to-r from-blue-600 to-emerald-500 transition-all duration-500" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-4">
                        <button type="reset" class="px-8 py-4 rounded-xl text-sm font-black text-slate-400 hover:text-slate-600 transition">Discard Changes</button>
                        <button type="submit" onclick="document.getElementById('hour-plan-action').value='draft'" class="px-8 py-4 rounded-xl border-2 border-blue-100 bg-white text-sm font-black text-blue-600 hover:bg-blue-50 transition">Save as Draft</button>
                        <button type="submit" onclick="document.getElementById('hour-plan-action').value='submit'" class="px-10 py-4 rounded-xl bg-blue-600 text-white text-sm font-black hover:bg-blue-700 transition shadow-xl shadow-blue-100">Finalize Plan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

    <div id="tab-files" class="tab-content hidden">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Project Documents</h3>
                <button onclick="openFileModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold shadow hover:bg-blue-700 transition focus:outline-none">
                    Upload File
                </button>
            </div>
            
            <?php if(empty($files)): ?>
                <div class="p-12 text-center flex flex-col items-center justify-center">
                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">No Files Uploaded</h3>
                    <p class="text-sm text-gray-500 mb-6">Upload documents, images, and project assets securely.</p>
                    <button onclick="openFileModal()" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg shadow-sm text-sm font-bold hover:bg-gray-50 transition focus:outline-none">Browse Files</button>
                </div>
            <?php else: ?>
                <ul class="divide-y divide-gray-200">
                    <?php foreach($files as $file): ?>
                        <?php 
                            $ext = strtolower(pathinfo($file['original_name'], PATHINFO_EXTENSION));
                            $iconColor = 'text-blue-500'; 
                            if(in_array($ext, ['pdf'])) $iconColor = 'text-red-500'; 
                            if(in_array($ext, ['png', 'jpg', 'jpeg', 'gif'])) $iconColor = 'text-green-500'; 
                        ?>
                        <li class="p-4 hover:bg-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center <?= $iconColor ?>">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 truncate max-w-xs md:max-w-md" title="<?= sanitize($file['original_name']) ?>">
                                        <?= sanitize($file['original_name']) ?>
                                    </p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Uploaded by <span class="font-semibold"><?= sanitize($file['first_name'] . ' ' . $file['last_name']) ?></span> on <?= date('M d, Y', strtotime($file['created_at'])) ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-2 shrink-0">
                                <a href="<?= base_url('/uploads/projects/' . $file['saved_name']) ?>" target="_blank" class="px-3 py-1.5 text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors border border-blue-100 shadow-sm">
                                    View / Download
                                </a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

</div>

<div id="task-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 z-[100] flex items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-black text-gray-800">Create New Task</h3>
            <button onclick="closeTaskModal()" class="text-gray-400 hover:text-red-500 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="<?= base_url('/task/create') ?>" method="POST" class="p-6">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Task Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                    <select name="status" id="task-status-input" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="todo">To Do</option>
                        <option value="in_progress">In Progress</option>
                        <option value="review">In Review</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Priority</label>
                    <select name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Deadline</label>
                    <input type="date" name="deadline" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-gray-700">
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="closeTaskModal()" class="px-4 py-2 text-sm font-bold text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Cancel</button>
                <button type="submit" class="px-5 py-2 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow shadow-blue-500/30 transition">Save Task</button>
            </div>
        </form>
    </div>
</div>

<div id="file-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 z-[100] flex items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-black text-gray-800">Upload Project File</h3>
            <button onclick="closeFileModal()" class="text-gray-400 hover:text-red-500 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="<?= base_url('/project/upload') ?>" method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Select File</label>
                    <input type="file" name="project_file" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="closeFileModal()" class="px-4 py-2 text-sm font-bold text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Cancel</button>
                <button type="submit" class="px-5 py-2 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow transition">Upload securely</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Tab Switching Logic
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.remove('block');
            el.classList.add('hidden');
        });
        document.querySelectorAll('#project-tabs button').forEach(el => {
            el.classList.remove('text-blue-600', 'border-blue-600');
            el.classList.add('text-gray-500', 'border-transparent');
        });
        document.getElementById('tab-' + tabId).classList.remove('hidden');
        document.getElementById('tab-' + tabId).classList.add('block');
        const activeBtn = document.getElementById('tab-btn-' + tabId);
        activeBtn.classList.remove('text-gray-500', 'border-transparent');
        activeBtn.classList.add('text-blue-600', 'border-blue-600');
    }

    // Modal Logic
    function openTaskModal(status) {
        document.getElementById('task-status-input').value = status;
        document.getElementById('task-modal').classList.remove('hidden');
    }
    function closeTaskModal() { document.getElementById('task-modal').classList.add('hidden'); }
    function openFileModal() { document.getElementById('file-modal').classList.remove('hidden'); }
    function closeFileModal() { document.getElementById('file-modal').classList.add('hidden'); }

    // ==========================================
    // 🚀 INITIALIZE CHARTS (Only if tasks exist)
    // ==========================================
    <?php if(!empty($tasks)): ?>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- 1. DONUT CHART (Task Status) ---
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['To Do', 'In Progress', 'In Review', 'Completed'],
                datasets: [{
                    data: [
                        <?= $taskStats['todo'] ?>, 
                        <?= $taskStats['in_progress'] ?>, 
                        <?= $taskStats['review'] ?>, 
                        <?= $taskStats['completed'] ?>
                    ],
                    backgroundColor: [
                        '#e5e7eb', // Gray for To Do
                        '#3b82f6', // Blue for In Progress
                        '#f97316', // Orange for Review
                        '#22c55e'  // Green for Completed
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%', // Makes it a beautiful thin donut
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, usePointStyle: true, boxWidth: 8, font: { family: 'Inter, sans-serif', size: 12 } }
                    }
                }
            }
        });

        // --- 2. BAR CHART (Task Priority Breakdown) ---
        const priorityCtx = document.getElementById('priorityChart').getContext('2d');
        new Chart(priorityCtx, {
            type: 'bar',
            data: {
                labels: ['Low', 'Medium', 'High', 'Urgent'],
                datasets: [{
                    label: 'Tasks by Priority',
                    data: [
                        <?= $priorityStats['low'] ?>, 
                        <?= $priorityStats['medium'] ?>, 
                        <?= $priorityStats['high'] ?>, 
                        <?= $priorityStats['urgent'] ?>
                    ],
                    backgroundColor: [
                        '#9ca3af', // Gray for Low
                        '#3b82f6', // Blue for Medium
                        '#f59e0b', // Yellow for High
                        '#ef4444'  // Red for Urgent
                    ],
                    borderRadius: 4, // Rounded tops on the bars
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false } // Hide legend for cleaner look
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, font: { family: 'Inter, sans-serif' } },
                        grid: { color: '#f3f4f6', drawBorder: false } // Clean subtle grid lines
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { font: { family: 'Inter, sans-serif', weight: 'bold' } }
                    }
                }
            }
        });
    });
    <?php endif; ?>
</script>
<script>
    (function () {
        const initialProjectTab = '<?= sanitize($active_tab ?? 'overview') ?>';

        function safeSwitchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.remove('block');
                el.classList.add('hidden');
            });
            document.querySelectorAll('#project-tabs button').forEach(el => {
                el.classList.remove('text-blue-600', 'border-blue-600');
                el.classList.add('text-gray-500', 'border-transparent');
            });

            const tab = document.getElementById('tab-' + tabId);
            const btn = document.getElementById('tab-btn-' + tabId);
            if (tab) {
                tab.classList.remove('hidden');
                tab.classList.add('block');
            }
            if (btn) {
                btn.classList.remove('text-gray-500', 'border-transparent');
                btn.classList.add('text-blue-600', 'border-blue-600');
            }
        }

        function updateResourceRow(row) {
            const requiredInput = row.querySelector('.resource-required');
            const assignedInput = row.querySelector('.resource-assigned');
            const gapEl = row.querySelector('.resource-gap');
            const availability = row.querySelector('.resource-availability');
            const availabilityText = row.querySelector('.resource-availability-text');
            const availabilityDot = row.querySelector('.resource-availability-dot');
            if (!requiredInput || !assignedInput || !gapEl || !availability || !availabilityText || !availabilityDot) return;

            const required = Math.max(0, parseInt(requiredInput.value || '0', 10));
            const assigned = Math.max(0, parseInt(assignedInput.value || '0', 10));
            const gap = Math.max(0, required - assigned);

            

            gapEl.textContent = gap;
            gapEl.classList.toggle('text-red-500', gap > 0);
            gapEl.classList.toggle('text-emerald-500', gap === 0);
            availability.classList.toggle('bg-red-50', gap > 0);
            availability.classList.toggle('text-red-500', gap > 0);
            availability.classList.toggle('border-red-100', gap > 0);
            availability.classList.toggle('bg-emerald-50', gap === 0);
            availability.classList.toggle('text-emerald-600', gap === 0);
            availability.classList.toggle('border-emerald-100', gap === 0);
            availabilityDot.classList.toggle('bg-red-500', gap > 0);
            availabilityDot.classList.toggle('bg-emerald-500', gap === 0);
            availabilityText.textContent = gap > 0 ? 'Overloaded' : 'Available';
        }

        function updateResourceSummary() {
            const rows = document.querySelectorAll('#resource-plan-rows .resource-plan-row');
            let totalRequired = 0;
            let totalAssigned = 0;

            rows.forEach(row => {
                updateResourceRow(row);
                totalRequired += Math.max(0, parseInt(row.querySelector('.resource-required')?.value || '0', 10));
                totalAssigned += Math.max(0, parseInt(row.querySelector('.resource-assigned')?.value || '0', 10));
            });

            const totalGap = Math.max(0, totalRequired - totalAssigned);
            const allocation = totalRequired > 0 ? ((totalAssigned / totalRequired) * 100) : 0;

            const requiredEl = document.getElementById('summary-required');
            const assignedEl = document.getElementById('summary-assigned');
            const gapEl = document.getElementById('summary-gap');
            const allocationEl = document.getElementById('summary-allocation');
            const allocationBar = document.getElementById('summary-allocation-bar');

            if (requiredEl) requiredEl.textContent = totalRequired;
            if (assignedEl) assignedEl.textContent = totalAssigned;
            if (gapEl) gapEl.textContent = totalGap;
            if (allocationEl) allocationEl.textContent = `${allocation.toFixed(1)}%`;
            if (allocationBar) allocationBar.style.width = `${Math.min(100, allocation)}%`;
        }

        let activeResourceRow = null;

        function openResourceMemberModal(row) {
            activeResourceRow = row;
            const department = row.querySelector('input[name="department[]"]')?.value || 'Department';
            const title = document.getElementById('resource-member-modal-title');
            if (title) {
                title.textContent = `Add Member to ${department} Department`;
            }
            document.getElementById('resource-member-modal')?.classList.remove('hidden');
            syncModalActionButtons();
        }

        function closeResourceMemberModal() {
            activeResourceRow = null;
            document.getElementById('resource-member-modal')?.classList.add('hidden');
        }

        function getRowMembers(row) {
            if (!row) return [];

            return (row.querySelector('.resource-member-list')?.value || row.querySelector('.resource-notes')?.value || '')
                .split(',')
                .map(name => name.trim())
                .filter(Boolean);
        }

        function setRowMembers(row, memberNames) {
            if (!row) return;

            const uniqueNames = [...new Set(memberNames.filter(Boolean))];
            const joinedNames = uniqueNames.join(', ');
            const notesInput = row.querySelector('.resource-notes');
            const memberListInput = row.querySelector('.resource-member-list');

            if (notesInput) notesInput.value = joinedNames;
            if (memberListInput) memberListInput.value = joinedNames;
        }
        // Function to calculate currently allocated hours for the active department in the modal
function getCurrentlyAllocatedHours() {
    if (!activeResourceRow) return 0;
    const notesValue = activeResourceRow.querySelector('.resource-notes').value;
    if (!notesValue) return 0;

    // Regex to find all "Xhrs/Ydays" patterns and sum them up
    const pattern = /(\d+(?:\.\d+)?)\s*hrs\/\s*(\d+)\s*days/g;
    let total = 0;
    let match;
    while ((match = pattern.exec(notesValue)) !== null) {
        total += parseFloat(match[1]) * parseInt(match[2]);
    }
    return total;
}

        function syncModalActionButtons() {
    if (!activeResourceRow) return;
    
    // Get the limit from the data-dept-hours attribute set in the PHP loop
    const plannedLimit = parseFloat(activeResourceRow.dataset.deptHours || 0);
    const currentAllocated = getCurrentlyAllocatedHours();
    const notesValue = activeResourceRow.querySelector('.resource-notes').value;

    document.querySelectorAll('#member-modal-body tr').forEach(row => {
        const name = row.dataset.employeeName;
        const assignBtn = row.querySelector('.resource-assign-action');
        const removeBtn = row.querySelector('.resource-remove-action');
        
        // Calculate what this specific assignment would add
        const inputHrs = parseFloat(row.querySelector('.employee-bill-hrs').value || 0);
        const inputDays = parseInt(row.querySelector('.employee-days').value || 0);
        const prospectiveHours = inputHrs * inputDays;

        const isAssigned = notesValue.includes(name);

        if (isAssigned) {
            assignBtn.disabled = true;
            assignBtn.innerText = 'Assigned';
            assignBtn.className = "resource-assign-action px-4 py-2 rounded-xl bg-gray-100 text-gray-400 text-xs font-black cursor-not-allowed";
            removeBtn.classList.remove('hidden');
        } else {
            // VALIDATION: Disable if prospective hours + current allocated > planned limit
            const wouldExceed = (currentAllocated + prospectiveHours) > plannedLimit;
            
            assignBtn.disabled = wouldExceed;
            removeBtn.classList.add('hidden');

            if (wouldExceed) {
                assignBtn.innerText = 'Limit Exceeded';
                assignBtn.className = "resource-assign-action px-4 py-2 rounded-xl bg-red-50 text-red-400 text-[10px] font-black cursor-not-allowed border border-red-100";
            } else {
                assignBtn.innerText = 'Assign';
                assignBtn.className = "resource-assign-action px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-black hover:bg-indigo-700 transition shadow-md shadow-indigo-100";
            }
        }
    });
}

        function assignMemberToRow(memberName) {
    if (!activeResourceRow) return;

    // Find the row in the modal to get the specific inputs
    const modalRow = document.querySelector(`tr[data-employee-name="${memberName}"]`);
    const billHrs = modalRow.querySelector('.employee-bill-hrs').value || '0';
    const days = modalRow.querySelector('.employee-days').value || '0';

    const assignedInput = activeResourceRow.querySelector('.resource-assigned');
    const notesInput = activeResourceRow.querySelector('.resource-notes');

    // Create a structured string for the notes field
    // Format: "John Doe (8hrs/5days)"
    const entry = `${memberName} (${billHrs}hrs/${days}days)`;

    let currentEntries = notesInput.value ? notesInput.value.split(',').map(s => s.trim()) : [];
    
    // Check if employee is already assigned to avoid duplicates
    const isAlreadyAssigned = currentEntries.some(e => e.startsWith(memberName));

    if (!isAlreadyAssigned) {
        currentEntries.push(entry);
        notesInput.value = currentEntries.join(', ');
        
        // Increment assigned count
        if (assignedInput) {
            assignedInput.value = parseInt(assignedInput.value || '0') + 1;
        }
    }

    updateResourceSummary();
    syncModalActionButtons();
}

        function removeMemberFromRow(memberName) {
    if (!activeResourceRow) return;

    const assignedInput = activeResourceRow.querySelector('.resource-assigned');
    const notesInput = activeResourceRow.querySelector('.resource-notes');

    let currentEntries = notesInput.value ? notesInput.value.split(',').map(s => s.trim()) : [];
    
    // Filter out the entry that starts with the member's name
    const nextEntries = currentEntries.filter(e => !e.startsWith(memberName));

    if (nextEntries.length !== currentEntries.length) {
        notesInput.value = nextEntries.join(', ');
        if (assignedInput) {
            assignedInput.value = Math.max(0, parseInt(assignedInput.value || '0') - 1);
        }
    }

    updateResourceSummary();
    syncModalActionButtons();
}

        function styleRemoveDepartmentButton(button) {
            if (!button) return;

            button.className = 'remove-department-btn inline-flex items-center gap-2 rounded-xl px-3 py-2.5 text-sm font-bold text-red-500 hover:bg-red-50 transition';
            button.innerHTML = '<span class="text-base leading-none">×</span><span>Remove</span>';
            button.textContent = 'Remove';
            button.setAttribute('aria-label', 'Remove department');
        }

        function attachRowEvents(row) {
            row.querySelector('.resource-required')?.addEventListener('input', updateResourceSummary);
            row.querySelector('.resource-assigned')?.addEventListener('input', updateResourceSummary);
            row.querySelector('.assign-member-btn')?.addEventListener('click', () => openResourceMemberModal(row));
            const removeButton = row.querySelector('.remove-department-btn');
            styleRemoveDepartmentButton(removeButton);
            removeButton?.addEventListener('click', () => {
                const rows = document.querySelectorAll('#resource-plan-rows .resource-plan-row');
                if (rows.length > 1) {
                    row.remove();
                    updateResourceSummary();
                }
            });
        }

        function createDepartmentRow() {
            const tbody = document.getElementById('resource-plan-rows');
            if (!tbody) return;

            const row = document.createElement('tr');
            row.className = 'resource-plan-row';
            row.innerHTML = `
                <td class="px-5 py-4">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-full bg-slate-500 text-white flex items-center justify-center text-lg font-black shrink-0">•</div>
                        <input type="text" name="department[]" value="New Department" class="w-full max-w-[220px] bg-transparent text-[1.05rem] font-black text-slate-900 focus:outline-none">
                    </div>
                </td>
                <td class="px-3 py-4 text-center">
                    <input type="number" min="0" name="required_employees[]" value="1" class="resource-required h-11 w-[76px] rounded-xl border border-gray-200 bg-white text-center text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </td>
                <td class="px-3 py-4 text-center">
                    <input type="number" min="0" name="assigned_employees[]" value="0" class="resource-assigned h-11 w-[76px] rounded-xl border border-gray-200 bg-white text-center text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </td>
                <td class="px-3 py-4 text-center">
                    <input type="text" name="notes[]" value="" class="resource-notes h-11 w-full min-w-[180px] rounded-xl border border-gray-200 bg-white px-4 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </td>
                <td class="px-3 py-4 text-center">
                    <span class="resource-availability inline-flex items-center gap-2 px-3 py-2 rounded-xl border text-sm font-bold bg-red-50 text-red-500 border-red-100">
                        <span class="resource-availability-dot w-2.5 h-2.5 rounded-full bg-red-500"></span>
                        <span class="resource-availability-text">Overloaded</span>
                    </span>
                </td>
                <td class="px-3 py-4 text-center">
                    <span class="resource-gap text-3xl font-black text-red-500">1</span>
                </td>
                <td class="px-3 py-4 text-center">
                    <button type="button" class="assign-member-btn inline-flex items-center gap-2 rounded-xl border border-indigo-200 px-4 py-2.5 text-sm font-bold text-indigo-600 hover:bg-indigo-50 transition" data-department="New Department">
                        <span class="text-lg leading-none">+</span>
                        Assign Member
                    </button>
                    <input type="hidden" class="resource-member-list" value="">
                </td>
                <td class="px-3 py-4 text-center">
                    <button type="button" class="remove-department-btn text-gray-400 hover:text-red-500 text-xl leading-none">⋮</button>
                </td>
            `;

            tbody.appendChild(row);
            attachRowEvents(row);
            updateResourceSummary();
        }

        window.switchTab = safeSwitchTab;

        document.addEventListener('DOMContentLoaded', function () {
            safeSwitchTab(initialProjectTab);
            document.querySelectorAll('#resource-plan-rows .resource-plan-row').forEach(attachRowEvents);
            updateResourceSummary();
            document.getElementById('add-department-btn')?.addEventListener('click', createDepartmentRow);
            document.getElementById('resource-plan-cancel')?.addEventListener('click', () => setTimeout(updateResourceSummary, 0));
            document.getElementById('close-resource-member-modal')?.addEventListener('click', closeResourceMemberModal);
            document.getElementById('resource-member-modal')?.addEventListener('click', event => {
                if (event.target.id === 'resource-member-modal') {
                    closeResourceMemberModal();
                }
            });
            document.querySelectorAll('.resource-assign-action').forEach(button => {
                button.addEventListener('click', () => assignMemberToRow(button.dataset.memberName || ''));
            });
            document.querySelectorAll('.resource-remove-action').forEach(button => {
                button.addEventListener('click', () => removeMemberFromRow(button.dataset.memberName || ''));
            });
        });
    })();
</script>
<script>
    (function () {
        function updateHourSummary() {
            const rows = document.querySelectorAll('#hour-plan-rows .hour-plan-row');
            let totalEstimated = 0;
            let totalAssigned = 0;
            let totalWeek1 = 0;
            let totalWeek2 = 0;

            rows.forEach(row => {
                const estimated = Math.max(0, parseFloat(row.querySelector('.hour-estimated')?.value || '0'));
                const assigned = Math.max(0, parseFloat(row.querySelector('.hour-assigned')?.value || '0'));
                const week1 = Math.max(0, parseFloat(row.querySelector('.hour-week1')?.value || '0'));
                const week2 = Math.max(0, parseFloat(row.querySelector('.hour-week2')?.value || '0'));
                const variance = assigned - estimated;
                const total = week1 + week2;

                totalEstimated += estimated;
                totalAssigned += assigned;
                totalWeek1 += week1;
                totalWeek2 += week2;

                const varianceEl = row.querySelector('.hour-variance');
                const totalEl = row.querySelector('.hour-total');
                if (varianceEl) {
                    varianceEl.textContent = `${variance >= 0 ? '+' : ''}${variance.toFixed(1)} hrs`;
                    varianceEl.classList.toggle('bg-red-50', variance < 0);
                    varianceEl.classList.toggle('text-red-500', variance < 0);
                    varianceEl.classList.toggle('bg-emerald-50', variance >= 0);
                    varianceEl.classList.toggle('text-emerald-600', variance >= 0);
                }
                if (totalEl) totalEl.textContent = `${total.toFixed(1)} hrs`;
            });

            const totalVariance = totalAssigned - totalEstimated;
            const totalHours = totalWeek1 + totalWeek2;
            const utilization = totalEstimated > 0 ? (totalAssigned / totalEstimated) * 100 : 0;

            const setText = (id, value) => {
                const el = document.getElementById(id);
                if (el) el.textContent = value;
            };
            

            setText('hour-total-estimated', totalEstimated.toFixed(1));
            setText('hour-total-assigned', totalAssigned.toFixed(1));
            setText('hour-total-week1', totalWeek1.toFixed(1));
            setText('hour-total-week2', totalWeek2.toFixed(1));
            setText('hour-total-variance', `${totalVariance >= 0 ? '+' : ''}${totalVariance.toFixed(1)} hrs`);
            setText('hour-total-hours', totalHours.toFixed(1));
            setText('hour-summary-estimated', `${totalEstimated.toFixed(1)} hrs`);
            setText('hour-summary-assigned', `${totalAssigned.toFixed(1)} hrs`);
            setText('hour-summary-variance', `${totalVariance >= 0 ? '+' : ''}${totalVariance.toFixed(1)} hrs / ${utilization && totalEstimated ? ((totalVariance / totalEstimated) * 100).toFixed(1) : '0.0'}%`);
            setText('hour-summary-utilization', `${utilization.toFixed(1)}%`);

            const utilizationBar = document.getElementById('hour-summary-utilization-bar');
            if (utilizationBar) utilizationBar.style.width = `${Math.min(100, utilization)}%`;

            
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('#hour-plan-rows .hour-plan-row input').forEach(input => {
                input.addEventListener('input', updateHourSummary);
            });
            updateHourSummary();

            document.getElementById('hour-plan-cancel')?.addEventListener('click', function () {
                setTimeout(updateHourSummary, 0);
            });
            // Add event listeners to inputs so the "Assign" button updates in real-time as user types
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('employee-bill-hrs') || e.target.classList.contains('employee-days')) {
        syncModalActionButtons();
    }
});
        });
    })();
</script>

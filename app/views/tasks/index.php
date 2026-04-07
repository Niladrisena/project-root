<div class="max-w-7xl mx-auto pb-12">
    
    <div class="flex flex-col lg:flex-row justify-between lg:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">My Task Center</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your priorities, track time, and update statuses in real-time.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" id="taskSearch" placeholder="Search tasks..." class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm w-full md:w-64 shadow-sm">
            </div>
            
            <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none shadow-sm bg-white">
                <option value="all">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="review">In Review</option>
                <option value="completed">Completed</option>
            </select>

            <select id="priorityFilter" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none shadow-sm bg-white">
                <option value="all">All Priorities</option>
                <option value="high">High Priority</option>
                <option value="medium">Medium Priority</option>
                <option value="low">Low Priority</option>
            </select>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        <div class="grid grid-cols-12 gap-4 p-4 border-b border-gray-100 bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider">
            <div class="col-span-5">Task Details</div>
            <div class="col-span-2 text-center">Priority</div>
            <div class="col-span-2 text-center">Deadline</div>
            <div class="col-span-2 text-center">Status</div>
            <div class="col-span-1 text-right">Timer</div>
        </div>

        <div id="taskContainer" class="divide-y divide-gray-100">
            <?php if(empty($tasks)): ?>
                <div class="p-12 text-center text-gray-500">No tasks assigned to you right now.</div>
            <?php else: ?>
                <?php foreach($tasks as $t): ?>
                    <div class="task-row grid grid-cols-12 gap-4 p-4 items-center hover:bg-gray-50 transition" 
                         data-title="<?= strtolower(sanitize($t['title'])) ?>" 
                         data-status="<?= sanitize($t['status']) ?>" 
                         data-priority="<?= sanitize($t['priority']) ?>">
                        
                        <div class="col-span-5 flex items-start gap-3">
                            <div class="mt-1">
                                <?php if($t['status'] == 'completed'): ?>
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <?php else: ?>
                                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"></circle></svg>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900"><?= sanitize($t['title']) ?></h4>
                                <div class="flex items-center gap-3 mt-1 text-xs text-gray-500 font-medium">
                                    <span class="flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg> <?= sanitize($t['project_name'] ?? 'General') ?></span>
                                    <?php if($t['total_subtasks'] > 0): ?>
                                        <span class="flex items-center gap-1 text-blue-600"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg> <?= $t['completed_subtasks'] ?>/<?= $t['total_subtasks'] ?> Subtasks</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-2 text-center">
                            <?php $p_color = ($t['priority'] == 'high') ? 'red' : (($t['priority'] == 'medium') ? 'yellow' : 'gray'); ?>
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-<?= $p_color ?>-100 text-<?= $p_color ?>-800 uppercase">
                                <?= sanitize($t['priority']) ?>
                            </span>
                        </div>

                        <div class="col-span-2 text-center text-sm font-medium text-gray-600">
                            <?= date('M d, Y', strtotime($t['deadline'])) ?>
                        </div>

                        <div class="col-span-2 text-center">
                            <select onchange="updateTaskStatus(<?= $t['id'] ?>, this.value)" class="px-3 py-1 text-xs font-bold border border-gray-300 rounded bg-white shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer">
                                <option value="pending" <?= $t['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="in_progress" <?= $t['status'] == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                <option value="review" <?= $t['status'] == 'review' ? 'selected' : '' ?>>In Review</option>
                                <option value="completed" <?= $t['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                            </select>
                        </div>

                        <div class="col-span-1 text-right">
                            <button onclick="toggleTimer(<?= $t['id'] ?>, this)" class="w-8 h-8 rounded-full flex items-center justify-center transition shadow-sm <?= $t['active_timer_id'] ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-green-100 text-green-600 hover:bg-green-200' ?>" title="<?= $t['active_timer_id'] ? 'Stop Timer' : 'Start Timer' ?>">
                                <?php if($t['active_timer_id']): ?>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd"></path></svg>
                                <?php else: ?>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                                <?php endif; ?>
                            </button>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // 1. Live Search & Filtering
    const searchInput = document.getElementById('taskSearch');
    const statusFilter = document.getElementById('statusFilter');
    const priorityFilter = document.getElementById('priorityFilter');
    const tasks = document.querySelectorAll('.task-row');

    function filterTasks() {
        const query = searchInput.value.toLowerCase();
        const status = statusFilter.value;
        const priority = priorityFilter.value;

        tasks.forEach(task => {
            const tTitle = task.getAttribute('data-title');
            const tStatus = task.getAttribute('data-status');
            const tPriority = task.getAttribute('data-priority');

            const matchesSearch = tTitle.includes(query);
            const matchesStatus = (status === 'all' || tStatus === status);
            const matchesPriority = (priority === 'all' || tPriority === priority);

            if (matchesSearch && matchesStatus && matchesPriority) {
                task.style.display = '';
            } else {
                task.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('keyup', filterTasks);
    statusFilter.addEventListener('change', filterTasks);
    priorityFilter.addEventListener('change', filterTasks);

    // 2. AJAX Status Update
    function updateTaskStatus(taskId, newStatus) {
        const formData = new FormData();
        formData.append('task_id', taskId);
        formData.append('status', newStatus);

        fetch('<?= base_url('task/ajax_update_status') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Update the data attribute for the live filter to keep working!
                const row = document.querySelector(`.task-row[data-title][onchange*="${taskId}"]`).closest('.task-row');
                if(row) row.setAttribute('data-status', newStatus);
            }
        });
    }

    // 3. AJAX Toggle Timer
    function toggleTimer(taskId, btnElement) {
        const formData = new FormData();
        formData.append('task_id', taskId);

        fetch('<?= base_url('task/ajax_toggle_timer') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                if(data.data.status === 'started') {
                    // Turn to Stop Button
                    btnElement.classList.replace('bg-green-100', 'bg-red-100');
                    btnElement.classList.replace('text-green-600', 'text-red-600');
                    btnElement.classList.replace('hover:bg-green-200', 'hover:bg-red-200');
                    btnElement.title = 'Stop Timer';
                    btnElement.innerHTML = `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd"></path></svg>`;
                } else {
                    // Turn to Play Button
                    btnElement.classList.replace('bg-red-100', 'bg-green-100');
                    btnElement.classList.replace('text-red-600', 'text-green-600');
                    btnElement.classList.replace('hover:bg-red-200', 'hover:bg-green-200');
                    btnElement.title = 'Start Timer';
                    btnElement.innerHTML = `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>`;
                    alert(`Timer stopped! Time logged: ${data.data.minutes} minutes.`);
                }
            }
        });
    }
</script>
<?php 
    // Fetch the logged-in user securely at the top of the layout 
    $user = Auth::user(); 

    // ==========================================
    // ELITE UI ROUTER: Dynamic Navigation Engine
    // Determines the correct Dashboard URL based on Role ID
    // ==========================================
    $nav_role_id = (int) Session::get('role_id'); 
    $dashboard_url = base_url('/dashboard/employee'); // Safe Default
    
    switch ($nav_role_id) {
        case 1:
            $dashboard_url = base_url('/dashboard'); // System Owner
            break;
        case 3:
            $dashboard_url = base_url('/hr/dashboard'); // HR Manager
            break;
        case 4:
            $dashboard_url = base_url('/pm/dashboard'); // Project Manager
            break;
        case 5:
            $dashboard_url = base_url('/it/dashboard'); // IT Manager
            break;
        case 6:
            $dashboard_url = base_url('/finance/dashboard'); // Finance
            break;
        case 7:
        $dashboard_url = base_url('/bd/dashboard'); // Business Development (NEW)
        break;
    }

    $current_route = trim($_GET['url'] ?? '', '/');
    $current_module = strtolower(explode('/', $current_route)[0] ?? '');
    $is_pm_panel = ($current_module === 'pm');
    $projects_url = (Auth::role('owner') || Auth::role('admin'))
        ? base_url('/adminpanel/projectManagement')
        : base_url('/project');

    // 🚀 FAILSAFE: Guarantee Company Name exists even if DB goes offline
    $company_branding = defined('SYS_COMPANY_NAME') ? SYS_COMPANY_NAME : 'Enterprise ERP';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> | <?= $company_branding ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: { colors: { brand: '#2563eb' } }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased flex h-screen overflow-hidden">

    <aside class="w-64 bg-[#0f172a] text-slate-300 flex-shrink-0 flex flex-col transition-all duration-300 z-20 shadow-xl">
        <div class="h-16 flex items-center px-6 border-b border-slate-800">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold">
                    <?= strtoupper(substr($company_branding, 0, 1)) ?>
                </div>
                <span class="font-bold truncate" title="<?= sanitize($company_branding) ?>"><?= sanitize($company_branding) ?></span>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 custom-scrollbar">
            
            <a href="<?= $dashboard_url ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>

            <?php if (Auth::can('view', 'projects')): ?>
            <a href="<?= $projects_url ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                Projects Management
            </a>
            <?php endif; ?>

            <?php if ($nav_role_id === 4 && $is_pm_panel): ?>
            <a href="<?= base_url('/pm/resource_management') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Resource Management
            </a>

            <a href="<?= base_url('/pm/hour_utilization_report') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6m4 6V7m4 10v-4M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Hour Utilization Report
            </a>

            <a href="<?= base_url('/pm/revenue_management') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Revenue Management
            </a>

            <a href="<?= base_url('/pm/overall_backlog') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8M8 11h8M8 15h5M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"></path></svg>
                Overall Backlog
            </a>
            <?php endif; ?>

            <?php if (Auth::can('view', 'hr')): ?>
            <a href="<?= base_url('/employee') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                HR & Employees
            </a>
            <?php endif; ?>

            <?php if (Auth::can('view_financials', 'accounts')): ?>
            <a href="<?= base_url('/finance') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Finance & Accounts
            </a>
            <?php endif; ?>
            
            
            <?php if (Auth::can('view', 'business_development')): ?>
                <a href="<?= base_url('/bd/dashboard') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-colors">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    BD Strategy & Leads
                </a>
                <?php endif; ?>
                <div class="pt-4 mt-4 border-t border-slate-800"></div>
            <?php if (Auth::role('owner') || Auth::role('admin')): ?>
            <a href="<?= base_url('/adminpanel/resourceManagement') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Resource Management
            </a>

            <a href="<?= base_url('/adminpanel/roleManagement') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <!-- Shield -->
  <path d="M12 2l7 4v6c0 5-3.5 9-7 10-3.5-1-7-5-7-10V6l7-4z"/>
  
  <!-- User -->
  <circle cx="12" cy="10" r="3"/>
  <path d="M8 16c1.5-2 6.5-2 8 0"/>
</svg>
                Role Management
            </a>

            <a href="<?= base_url('/adminpanel/revenueManagement') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Revenue Management
            </a>

            <a href="<?= base_url('/adminpanel/analyticsReporting') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <!-- Box -->
  <rect x="3" y="3" width="18" height="18" rx="2"/>

  <!-- Graph -->
  <polyline points="6 15 10 11 13 13 18 8"/>
</svg>
                Analytics & reporting
            </a>

            <a href="<?= base_url('/adminpanel/riskManagement') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <!-- Shield -->
  <path d="M12 2l7 4v6c0 5-3.5 9-7 10-3.5-1-7-5-7-10V6l7-4z"/>
  
  <!-- Check -->
  <polyline points="9 12 11 14 15 10"/>
</svg>
                Risk Management
            </a>

            <?php endif; ?>

            <?php if (Auth::role('owner')): ?>
            <a href="<?= base_url('/settings') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                System Settings
            </a>
            <?php endif; ?>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50">
        
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-10 shadow-sm">
            <div class="flex-1 max-w-lg flex items-center">
                <div class="relative w-full">
                    <form action="<?= base_url('/search/index') ?>" method="GET" class="relative w-full max-w-md" id="global-search-form">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="q" id="global-search-input" value="<?= isset($query) ? sanitize($query) : '' ?>" class="block w-full p-2 pl-10 pr-24 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none" placeholder="Search your workspace, tasks, leave, assets... (Ctrl+/)" autocomplete="off" required>
                        <button type="submit" class="absolute inset-y-1 right-1 px-3 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-md transition-colors">Search</button>
                    </form>
                </div>
            </div>

            <div class="flex items-center gap-4">
                
                <div class="relative inline-block text-left" id="notification-component">
                    <button onclick="toggleNotifications()" class="relative p-2 text-gray-400 hover:text-blue-600 hover:bg-gray-100 rounded-full transition-colors focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span id="notification-badge" class="hidden absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full shadow-sm border-2 border-white">0</span>
                    </button>

                    <div id="notification-dropdown" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden transform origin-top-right transition-all">
                        <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                            <h3 class="font-black text-gray-900 text-sm tracking-tight">System Alerts</h3>
                            <button onclick="markAllNotificationsRead()" class="text-[10px] font-black uppercase tracking-wider text-blue-600 hover:text-blue-800 transition">Mark all read</button>
                        </div>
                        <div id="notification-list" class="max-h-80 overflow-y-auto divide-y divide-gray-50 custom-scrollbar">
                            <div class="p-6 text-center text-sm text-gray-400 font-medium">Loading intelligence...</div>
                        </div>
                    </div>
                </div>
                <div class="w-px h-6 bg-gray-200"></div>

                <div class="flex items-center gap-3 group">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-bold text-gray-800 leading-tight">
                            <?= $user ? sanitize($user['first_name'] . ' ' . $user['last_name']) : 'System Admin' ?>
                        </p>
                        <p class="text-xs font-medium text-gray-500">
                            <?= $user ? sanitize(ucfirst(str_replace('_', ' ', $user['role_name']))) : 'Loading...' ?>
                        </p>
                    </div>
                    <img src="<?= $user['avatar'] ?? 'https://ui-avatars.com/api/?name='.urlencode($user['first_name'] ?? 'Admin').'&background=0D8ABC&color=fff' ?>" alt="Avatar" class="w-9 h-9 rounded-full border border-gray-200 shadow-sm group-hover:ring-2 ring-blue-500 transition-all">
                    
                    <a href="<?= base_url('/auth/logout') ?>" class="px-3 py-1.5 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors ml-2">Logout</a>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 relative">
            <?php 
                if (isset($view_content) && file_exists('../app/views/' . $view_content . '.php')) {
                    require_once '../app/views/' . $view_content . '.php'; 
                } else {
                    echo "<div class='p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg font-medium shadow-sm flex items-center gap-3'>";
                    echo "<svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'></path></svg>";
                    echo "<span><strong>System Error:</strong> View file <code>'{$view_content}'</code> could not be found. Please ensure the view file exists in the <code>app/views/</code> directory.</span>";
                    echo "</div>";
                }
            ?>
        </main>
    </div>

    <script>
        const baseUrl = "<?= rtrim(base_url(), '/') ?>";
        const globalSearchInput = document.getElementById('global-search-input');
        const globalSearchForm = document.getElementById('global-search-form');

        function fetchNotifications() {
            fetch(`${baseUrl}/notification/fetch`)
                .then(res => res.json())
                .then(res => {
                    if(res.status === 'success') {
                        const badge = document.getElementById('notification-badge');
                        const list = document.getElementById('notification-list');
                        
                        if(res.count > 0) {
                            badge.innerText = res.count;
                            badge.classList.remove('hidden');
                        } else {
                            badge.classList.add('hidden');
                        }

                        list.innerHTML = '';
                        if(res.data.length === 0) {
                            list.innerHTML = `
                                <div class="p-8 flex flex-col items-center justify-center text-center">
                                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3 border border-gray-100 text-gray-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 block">All caught up!</span>
                                    <span class="text-xs text-gray-400 mt-1 block">No new system alerts.</span>
                                </div>`;
                        } else {
                            res.data.forEach(n => {
                                list.innerHTML += `
                                    <div onclick="readNotification(${n.id}, '${n.link}')" class="p-4 hover:bg-blue-50 cursor-pointer transition flex items-start gap-3 group">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-black text-gray-900 leading-tight">${n.title}</h4>
                                            <p class="text-[11px] text-gray-500 mt-1 line-clamp-2 leading-snug">${n.message}</p>
                                        </div>
                                    </div>
                                `;
                            });
                        }
                    }
                }).catch(err => console.error("Notification Polling Error", err));
        }

        function toggleNotifications() {
            document.getElementById('notification-dropdown').classList.toggle('hidden');
        }

        function readNotification(id, link) {
            fetch(`${baseUrl}/notification/read/${id}`)
                .then(() => {
                    if(link && link !== '#' && link !== 'null') {
                        window.location.href = link;
                    } else { 
                        fetchNotifications(); 
                    }
                });
        }

        function markAllNotificationsRead() {
            fetch(`${baseUrl}/notification/read_all`).then(() => {
                fetchNotifications();
                document.getElementById('notification-dropdown').classList.add('hidden');
            });
        }

        document.addEventListener('click', function(event) {
            const component = document.getElementById('notification-component');
            if (component && !component.contains(event.target)) {
                document.getElementById('notification-dropdown').classList.add('hidden');
            }
        });

        document.addEventListener('keydown', function(event) {
            if ((event.ctrlKey || event.metaKey) && event.key === '/') {
                event.preventDefault();
                if (globalSearchInput) {
                    globalSearchInput.focus();
                    globalSearchInput.select();
                }
            }
        });

        if (globalSearchForm && globalSearchInput) {
            globalSearchForm.addEventListener('submit', function(event) {
                globalSearchInput.value = globalSearchInput.value.trim();
                if (!globalSearchInput.value) {
                    event.preventDefault();
                    globalSearchInput.focus();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchNotifications();
            setInterval(fetchNotifications, 30000); // 30-Second AJAX Polling
        });
    </script>
</body>
</html>

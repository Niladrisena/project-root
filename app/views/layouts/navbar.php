<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-10 shadow-sm">
    <div class="flex-1 max-w-lg flex items-center">
        <div class="relative w-full">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" placeholder="Search projects, employees, invoices (Ctrl+/)" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm outline-none">
        </div>
    </div>

    <div class="flex items-center gap-4">
        <button class="hidden md:flex items-center gap-2 bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-sm font-medium transition-colors border border-blue-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Quick Add
        </button>

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

        <div class="flex items-center gap-3 cursor-pointer group">
            <div class="text-right hidden md:block">
                <p class="text-sm font-bold text-gray-800 leading-tight"><?= sanitize($user['first_name'] . ' ' . $user['last_name']) ?></p>
                <p class="text-xs font-medium text-gray-500"><?= sanitize(ucfirst(str_replace('_', ' ', $user['role_name']))) ?></p>
            </div>
            <img src="<?= $user['avatar'] ?? 'https://ui-avatars.com/api/?name='.urlencode($user['first_name']).'&background=0D8ABC&color=fff' ?>" alt="Avatar" class="w-9 h-9 rounded-full border border-gray-200 shadow-sm group-hover:ring-2 ring-blue-500 transition-all">
        </div>
    </div>
</header>

<script>
    const baseUrl = "<?= rtrim(base_url(), '/') ?>";

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

    document.addEventListener('DOMContentLoaded', () => {
        fetchNotifications();
        setInterval(fetchNotifications, 30000); // 30-Second AJAX Polling
    });
</script>
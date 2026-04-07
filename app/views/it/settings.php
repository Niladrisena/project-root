<div class="max-w-5xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="mb-8 flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gray-900 text-white rounded-xl flex items-center justify-center shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Global System Configurations</h1>
                <p class="text-sm text-gray-500 mt-0.5">Control core behavior, security policies, and infrastructure thresholds.</p>
            </div>
        </div>
        <a href="<?= base_url('/it/dashboard') ?>" class="px-5 py-2.5 bg-gray-50 border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-100 transition">Dashboard</a>
    </div>

    <?php if (Session::get('flash_success')): ?><div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm"><p class="text-sm text-green-700 font-bold"><?= Session::get('flash_success'); Session::set('flash_success', null); ?></p></div><?php endif; ?>
    <?php if (Session::get('flash_error')): ?><div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm"><p class="text-sm text-red-700 font-bold"><?= Session::get('flash_error'); Session::set('flash_error', null); ?></p></div><?php endif; ?>

    <form action="<?= base_url('/it/update_settings') ?>" method="POST" class="space-y-8">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg></div>
                <h3 class="font-bold text-gray-900">1. Global Identity & Localization</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Platform Name</label>
                    <input type="text" name="company_name" value="<?= sanitize($settings['company_name'] ?? '') ?>" required class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-gray-900 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-2">IT Support Email (Routing)</label>
                    <input type="email" name="support_email" value="<?= sanitize($settings['support_email'] ?? '') ?>" required class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-gray-900 outline-none transition">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Master System Timezone</label>
                    <select name="system_timezone" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-gray-900 outline-none transition custom-scrollbar">
                        <option value="UTC" <?= ($settings['system_timezone'] ?? '') == 'UTC' ? 'selected' : '' ?>>UTC (Coordinated Universal Time)</option>
                        <option value="America/New_York" <?= ($settings['system_timezone'] ?? '') == 'America/New_York' ? 'selected' : '' ?>>America / New York (EST/EDT)</option>
                        <option value="Europe/London" <?= ($settings['system_timezone'] ?? '') == 'Europe/London' ? 'selected' : '' ?>>Europe / London (GMT/BST)</option>
                        <option value="Asia/Kolkata" <?= ($settings['system_timezone'] ?? '') == 'Asia/Kolkata' ? 'selected' : '' ?>>Asia / Kolkata (IST)</option>
                        <option value="Asia/Tokyo" <?= ($settings['system_timezone'] ?? '') == 'Asia/Tokyo' ? 'selected' : '' ?>>Asia / Tokyo (JST)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></div>
                <h3 class="font-bold text-gray-900">2. Corporate Security Policies</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Idle Session Timeout (Minutes)</label>
                    <input type="number" name="session_timeout_minutes" value="<?= sanitize($settings['session_timeout_minutes'] ?? '120') ?>" required min="5" max="1440" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-gray-900 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Enforced Password Policy</label>
                    <select name="password_policy" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-gray-900 outline-none transition">
                        <option value="basic" <?= ($settings['password_policy'] ?? '') == 'basic' ? 'selected' : '' ?>>Basic (Minimum 8 Characters)</option>
                        <option value="strict" <?= ($settings['password_policy'] ?? '') == 'strict' ? 'selected' : '' ?>>Strict (8+ Chars, Numbers, Symbols, Uppercase)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg></div>
                <h3 class="font-bold text-gray-900">3. Infrastructure & Capabilities</h3>
            </div>
            <div class="p-6 space-y-6">
                
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Global File Upload Limit (MB)</label>
                    <input type="number" name="max_file_upload_mb" value="<?= sanitize($settings['max_file_upload_mb'] ?? '10') ?>" required min="1" max="500" class="w-full md:w-1/2 px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-gray-900 outline-none transition">
                </div>

                <div class="p-5 bg-orange-50 border border-orange-200 rounded-xl flex items-start gap-4">
                    <div class="mt-0.5">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="maintenance_mode" value="1" <?= ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' ?> class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                        </label>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-orange-900 uppercase tracking-wider mb-1">Activate Maintenance Mode</h4>
                        <p class="text-xs text-orange-800 font-medium">When active, all standard employees will be locked out and shown a "System Maintenance" page. Only System Owners and IT Managers will be able to log in.</p>
                    </div>
                </div>

            </div>
        </div>

        <div class="flex justify-end gap-4">
            <button type="submit" class="px-10 py-4 bg-gray-900 hover:bg-black text-white font-black rounded-xl shadow-xl transition flex items-center gap-2 transform hover:-translate-y-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Synchronize Master Configurations
            </button>
        </div>
    </form>
</div>
<div class="max-w-7xl mx-auto pb-12">
    
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">System Settings</h1>
        <p class="text-sm text-gray-500 mt-1">Configure global application parameters, company details, and system security.</p>
    </div>

    <?php if (Session::get('flash_success')): ?>
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <p class="text-sm text-green-700 font-medium"><?= Session::get('flash_success'); Session::set('flash_success', null); ?></p>
        </div>
    <?php endif; ?>

    <div class="flex flex-col md:flex-row gap-8 items-start">
        
        <div class="w-full md:w-64 shrink-0 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
            <nav class="flex flex-col">
                <a href="<?= base_url('/settings') ?>" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-l-4 border-transparent px-5 py-3.5 text-sm font-bold transition-colors flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Company Info
                </a>
                <a href="<?= base_url('/settings/localization') ?>" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-l-4 border-transparent px-5 py-3.5 text-sm font-bold transition-colors flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Localization
                </a>
                <a href="<?= base_url('/settings/smtp') ?>" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-l-4 border-transparent px-5 py-3.5 text-sm font-bold transition-colors flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    SMTP & Email
                </a>
                <a href="<?= base_url('/settings/security') ?>" class="bg-blue-50 text-blue-700 border-l-4 border-blue-600 px-5 py-3.5 text-sm font-bold transition-colors flex items-center gap-3 border-t border-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Security & API
                </a>
            </nav>
        </div>

        <div class="flex-1 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            
            <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-black text-gray-900">Security & API Access</h2>
                    <p class="text-xs font-medium text-gray-500 mt-1">Manage session timeouts, password policies, and external API integrations.</p>
                </div>
            </div>
            
            <form action="<?= base_url('/settings/update') ?>" method="POST" class="p-8 space-y-8">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                
                <div>
                    <h3 class="text-sm font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Global Security Policies</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Session Timeout (Minutes)</label>
                            <input type="number" name="session_timeout" value="<?= sanitize($settings['session_timeout'] ?? '120') ?>" required 
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                            <p class="text-xs text-gray-500 mt-1">Users will be logged out after this period of inactivity.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Password Expiry (Days)</label>
                            <input type="number" name="password_expiry" value="<?= sanitize($settings['password_expiry'] ?? '90') ?>" required 
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                            <p class="text-xs text-gray-500 mt-1">Force users to reset passwords periodically (0 to disable).</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Require 2FA for Administrators</label>
                            <select name="require_2fa" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                <option value="no" <?= (isset($settings['require_2fa']) && $settings['require_2fa'] == 'no') ? 'selected' : '' ?>>No (Optional)</option>
                                <option value="yes" <?= (isset($settings['require_2fa']) && $settings['require_2fa'] == 'yes') ? 'selected' : '' ?>>Yes (Mandatory)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Maximum Login Attempts</label>
                            <input type="number" name="max_login_attempts" value="<?= sanitize($settings['max_login_attempts'] ?? '5') ?>" required 
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                            <p class="text-xs text-gray-500 mt-1">Account locks automatically after this many failed attempts.</p>
                        </div>

                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">API Integrations</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Global API Access</label>
                            <select name="api_status" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                <option value="enabled" <?= (isset($settings['api_status']) && $settings['api_status'] == 'enabled') ? 'selected' : '' ?>>Enabled</option>
                                <option value="disabled" <?= (isset($settings['api_status']) && $settings['api_status'] == 'disabled') ? 'selected' : '' ?>>Disabled</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Master API Webhook URL</label>
                            <input type="url" name="webhook_url" value="<?= sanitize($settings['webhook_url'] ?? '') ?>" placeholder="https://api.yoursystem.com/webhook"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm">
                        </div>

                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6 flex justify-between items-center">
                    <button type="button" class="px-5 py-2.5 bg-gray-100 text-red-600 rounded-lg shadow-sm text-sm font-bold hover:bg-gray-200 transition border border-gray-200">
                        Regenerate API Keys
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg shadow-sm text-sm font-bold hover:bg-blue-700 transition focus:ring-4 focus:ring-blue-200">
                        Save Security Configuration
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</div>
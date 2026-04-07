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
                <a href="<?= base_url('/settings/smtp') ?>" class="bg-blue-50 text-blue-700 border-l-4 border-blue-600 px-5 py-3.5 text-sm font-bold transition-colors flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    SMTP & Email
                </a>
                <a href="<?= base_url('/settings/security') ?>" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-l-4 border-transparent px-5 py-3.5 text-sm font-bold transition-colors flex items-center gap-3 border-t border-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Security & API
                </a>
            </nav>
        </div>

        <div class="flex-1 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            
            <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-black text-gray-900">Email Configuration (SMTP)</h2>
                    <p class="text-xs font-medium text-gray-500 mt-1">Configure your outgoing mail server for invoices, resets, and notifications.</p>
                </div>
            </div>
            
            <form action="<?= base_url('/settings/update') ?>" method="POST" class="p-8 space-y-6">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Mail Driver</label>
                        <select name="mail_driver" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                            <option value="smtp" <?= (isset($settings['mail_driver']) && $settings['mail_driver'] == 'smtp') ? 'selected' : '' ?>>SMTP (Recommended)</option>
                            <option value="mailgun" <?= (isset($settings['mail_driver']) && $settings['mail_driver'] == 'mailgun') ? 'selected' : '' ?>>Mailgun API</option>
                            <option value="sendmail" <?= (isset($settings['mail_driver']) && $settings['mail_driver'] == 'sendmail') ? 'selected' : '' ?>>Server Sendmail</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Mail Encryption</label>
                        <select name="mail_encryption" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                            <option value="tls" <?= (isset($settings['mail_encryption']) && $settings['mail_encryption'] == 'tls') ? 'selected' : '' ?>>TLS</option>
                            <option value="ssl" <?= (isset($settings['mail_encryption']) && $settings['mail_encryption'] == 'ssl') ? 'selected' : '' ?>>SSL</option>
                            <option value="none" <?= (isset($settings['mail_encryption']) && $settings['mail_encryption'] == 'none') ? 'selected' : '' ?>>None</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">SMTP Host</label>
                        <input type="text" name="mail_host" value="<?= sanitize($settings['mail_host'] ?? 'smtp.mailtrap.io') ?>" placeholder="e.g., smtp.gmail.com"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition font-mono text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">SMTP Port</label>
                        <input type="number" name="mail_port" value="<?= sanitize($settings['mail_port'] ?? '587') ?>" placeholder="e.g., 587, 465, or 25"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition font-mono text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">SMTP Username</label>
                        <input type="text" name="mail_username" value="<?= sanitize($settings['mail_username'] ?? '') ?>" autocomplete="off"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">SMTP Password</label>
                        <input type="password" name="mail_password" value="<?= sanitize($settings['mail_password'] ?? '') ?>" autocomplete="off" placeholder="••••••••••••"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">"From" Address & Name</label>
                        <div class="flex gap-2">
                            <input type="email" name="mail_from_address" value="<?= sanitize($settings['mail_from_address'] ?? 'noreply@enterprise.com') ?>" placeholder="Email"
                                   class="w-1/2 px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm">
                            <input type="text" name="mail_from_name" value="<?= sanitize($settings['mail_from_name'] ?? 'Enterprise SaaS') ?>" placeholder="Name"
                                   class="w-1/2 px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm">
                        </div>
                    </div>

                </div>

                <div class="border-t border-gray-100 pt-6 mt-8 flex justify-between items-center">
                    <button type="button" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg shadow-sm text-sm font-bold hover:bg-gray-200 transition border border-gray-200">
                        Test Connection
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg shadow-sm text-sm font-bold hover:bg-blue-700 transition focus:ring-4 focus:ring-blue-200">
                        Save SMTP Configuration
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</div>
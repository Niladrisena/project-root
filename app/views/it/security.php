<div class="max-w-3xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="mb-8 flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-slate-900 text-white rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Personal Security</h1>
                <p class="text-sm text-gray-500 mt-0.5">Manage your cryptographic credentials and account access.</p>
            </div>
        </div>
        <a href="<?= base_url('/it/dashboard') ?>" class="px-5 py-2.5 bg-gray-50 border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-100 transition">
            &larr; Back to Hub
        </a>
    </div>

    <?php if (Session::get('flash_error')): ?>
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
            <p class="text-sm text-red-700 font-bold flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <?= Session::get('flash_error'); Session::set('flash_error', null); ?>
            </p>
        </div>
    <?php endif; ?>
    <?php if (Session::get('flash_success')): ?>
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm">
            <p class="text-sm text-green-700 font-bold flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <?= Session::get('flash_success'); Session::set('flash_success', null); ?>
            </p>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/it/security') ?>" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <div class="p-8 space-y-6">
            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest border-b border-gray-100 pb-3 mb-6">Change Dashboard Password</h3>
            
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Current Password <span class="text-red-500">*</span></label>
                <input type="password" name="current_password" required placeholder="Enter your current password" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none transition">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">New Password <span class="text-red-500">*</span></label>
                    <input type="password" name="new_password" required placeholder="Minimum 8 characters" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Confirm New Password <span class="text-red-500">*</span></label>
                    <input type="password" name="confirm_password" required placeholder="Retype new password" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none transition">
                </div>
            </div>
            
            <div class="mt-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                <p class="text-xs text-slate-800 font-medium leading-relaxed">
                    <span class="font-bold uppercase tracking-wider">Security Tip:</span> As a system administrator, you should use a combination of uppercase letters, lowercase letters, numbers, and symbols. You will remain logged in after updating your password.
                </p>
            </div>
        </div>

        <div class="bg-gray-50 px-8 py-5 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-8 py-3.5 bg-slate-900 hover:bg-black text-white font-black rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                Update Credentials
            </button>
        </div>
    </form>
</div>
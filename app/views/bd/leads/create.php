<div class="max-w-4xl mx-auto py-10 px-4">
    <nav class="mb-6 flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-400">
        <a href="<?= base_url('/bd/dashboard') ?>" class="hover:text-indigo-600 transition">Dashboard</a>
        <span>/</span>
        <span class="text-indigo-600">Create New Lead</span>
    </nav>

    <?php if (Session::get('flash_error')): ?>
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
            <p class="text-sm text-red-700 font-bold"><?= Session::get('flash_error'); Session::set('flash_error', null); ?></p>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-xl overflow-hidden">
        <div class="bg-indigo-700 p-8 text-white">
            <h2 class="text-2xl font-black tracking-tight">Lead Information 📋</h2>
            <p class="text-indigo-100 text-sm mt-1 font-medium">Enter details to initiate a new project proposal.</p>
        </div>

        <form action="<?= base_url('/leads/store') ?>" method="POST" class="p-8 space-y-6">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-wider">Company Name</label>
                    <input type="text" name="company_name" required 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition outline-none text-sm font-medium" 
                           placeholder="Acme Corp">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-wider">Contact Person</label>
                    <input type="text" name="contact_person" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition outline-none text-sm font-medium"
                           placeholder="John Doe">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-wider">Email Address</label>
                    <input type="email" name="email" required 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition outline-none text-sm font-medium" 
                           placeholder="john@company.com">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-wider">Phone Number</label>
                    <input type="text" name="phone"
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition outline-none text-sm font-medium"
                           placeholder="+91 98765 43210">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-wider">Lead Status</label>
                    <select name="status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-medium">
                        <option value="New">New</option>
                        <option value="Active">Active</option>
                        <option value="Closed">Closed</option>
                    </select>
                </div>
            </div>

            <hr class="border-gray-100">

            <div class="flex justify-end gap-3 pt-4">
                <a href="<?= base_url('/bd/dashboard') ?>" class="px-6 py-3 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded-xl transition">Cancel</a>
                <button type="submit" class="px-8 py-3 bg-indigo-700 hover:bg-indigo-800 text-white font-black rounded-xl shadow-lg transition transform hover:-translate-y-0.5">
                    Save & Create Lead
                </button>
            </div>
        </form>
    </div>
</div>

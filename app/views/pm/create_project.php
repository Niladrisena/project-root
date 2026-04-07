<div class="max-w-5xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="mb-8 flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Initiate Project Architecture</h1>
                <p class="text-sm text-gray-500 mt-0.5">Define scope, timelines, financials, and risk priorities.</p>
            </div>
        </div>
        <a href="<?= base_url('/pm/dashboard') ?>" class="px-5 py-2.5 bg-gray-50 border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-100 transition">
            &larr; Cancel
        </a>
    </div>

    <?php if (Session::get('flash_error')): ?>
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm">
            <p class="text-sm text-red-700 font-bold"><?= Session::get('flash_error'); Session::set('flash_error', null); ?></p>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/pm/create_project') ?>" method="POST" class="space-y-8">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                <h3 class="font-bold text-gray-900">1. Project Identity & Scope</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Project Title <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g., Enterprise Cloud Migration Q3" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Client Assignment</label>
                    <select name="client_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <option value="">-- Internal Project (No Client) --</option>
                        <option value="1">Acme Corporation</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Risk & Priority Level <span class="text-red-500">*</span></label>
                    <select name="priority" required class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <option value="low">Low Priority (Maintenance)</option>
                        <option value="medium" selected>Medium Priority (Standard)</option>
                        <option value="high">High Priority (Urgent)</option>
                        <option value="critical">Critical (Blocker / VIP)</option>
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Project Brief & Objectives</label>
                    <textarea name="description" rows="4" placeholder="Define the core objectives, deliverables, and expected outcomes..." class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition resize-none"></textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                <h3 class="font-bold text-gray-900">2. Allocation & Deadlines</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6">
                
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Currency</label>
                    <select name="currency_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-green-500 outline-none transition custom-scrollbar">
                        <option value="1" selected>USD ($) - US Dollar</option>
                        <option value="2">EUR (€) - Euro</option>
                        <option value="3">GBP (£) - British Pound</option>
                        <option value="4">INR (₹) - Indian Rupee</option>
                        <option value="5">AUD ($) - Australian Dollar</option>
                        <option value="6">CAD ($) - Canadian Dollar</option>
                        <option value="7">JPY (¥) - Japanese Yen</option>
                        <option value="8">SAR (﷼) - Saudi Riyal</option>
                        <option value="9">QAR (﷼) - Qatari Riyal</option>
                        <option value="10">AED (د.إ) - UAE Dirham</option>
                        <option value="11">CHF (Fr) - Swiss Franc</option>
                        <option value="12">CNY (¥) - Chinese Yuan</option>
                        <option value="13">SGD ($) - Singapore Dollar</option>
                        <option value="14">NZD ($) - New Zealand Dollar</option>
                        <option value="15">ZAR (R) - South African Rand</option>
                    </select>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Estimated Budget</label>
                    <input type="number" step="0.01" name="estimated_budget" placeholder="0.00" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-green-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Kickoff Date <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" required value="<?= date('Y-m-d') ?>" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-green-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Final Deadline <span class="text-red-500">*</span></label>
                    <input type="date" name="deadline" required class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-green-500 outline-none transition">
                </div>

            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></div>
                <h3 class="font-bold text-gray-900">3. Resource & Hour Allocation</h3>
            </div>
            
            <div class="p-6">
                <div class="mb-8 p-5 bg-orange-50 rounded-xl border border-orange-100 flex items-center justify-between">
                    <div>
                        <label class="block text-sm font-black text-orange-900 tracking-tight mb-1">Total Project Hourly Cap <span class="text-red-500">*</span></label>
                        <p class="text-xs text-orange-700 font-medium">Define the maximum billable hours available for this project lifecycle.</p>
                    </div>
                    <div class="w-48 relative">
                        <input type="number" step="0.5" name="total_hours" required placeholder="e.g., 500" class="w-full px-4 py-3 bg-white border border-orange-200 rounded-xl text-lg font-black text-gray-900 focus:ring-2 focus:ring-orange-500 outline-none transition text-right shadow-sm">
                        <span class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400 font-bold text-sm">Hrs</span>
                    </div>
                </div>

                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-4">Distribute Hours Across Team Personnel</label>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php if(empty($employees)): ?>
                        <div class="col-span-full p-4 text-center text-sm text-gray-500 bg-gray-50 rounded-lg">No active personnel available for allocation.</div>
                    <?php else: ?>
                        <?php foreach($employees as $emp): ?>
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:border-orange-300 transition bg-white shadow-sm group">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-gray-100 text-gray-500 font-black rounded-full flex items-center justify-center text-xs shadow-inner">
                                    <?= substr($emp['first_name'], 0, 1) . substr($emp['last_name'], 0, 1) ?>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 group-hover:text-orange-600 transition"><?= sanitize($emp['first_name'] . ' ' . $emp['last_name']) ?></p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider"><?= sanitize($emp['email']) ?> &bull; Role ID: <?= $emp['role_id'] ?></p>
                                </div>
                            </div>
                            <div class="w-20 relative">
                                <input type="number" step="0.5" name="team_hours[<?= $emp['id'] ?>]" placeholder="0" class="w-full px-2 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-center font-bold text-gray-900 focus:bg-white focus:ring-2 focus:ring-orange-500 outline-none transition">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <button type="submit" class="px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Deploy Project Workspace
            </button>
        </div>
    </form>
</div>
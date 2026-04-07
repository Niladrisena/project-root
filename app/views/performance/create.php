<div class="max-w-4xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Initiate Appraisal</h1>
            <p class="text-sm text-gray-500 mt-1">Submit an official performance review or save a draft.</p>
        </div>
        <a href="<?= base_url('/performance') ?>" class="text-sm font-bold text-gray-500 hover:text-gray-800 transition">Cancel & Go Back</a>
    </div>

    <form action="<?= base_url('/performance/create') ?>" method="POST" class="space-y-8 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Evaluatee (Employee) <span class="text-red-500">*</span></label>
                    <select name="employee_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 outline-none transition">
                        <option value="">-- Select an Employee --</option>
                        <?php foreach($employees as $emp): ?>
                            <option value="<?= $emp['id'] ?>"><?= sanitize($emp['first_name'] . ' ' . $emp['last_name']) ?> (<?= sanitize($emp['email']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Review Period <span class="text-red-500">*</span></label>
                    <select name="review_period" required class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 outline-none transition">
                        <option value="Q1 <?= date('Y') ?>">Q1 <?= date('Y') ?> (Jan - Mar)</option>
                        <option value="Q2 <?= date('Y') ?>">Q2 <?= date('Y') ?> (Apr - Jun)</option>
                        <option value="Q3 <?= date('Y') ?>">Q3 <?= date('Y') ?> (Jul - Sep)</option>
                        <option value="Q4 <?= date('Y') ?>">Q4 <?= date('Y') ?> (Oct - Dec)</option>
                        <option value="Annual <?= date('Y') ?>">Annual Review <?= date('Y') ?></option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Overall Rating (1.0 - 5.0) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.1" min="1.0" max="5.0" name="rating" required placeholder="e.g., 4.5" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 outline-none transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Manager Comments & Feedback</label>
                <textarea name="comments" rows="6" placeholder="Detail the employee's achievements, areas for improvement, and goal progression..." class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 outline-none transition resize-none"></textarea>
            </div>
        </div>

        <div class="bg-gray-50 px-8 py-5 border-t border-gray-100 flex justify-end gap-4">
            <button type="submit" name="action" value="draft" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-50 transition">
                Save as Draft
            </button>
            <button type="submit" name="action" value="submit" class="px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white font-black rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Submit Official Review
            </button>
        </div>
    </form>

</div>
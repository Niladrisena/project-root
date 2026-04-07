<div class="max-w-4xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="mb-8 flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-teal-900 text-white rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Log Transaction</h1>
                <p class="text-sm text-gray-500 mt-0.5">Record a new income or expense entry into the corporate ledger.</p>
            </div>
        </div>
        <a href="<?= base_url('/finance/transactions') ?>" class="px-5 py-2.5 bg-gray-50 border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-100 transition">
            &larr; Cancel
        </a>
    </div>

    <form action="<?= base_url('/finance/log_transaction') ?>" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <div class="p-8 space-y-8">
            
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-3">Transaction Type <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="income" class="peer sr-only" required checked>
                        <div class="px-6 py-4 rounded-xl border-2 border-gray-200 peer-checked:border-green-500 peer-checked:bg-green-50 hover:bg-gray-50 transition flex items-center justify-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center peer-checked:bg-green-500 peer-checked:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg></div>
                            <span class="font-black text-gray-900 peer-checked:text-green-800">Income (Revenue)</span>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="expense" class="peer sr-only">
                        <div class="px-6 py-4 rounded-xl border-2 border-gray-200 peer-checked:border-red-500 peer-checked:bg-red-50 hover:bg-gray-50 transition flex items-center justify-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-red-100 text-red-600 flex items-center justify-center peer-checked:bg-red-500 peer-checked:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path></svg></div>
                            <span class="font-black text-gray-900 peer-checked:text-red-800">Expense (Cost)</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Transaction Amount <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-lg font-black">$</span>
                        </div>
                        <input type="number" step="0.01" name="amount" required placeholder="0.00" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-lg font-black focus:bg-white focus:ring-2 focus:ring-teal-900 outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Transaction Date <span class="text-red-500">*</span></label>
                    <input type="date" name="transaction_date" required value="<?= date('Y-m-d') ?>" class="w-full px-4 py-3.5 bg-gray-50 border border-gray-300 rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-teal-900 outline-none transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Category <span class="text-red-500">*</span></label>
                    <input type="text" name="category" required placeholder="e.g. Software Subscription, Vendor Payment" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-teal-900 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Reference / Receipt No.</label>
                    <input type="text" name="reference_no" placeholder="Optional" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-teal-900 outline-none transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Detailed Description</label>
                <textarea name="description" rows="3" placeholder="Provide additional details regarding this transaction..." class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm font-medium focus:bg-white focus:ring-2 focus:ring-teal-900 outline-none transition"></textarea>
            </div>
            
        </div>

        <div class="bg-gray-50 px-8 py-5 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-8 py-3.5 bg-teal-900 hover:bg-teal-800 text-white font-black rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Save to Ledger
            </button>
        </div>
    </form>
</div>
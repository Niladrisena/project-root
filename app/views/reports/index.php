<div class="max-w-7xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Master Report Builder</h1>
    <form action="<?= base_url('/reports/generate') ?>" method="POST" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Data Source</label>
                <select name="module" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500">
                    <option value="finance_pl">Financial Profit/Loss</option>
                    <option value="hr_attendance">HR Attendance & Overtime</option>
                    <option value="projects">Project Status & Profitability</option>
                    <option value="inventory">Inventory Valuation</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Date Range</label>
                <div class="flex items-center gap-2">
                    <input type="date" name="start_date" class="w-full border border-gray-300 rounded-lg p-2.5">
                    <span class="text-gray-400">to</span>
                    <input type="date" name="end_date" class="w-full border border-gray-300 rounded-lg p-2.5">
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Export Format</label>
                <select name="export_type" class="w-full border border-gray-300 rounded-lg p-2.5">
                    <option value="screen">View on Screen</option>
                    <option value="csv">Download CSV (Excel)</option>
                    <option value="pdf">Download PDF</option>
                </select>
            </div>
        </div>
        
        <div class="border-t border-gray-100 pt-6 flex justify-end">
            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow transition">Generate Report</button>
        </div>
    </form>
</div>
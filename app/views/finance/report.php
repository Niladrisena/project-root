<div class="max-w-5xl mx-auto pb-12">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Financial Reports</h1>
        <a href="<?= base_url('/finance') ?>" class="text-sm font-medium text-gray-600 bg-white border border-gray-300 px-4 py-2 rounded-lg shadow-sm">Back</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <svg class="w-16 h-16 text-blue-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        <h2 class="text-xl font-bold text-gray-900 mb-2">Report Generator Engine</h2>
        <p class="text-gray-500 max-w-md mx-auto mb-6">Select a date range and report type below to export your financial data to PDF or CSV.</p>
        
        <div class="flex justify-center gap-4">
            <a href="<?= base_url('/finance/export/pdf') ?>" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg shadow-sm hover:bg-blue-700 transition">Export PDF</a>
            <a href="<?= base_url('/finance/export/csv') ?>" class="px-6 py-2 bg-green-600 text-white font-bold rounded-lg shadow-sm hover:bg-green-700 transition">Export CSV</a>
        </div>
    </div>
</div>
<?php 
    // ==========================================
    // ELITE UI ROUTER: Dynamic Back Navigation
    // Determines the correct Dashboard URL based on Role ID
    // ==========================================
    $nav_role_id = (int) Session::get('role_id'); 
    $back_url = base_url('/dashboard/employee'); // Safe Default
    
    if ($nav_role_id === 1) {
        $back_url = base_url('/dashboard'); // System Owner
    } elseif ($nav_role_id === 3) {
        $back_url = base_url('/hr/dashboard'); // HR Manager
    } elseif ($nav_role_id === 4) {
        $back_url = base_url('/pm/dashboard'); // Project Manager
    }
?>
<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Performance Appraisals</h1>
                <p class="text-sm text-gray-500 mt-0.5">Track employee evaluations, KPIs, and corporate growth metrics.</p>
            </div>
        </div>
        <div class="flex gap-3">
            
            <a href="<?= $back_url ?>" class="px-5 py-2.5 bg-gray-50 border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-100 transition">
                &larr; Back to Hub
            </a>

            <a href="<?= base_url('/performance/create') ?>" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-black rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Appraisal
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Company Average</p>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight"><?= number_format($companyAverage ?? 0, 1) ?> <span class="text-lg text-gray-400 font-medium">/ 5.0</span></h2>
            </div>
            <div class="w-12 h-12 bg-green-50 text-green-500 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-900">Recent Evaluations</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <th class="px-6 py-4">Employee</th>
                        <th class="px-6 py-4">Review Period</th>
                        <th class="px-6 py-4">Reviewer</th>
                        <th class="px-6 py-4">Rating</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if(empty($reviews)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <span class="font-bold text-gray-900 block">No Appraisals Found</span>
                                <span class="text-sm">Initiate a new performance review to get started.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($reviews as $rev): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900"><?= sanitize($rev['emp_first'] . ' ' . $rev['emp_last']) ?></p>
                                    <p class="text-xs text-gray-400"><?= sanitize($rev['emp_email']) ?></p>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-700">
                                    <?= sanitize($rev['review_period']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?= sanitize($rev['rev_first'] . ' ' . $rev['rev_last']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-black text-gray-900"><?= number_format($rev['rating'], 1) ?></span>
                                        <div class="flex text-yellow-400 text-xs">
                                            <?php for($i=1; $i<=5; $i++): ?>
                                                <svg class="w-3 h-3 <?= $i <= $rev['rating'] ? 'fill-current' : 'text-gray-200 fill-current' ?>" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if($rev['status'] == 'submitted'): ?>
                                        <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase tracking-wider rounded-md border border-blue-200">Submitted</span>
                                    <?php elseif($rev['status'] == 'acknowledged'): ?>
                                        <span class="px-2.5 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase tracking-wider rounded-md border border-green-200">Acknowledged</span>
                                    <?php else: ?>
                                        <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold uppercase tracking-wider rounded-md border border-gray-200">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-sm font-bold text-blue-600 hover:text-blue-800 transition">View</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
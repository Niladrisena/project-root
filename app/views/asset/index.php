<div class="max-w-7xl mx-auto pb-12 px-4 md:px-0">
    
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">My Assets</h1>
            <p class="text-sm text-gray-500 mt-1">View and manage company hardware, software, and access keys assigned to you.</p>
        </div>
        <button onclick="window.print()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-bold text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print Record
        </button>
    </div>

    <?php if(empty($assets)): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-16 text-center">
            <div class="w-20 h-20 bg-orange-50 text-orange-400 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-1">No Assets Assigned</h3>
            <p class="text-gray-500 max-w-sm mx-auto">You currently do not have any company hardware or licenses assigned to your profile.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($assets as $asset): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-br from-gray-50 to-white flex justify-between items-start">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 shadow-inner">
                            <?php if($asset['asset_type'] == 'software'): ?>
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                            <?php else: ?>
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <?php endif; ?>
                        </div>
                        <?php 
                            $statusColors = [
                                'new' => 'bg-green-100 text-green-700',
                                'good' => 'bg-blue-100 text-blue-700',
                                'fair' => 'bg-yellow-100 text-yellow-700',
                                'poor' => 'bg-red-100 text-red-700'
                            ];
                            $badgeColor = $statusColors[$asset['condition_status']] ?? 'bg-gray-100 text-gray-700';
                        ?>
                        <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full <?= $badgeColor ?>">
                            <?= sanitize($asset['condition_status']) ?>
                        </span>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="font-bold text-gray-900 text-lg leading-tight mb-1"><?= sanitize($asset['asset_name']) ?></h3>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-100 pb-4">
                            Tag: <span class="font-bold text-gray-800"><?= sanitize($asset['asset_tag'] ?? 'N/A') ?></span>
                        </p>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Serial Number</span>
                                <span class="font-medium text-gray-900 font-mono"><?= sanitize($asset['serial_number'] ?? 'N/A') ?></span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Assigned On</span>
                                <span class="font-medium text-gray-900"><?= date('M d, Y', strtotime($asset['assigned_date'])) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div class="mt-8 bg-blue-50 border border-blue-100 rounded-xl p-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-blue-200 text-blue-700 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <h4 class="text-sm font-bold text-blue-900">Need IT Support?</h4>
                <p class="text-xs text-blue-700 mt-0.5">Report broken equipment or request software upgrades.</p>
            </div>
        </div>
        <button class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-sm transition">
            Contact IT Desk
        </button>
    </div>

</div>
<script>
document.addEventListener("DOMContentLoaded", function(){

    const buttons = document.querySelectorAll("button");

    buttons.forEach(function(btn){

        if(btn.innerText.trim() === "Contact IT Desk"){

            btn.addEventListener("click", function(){

                window.location.href = "<?= ROOT ?>/support/create";

            });

        }

    });

});
</script>
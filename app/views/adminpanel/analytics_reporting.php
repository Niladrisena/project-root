<div class="p-8 bg-[#F0F2F5] min-h-screen font-sans">
    <?php
    $renderAnalyticsIcon = static function (string $icon, string $classes = 'w-7 h-7') {
        $icons = [
            'rocket' => '<svg class="' . $classes . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 19c2.5-1 4.5-1 6 0-1.5-1.5-1.5-3.5 0-6l6.5-6.5c1.5 4 .8 8.2-2.1 11.1C12.5 20.5 8.8 21.2 5 19Z"/><path d="M13 5l6 6"/><path d="M8 16l-2.5 2.5"/><path d="M10.5 8.5l5 5"/><path d="M6 12c-.8-1.5-.8-3.2.1-4.7L8 5.5l1.5 1.5"/><path d="M12 18l1.5 1.5 2.3-1.9c1.5-.8 2.5-2.5 2.7-4.3"/></svg>',
            'fa-rocket-launch' => '<svg class="' . $classes . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 19c2.5-1 4.5-1 6 0-1.5-1.5-1.5-3.5 0-6l6.5-6.5c1.5 4 .8 8.2-2.1 11.1C12.5 20.5 8.8 21.2 5 19Z"/><path d="M13 5l6 6"/><path d="M8 16l-2.5 2.5"/><path d="M10.5 8.5l5 5"/><path d="M6 12c-.8-1.5-.8-3.2.1-4.7L8 5.5l1.5 1.5"/><path d="M12 18l1.5 1.5 2.3-1.9c1.5-.8 2.5-2.5 2.7-4.3"/></svg>',
            'team' => '<svg class="' . $classes . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="2.5"/><circle cx="6.5" cy="10" r="2"/><circle cx="17.5" cy="10" r="2"/><path d="M8.5 16.5c.7-1.7 2-2.5 3.5-2.5s2.8.8 3.5 2.5"/><path d="M3.8 17c.5-1.3 1.5-2 2.7-2 .7 0 1.4.2 2 .7"/><path d="M15.5 15.7c.6-.5 1.3-.7 2-.7 1.2 0 2.2.7 2.7 2"/></svg>',
            'fa-users-viewfinder' => '<svg class="' . $classes . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="2.5"/><circle cx="6.5" cy="10" r="2"/><circle cx="17.5" cy="10" r="2"/><path d="M8.5 16.5c.7-1.7 2-2.5 3.5-2.5s2.8.8 3.5 2.5"/><path d="M3.8 17c.5-1.3 1.5-2 2.7-2 .7 0 1.4.2 2 .7"/><path d="M15.5 15.7c.6-.5 1.3-.7 2-.7 1.2 0 2.2.7 2.7 2"/></svg>',
            'check' => '<svg class="' . $classes . '" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" fill="currentColor" fill-opacity="0.16"></circle><path d="M8.7 12.3l2.2 2.2 4.5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path></svg>',
            'fa-circle-check' => '<svg class="' . $classes . '" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" fill="currentColor" fill-opacity="0.16"></circle><path d="M8.7 12.3l2.2 2.2 4.5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path></svg>',
            'money-bag' => '<svg class="' . $classes . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 4h6l-2 3h-2l-2-3Z"/><path d="M10 7h4c3.6 1.9 5.5 5 5.5 8.4A5.6 5.6 0 0 1 13.9 21H10.1A5.6 5.6 0 0 1 4.5 15.4C4.5 12 6.4 8.9 10 7Z"/><path d="M12 10.2v5.6"/><path d="M10 12.2c.5-.5 1.2-.8 2-.8 1.1 0 2 .7 2 1.6s-.9 1.6-2 1.6-2 .7-2 1.6.9 1.6 2 1.6c.8 0 1.5-.3 2-.8"/><path d="M16.8 9.6l1.8-1.2"/></svg>',
            'fa-money-bill-transfer' => '<svg class="' . $classes . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 4h6l-2 3h-2l-2-3Z"/><path d="M10 7h4c3.6 1.9 5.5 5 5.5 8.4A5.6 5.6 0 0 1 13.9 21H10.1A5.6 5.6 0 0 1 4.5 15.4C4.5 12 6.4 8.9 10 7Z"/><path d="M12 10.2v5.6"/><path d="M10 12.2c.5-.5 1.2-.8 2-.8 1.1 0 2 .7 2 1.6s-.9 1.6-2 1.6-2 .7-2 1.6.9 1.6 2 1.6c.8 0 1.5-.3 2-.8"/><path d="M16.8 9.6l1.8-1.2"/></svg>',
            'summary' => '<svg class="' . $classes . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 3.5h7l4 4V20a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-15a1 1 0 0 1 1-1.5Z"/><path d="M14 3.5V8h4"/><path d="M9 12h6"/><path d="M9 15h6"/><path d="M9 18h3"/><path d="M8 8.5h2.5v2.5H8z" fill="currentColor" fill-opacity="0.2" stroke="none"/></svg>',
            'fa-file-chart-column' => '<svg class="' . $classes . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 3.5h7l4 4V20a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-15a1 1 0 0 1 1-1.5Z"/><path d="M14 3.5V8h4"/><path d="M9 12h6"/><path d="M9 15h6"/><path d="M9 18h3"/><path d="M8 8.5h2.5v2.5H8z" fill="currentColor" fill-opacity="0.2" stroke="none"/></svg>',
            'allocation' => '<svg class="' . $classes . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="7" cy="8" r="2.2"/><circle cx="17" cy="7.5" r="2.2"/><circle cx="12" cy="15.5" r="2.4"/><path d="M3.8 18c.6-1.7 1.9-2.6 3.4-2.6S10 16.3 10.6 18"/><path d="M13.4 18c.6-1.7 1.9-2.6 3.4-2.6s2.8.9 3.4 2.6"/><path d="M9.2 11.3 10.8 13"/><path d="M14.8 13 16.4 11.3"/></svg>',
            'fa-user-group-crown' => '<svg class="' . $classes . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="7" cy="8" r="2.2"/><circle cx="17" cy="7.5" r="2.2"/><circle cx="12" cy="15.5" r="2.4"/><path d="M3.8 18c.6-1.7 1.9-2.6 3.4-2.6S10 16.3 10.6 18"/><path d="M13.4 18c.6-1.7 1.9-2.6 3.4-2.6s2.8.9 3.4 2.6"/><path d="M9.2 11.3 10.8 13"/><path d="M14.8 13 16.4 11.3"/></svg>',
            'financial' => '<svg class="' . $classes . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 3.5h7l5 5V20a1 1 0 0 1-1 1H6.8A1.8 1.8 0 0 1 5 19.2V5.3A1.8 1.8 0 0 1 6.8 3.5Z"/><path d="M13 3.5V9h5"/><path d="M8 13.5h8"/><path d="M8 17h4"/><path d="M9 8.8c.6-.5 1.3-.8 2.1-.8 1.2 0 2.1.7 2.1 1.7s-.9 1.7-2.1 1.7-2.1.8-2.1 1.7"/><path d="M11.1 7.1v8.4"/></svg>',
            'fa-file-invoice-dollar' => '<svg class="' . $classes . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 3.5h7l5 5V20a1 1 0 0 1-1 1H6.8A1.8 1.8 0 0 1 5 19.2V5.3A1.8 1.8 0 0 1 6.8 3.5Z"/><path d="M13 3.5V9h5"/><path d="M8 13.5h8"/><path d="M8 17h4"/><path d="M9 8.8c.6-.5 1.3-.8 2.1-.8 1.2 0 2.1.7 2.1 1.7s-.9 1.7-2.1 1.7-2.1.8-2.1 1.7"/><path d="M11.1 7.1v8.4"/></svg>',
            'utilization' => '<svg class="' . $classes . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 6v6l3.5 2"/><path d="M6.6 17.4a7.5 7.5 0 1 1 10.8 0"/><path d="M4 19h2.8"/><path d="M17.2 19H20"/><path d="M9 19v-2.2"/><path d="M12 19v-4"/><path d="M15 19v-3"/></svg>',
            'fa-clock-rotate-left' => '<svg class="' . $classes . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 6v6l3.5 2"/><path d="M6.6 17.4a7.5 7.5 0 1 1 10.8 0"/><path d="M4 19h2.8"/><path d="M17.2 19H20"/><path d="M9 19v-2.2"/><path d="M12 19v-4"/><path d="M15 19v-3"/></svg>',
            'insight' => '<svg class="' . $classes . '" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3.8a6.6 6.6 0 0 0-4.5 11.4c.9.8 1.5 1.8 1.8 2.9h5.4c.3-1.1.9-2.1 1.8-2.9A6.6 6.6 0 0 0 12 3.8Z" fill="currentColor" fill-opacity="0.18" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M9.5 20.2h5"/><path d="M10 17.8h4"/><path d="M12 8v3.2"/><path d="M12 14h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
            'plus' => '<svg class="' . $classes . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14"/><path d="M5 12h14"/></svg>',
            'export' => '<svg class="' . $classes . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 4v10"/><path d="m8.5 10.5 3.5 3.5 3.5-3.5"/><path d="M5 18.5h14"/></svg>',
        ];

        return $icons[$icon] ?? '';
    };
    ?>
    
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#2D3748]">Analytics & Reporting</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <?php 
        $stats = $stats ?? ['active' => 3, 'team_size' => 5, 'completed' => 12, 'revenue' => 1250000];
        
        $kpi_configs = [
            ['Active Projects', $stats['active'], 'rocket', 'bg-[#F3E8FF]', 'text-[#9333EA]'],
            ['Team Members', $stats['team_size'], 'team', 'bg-[#E0E7FF]', 'text-[#4F46E5]'],
            ['Completed Projects', $stats['completed'], 'check', 'bg-[#DCFCE7]', 'text-[#16A34A]'],
            ['Total Revenue', '₹' . number_format($stats['revenue']), 'fa-money-bill-transfer', 'bg-[#DBEAFE]', 'text-[#1E40AF]']
        ];

        foreach ($kpi_configs as $kpi): ?>
            <div class="bg-white p-6 rounded-2xl border border-white shadow-sm flex items-center gap-5 transition-all hover:shadow-md">
                <div class="w-14 h-14 <?= $kpi[3] ?> <?= $kpi[4] ?> rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                    <?= $renderAnalyticsIcon($kpi[2]) ?>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-400 mb-0.5"><?= $kpi[0] ?></p>
                    <p class="text-2xl font-extrabold text-[#1A202C]"><?= $kpi[1] ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-8">
            <h2 class="text-lg font-bold text-[#2D3748] mb-6">Analytics & Reporting</h2>
            
            <div class="space-y-4">
                <?php 
                // Icon mapping precisely matched to the screenshot circular background style
                $reports = [
                    ['Project Summary Report', 'Generate comprehensive reports summarizing all project activities and progress.', 'summary', 'bg-blue-50 text-blue-400'],
                    ['Resource Allocation Report', 'Analyze team utilization and workload distribution across projects.', 'allocation', 'bg-indigo-50 text-indigo-400'],
                    ['Revenue & Financial Report', 'Review detailed reports on revenue, profitability, and financial performance.', 'financial', 'bg-orange-50 text-orange-400'],
                    ['Hour Utilization Report', 'Generate reports on employee working hours and utilization rates.', 'utilization', 'bg-cyan-50 text-cyan-400']
                ];

                foreach ($reports as $report): ?>
                    <div class="group bg-white p-6 rounded-3xl border border-transparent flex items-center justify-between hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 <?= $report[3] ?> rounded-full flex items-center justify-center text-2xl transition-transform group-hover:scale-110">
                                <?= $renderAnalyticsIcon($report[2]) ?>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#1A202C] text-lg mb-1"><?= $report[0] ?></h3>
                                <p class="text-sm text-gray-400 max-w-md leading-relaxed"><?= $report[1] ?></p>
                            </div>
                        </div>
                        <button class="bg-[#F1F5F9] text-[#475569] px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-[#1E293B] hover:text-white transition-colors">
                            Explore
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-6">
            <h2 class="text-lg font-bold text-[#2D3748] mb-6">Quick Insights</h2>
            
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-3 bg-blue-50 text-blue-500 rounded-2xl">
                        <?= $renderAnalyticsIcon('insight', 'w-6 h-6') ?>
                    </div>
                    <div class="flex flex-col">
                        <div class="h-1 w-8 bg-orange-200 rounded-full mb-1"></div>
                        <div class="h-1 w-12 bg-gray-100 rounded-full"></div>
                    </div>
                </div>
                
                <h3 class="font-bold text-[#1A202C] text-xl mb-4 leading-tight">
                    Kingdom Tower Phase 3 Update
                </h3>
                
                <p class="text-gray-400 leading-relaxed mb-8 text-sm">
                    Time elapsed is <span class="font-bold text-[#2D3748]">60%</span> of allocated time, 
                    but <span class="font-bold text-red-500">80%</span> of budget has been used. 
                    Immediate cost review recommended.
                </p>
                
                <a href="#" class="inline-flex items-center text-indigo-600 font-bold text-sm hover:underline underline-offset-4">
                    View Details
                </a>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button class="flex-1 bg-[#4F46E5] text-white py-4 rounded-2xl font-bold text-sm shadow-lg shadow-indigo-100 hover:bg-[#4338CA] transition-all flex items-center justify-center gap-2">
                    <?= $renderAnalyticsIcon('plus', 'w-4 h-4') ?> Generate Report
                </button>
                <button class="px-5 py-4 bg-white border border-gray-200 text-gray-400 rounded-2xl hover:text-gray-600 transition-colors" title="Export">
                    <?= $renderAnalyticsIcon('export', 'w-5 h-5') ?>
                </button>
            </div>
        </div>
    </div>
</div>

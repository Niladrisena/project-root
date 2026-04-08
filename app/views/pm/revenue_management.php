<?php
    $sysCurrency = defined('SYS_CURRENCY') ? SYS_CURRENCY : '₹';
    $summary = $revenue_summary ?? [];
    $transactions = $revenue_transactions ?? [];
    $projects = $revenue_projects ?? [];
    $selectedMonth = $selected_month ?? date('Y-m');
    $selectedProjectId = $selected_project_id ?? null;
    $monthLabel = date('F Y', strtotime(($selectedMonth ?: date('Y-m')) . '-01'));

    $formatCurrency = function ($amount, $symbol = null) use ($sysCurrency) {
        $activeSymbol = $symbol ?: $sysCurrency;
        return $activeSymbol . number_format((float) $amount, 2);
    };

    $summaryCards = [
        [
            'label' => 'Total Revenue',
            'value' => $formatCurrency($summary['total_revenue'] ?? 0),
            'note' => 'Across invoices for selected projects',
            'iconBg' => 'bg-blue-50',
            'iconText' => 'text-blue-600',
            'accent' => 'text-blue-600'
        ],
        [
            'label' => 'Monthly Revenue',
            'value' => $formatCurrency($summary['monthly_revenue'] ?? 0),
            'note' => 'Booked in ' . $monthLabel,
            'iconBg' => 'bg-violet-50',
            'iconText' => 'text-violet-600',
            'accent' => 'text-violet-600'
        ],
        [
            'label' => 'Pending Payments',
            'value' => $formatCurrency($summary['pending_payments'] ?? 0),
            'note' => 'Awaiting collection',
            'iconBg' => 'bg-amber-50',
            'iconText' => 'text-amber-600',
            'accent' => 'text-amber-600'
        ],
        [
            'label' => 'Profit',
            'value' => $formatCurrency($summary['profit'] ?? 0),
            'note' => 'Realized from paid invoices',
            'iconBg' => 'bg-emerald-50',
            'iconText' => 'text-emerald-600',
            'accent' => 'text-emerald-600'
        ],
    ];

    $collectionSummary = [
        ['label' => 'Paid Invoices', 'value' => (int) ($summary['paid_invoices'] ?? 0), 'style' => 'text-emerald-700 bg-emerald-50 border-emerald-100'],
        ['label' => 'Pending Follow-up', 'value' => (int) ($summary['pending_invoices'] ?? 0), 'style' => 'text-amber-700 bg-amber-50 border-amber-100'],
        ['label' => 'Overdue Cases', 'value' => (int) ($summary['overdue_invoices'] ?? 0), 'style' => 'text-rose-700 bg-rose-50 border-rose-100'],
    ];

    $latestTransactionDate = !empty($summary['latest_transaction_date'])
        ? date('d M Y', strtotime($summary['latest_transaction_date']))
        : date('d M Y');
?>

<div class="max-w-7xl mx-auto space-y-6 pb-12">
    <section class="bg-white border border-gray-200 rounded-[28px] shadow-sm overflow-hidden">
        <div class="px-6 md:px-8 py-6 border-b border-gray-100 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.28em] text-indigo-600">Project Finance Workspace</p>
                <h1 class="mt-2 text-3xl font-black text-slate-900">Revenue Management</h1>
                <p class="mt-2 text-sm text-slate-500 max-w-2xl">Track collections, monitor project revenue, and review the billing status for your active delivery portfolio.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="<?= base_url('/finance/create_invoice') ?>" class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2.5 text-sm font-bold text-indigo-700 hover:bg-indigo-100 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Revenue
                </a>
                <a href="<?= base_url('/finance/create_invoice') ?>" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2z"></path></svg>
                    Generate Invoice
                </a>
                <a href="<?= base_url('/finance/export/csv') ?>" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16V4m0 12l-4-4m4 4l4-4M4 20h16"></path></svg>
                    Export
                </a>
            </div>
        </div>

        <form method="GET" action="<?= base_url('/pm/revenue_management') ?>" class="px-6 md:px-8 py-5 bg-gray-50/80 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex flex-col md:flex-row gap-3">
                <label class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8m-8 5h8m-8 5h5"></path></svg>
                    <input type="month" name="month" value="<?= sanitize($selectedMonth) ?>" class="bg-transparent focus:outline-none">
                </label>

                <label class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M6 11h12M9 15h6"></path></svg>
                    <select name="project_id" class="bg-transparent focus:outline-none">
                        <option value="">All Projects</option>
                        <?php foreach ($projects as $project): ?>
                            <option value="<?= (int) $project['id'] ?>" <?= ((int) ($project['id'] ?? 0) === (int) $selectedProjectId) ? 'selected' : '' ?>>
                                <?= sanitize($project['name'] ?? 'Project #' . ($project['id'] ?? '')) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white hover:bg-slate-800 transition">
                    Apply Filters
                </button>
            </div>

            <div class="text-sm text-slate-400 font-medium">
                Snapshot updated for <?= sanitize($latestTransactionDate) ?>
            </div>
        </form>

        <div class="p-6 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                <?php foreach ($summaryCards as $card): ?>
                    <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl <?= $card['iconBg'] ?> <?= $card['iconText'] ?> flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M20 12a8 8 0 11-16 0 8 8 0 0116 0z"></path></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-500"><?= $card['label'] ?></p>
                                <p class="mt-1 text-3xl font-black text-slate-900 break-words"><?= $card['value'] ?></p>
                                <p class="mt-2 text-xs font-medium <?= $card['accent'] ?>"><?= $card['note'] ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 rounded-3xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-black text-slate-900">Recent Transactions</h2>
                            <p class="text-sm text-slate-500 mt-1">Latest invoice collections and client payment statuses for your assigned projects.</p>
                        </div>
                        <a href="<?= base_url('/finance') ?>" class="text-sm font-bold text-indigo-600 hover:text-indigo-700">View All</a>
                    </div>

                    <?php if (empty($transactions)): ?>
                        <div class="px-6 py-14 text-center">
                            <div class="mx-auto w-16 h-16 rounded-full bg-slate-50 border border-dashed border-slate-200 flex items-center justify-center text-slate-400">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="mt-4 text-lg font-black text-slate-900">No revenue records yet</h3>
                            <p class="mt-2 text-sm text-slate-500">Create an invoice for one of your projects and it will start showing up here.</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr class="text-left text-xs font-bold uppercase tracking-[0.24em] text-slate-400">
                                        <th class="px-6 py-4">Date</th>
                                        <th class="px-6 py-4">Project Name</th>
                                        <th class="px-6 py-4">Client</th>
                                        <th class="px-6 py-4">Amount</th>
                                        <th class="px-6 py-4 text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php foreach ($transactions as $transaction): ?>
                                        <?php
                                            $rawStatus = strtolower((string) ($transaction['status'] ?? 'draft'));
                                            $dueDate = !empty($transaction['due_date']) ? strtotime($transaction['due_date']) : null;
                                            $effectiveStatus = ($rawStatus !== 'paid' && $dueDate && $dueDate < strtotime(date('Y-m-d'))) ? 'overdue' : $rawStatus;
                                            $statusStyle = 'bg-slate-100 text-slate-700';
                                            $amountStyle = 'bg-slate-100 text-slate-700';

                                            if ($effectiveStatus === 'paid') {
                                                $statusStyle = 'bg-emerald-100 text-emerald-800';
                                                $amountStyle = 'bg-emerald-100 text-emerald-800';
                                            } elseif ($effectiveStatus === 'overdue') {
                                                $statusStyle = 'bg-rose-100 text-rose-700';
                                                $amountStyle = 'bg-rose-100 text-rose-700';
                                            } else {
                                                $statusStyle = 'bg-amber-100 text-amber-800';
                                                $amountStyle = 'bg-amber-100 text-amber-800';
                                            }
                                        ?>
                                        <tr class="hover:bg-gray-50/70 transition">
                                            <td class="px-6 py-4 text-sm font-semibold text-slate-500 whitespace-nowrap"><?= date('d M', strtotime($transaction['created_at'] ?? 'now')) ?></td>
                                            <td class="px-6 py-4 text-sm font-bold text-slate-900 whitespace-nowrap"><?= sanitize($transaction['project_name'] ?? 'Untitled Project') ?></td>
                                            <td class="px-6 py-4 text-sm text-slate-600 whitespace-nowrap"><?= sanitize($transaction['client_name'] ?? 'Client not mapped') ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex rounded-full px-3 py-1 text-sm font-bold <?= $amountStyle ?>">
                                                    <?= $formatCurrency($transaction['amount'] ?? 0, $transaction['currency_symbol'] ?? $sysCurrency) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                                <span class="inline-flex rounded-full px-3 py-1 text-sm font-bold <?= $statusStyle ?>">
                                                    <?= sanitize(ucfirst($effectiveStatus)) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="space-y-6">
                    <div class="rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-900 p-6 text-white shadow-sm overflow-hidden relative">
                        <div class="absolute -right-8 -top-8 w-28 h-28 rounded-full bg-white/10 blur-2xl"></div>
                        <div class="relative">
                            <p class="text-xs font-bold uppercase tracking-[0.28em] text-indigo-200">Collection Health</p>
                            <h3 class="mt-3 text-2xl font-black"><?= (int) ($summary['collection_health'] ?? 0) ?>% realized this cycle</h3>
                            <p class="mt-2 text-sm text-slate-200">This score compares cleared invoice value against total invoice value for the selected revenue set.</p>
                        </div>

                        <div class="mt-6 space-y-3 relative">
                            <?php foreach ($collectionSummary as $item): ?>
                                <div class="rounded-2xl border px-4 py-3 <?= $item['style'] ?>">
                                    <p class="text-xs font-bold uppercase tracking-[0.22em]"><?= $item['label'] ?></p>
                                    <p class="mt-1 text-2xl font-black"><?= $item['value'] ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-black text-slate-900">Revenue Notes</h3>
                        <div class="mt-4 space-y-4">
                            <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-4">
                                <p class="text-sm font-bold text-emerald-800">Paid invoices are counted as realized revenue</p>
                                <p class="mt-1 text-sm text-emerald-700">Use this to quickly understand how much value is already collected for your projects.</p>
                            </div>
                            <div class="rounded-2xl bg-amber-50 border border-amber-100 p-4">
                                <p class="text-sm font-bold text-amber-800">Pending items need billing follow-up</p>
                                <p class="mt-1 text-sm text-amber-700">Invoices still in draft or sent status contribute to pending payments until cleared.</p>
                            </div>
                            <div class="rounded-2xl bg-rose-50 border border-rose-100 p-4">
                                <p class="text-sm font-bold text-rose-800">Overdue is auto-detected from due date</p>
                                <p class="mt-1 text-sm text-rose-700">Any unpaid invoice past its due date is surfaced here as an overdue collection risk.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

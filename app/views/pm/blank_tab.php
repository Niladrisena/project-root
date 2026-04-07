<div class="max-w-5xl mx-auto">
    <div class="bg-white border border-gray-200 rounded-3xl shadow-sm p-8 md:p-10">
        <div class="flex items-start justify-between gap-6">
            <div>
                <p class="text-xs font-bold tracking-[0.3em] text-indigo-600 uppercase">Project Manager Workspace</p>
                <h1 class="mt-3 text-3xl font-black text-slate-900"><?= sanitize($page_title ?? 'Blank Tab') ?></h1>
                <p class="mt-3 text-sm text-slate-500 max-w-2xl">
                    <?= sanitize($page_description ?? 'This tab is currently blank and ready for future content.') ?>
                </p>
            </div>
            <div class="hidden md:flex w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 items-center justify-center border border-indigo-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v12m6-6H6"></path>
                </svg>
            </div>
        </div>

        <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-5">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Status</p>
                <p class="mt-2 text-lg font-semibold text-slate-800">Blank tab created</p>
            </div>
            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-5">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Owner</p>
                <p class="mt-2 text-lg font-semibold text-slate-800">Project Management</p>
            </div>
            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-5">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Next Step</p>
                <p class="mt-2 text-lg font-semibold text-slate-800">Ready for module content</p>
            </div>
        </div>
    </div>
</div>

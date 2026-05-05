<x-layouts::admin :title="__('Admin Projects')" :heading="__('Projects')">
    <section class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-base font-semibold text-zinc-900">Project Library</h2>
                <p class="mt-1 text-sm text-zinc-600">
                    Browse and review all projects in the catalog.
                </p>
            </div>
        </div>

        <div class="mt-6">
            <livewire:admin.projects-table lazy/>
        </div>
    </section>
</x-layouts::admin>

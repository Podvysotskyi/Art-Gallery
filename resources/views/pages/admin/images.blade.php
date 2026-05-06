<x-layouts::admin :title="__('Admin Images')" :heading="__('Images')">
    <section class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-base font-semibold text-zinc-900">Image Library</h2>
                <p class="mt-1 text-sm text-zinc-600">
                    Manage uploaded artworks and collection photos.
                </p>
            </div>
            <livewire:admin.images.create/>
        </div>

        <div class="mt-6">
            <livewire:admin.images.table lazy/>
        </div>
    </section>
</x-layouts::admin>

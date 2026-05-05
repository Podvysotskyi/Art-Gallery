<?php

use App\Models\Project;
use Illuminate\Pagination\Paginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    #[Computed]
    public function projects(): Paginator
    {
        return Project::query()
            ->latest()
            ->simplePaginate(15);
    }
};
?>

@placeholder
<div class="overflow-hidden rounded-md border border-zinc-200">
    <table class="min-w-full divide-y divide-zinc-200">
        <thead class="bg-zinc-50">
        <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">Title</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">Status</th>
            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-600">Created</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-zinc-200 bg-white">
        <tr>
            <td colspan="3" class="px-4 py-10 text-center text-sm text-zinc-500">Loading projects...</td>
        </tr>
        </tbody>
    </table>
</div>
@endplaceholder

<div>
    <div class="overflow-hidden rounded-md border border-zinc-200">
        <table class="min-w-full divide-y divide-zinc-200">
            <thead class="bg-zinc-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">Title</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">Status</th>
                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-600">Created
                </th>
            </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white">
            @forelse($this->projects as $project)
                <tr>
                    <td class="px-4 py-3 text-sm text-zinc-800">{{ $project->title }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-600">{{ $project->hide ? 'Hidden' : 'Published' }}</td>
                    <td class="px-4 py-3 text-right text-sm text-zinc-600">{{ $project->created_at->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-10 text-center text-sm text-zinc-500">
                        No projects found.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $this->projects->links() }}
    </div>
</div>

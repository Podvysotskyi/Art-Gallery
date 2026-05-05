@props(['name'])

<button
    type="button"
    role="tab"
    name="{{ $name }}"
    @click="selectedTab = '{{ $name }}'"
    :class="selectedTab === '{{ $name }}' ? 'border-zinc-900 text-zinc-900' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300'"
    {{ $attributes->merge(['class' => 'whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200']) }}
>
    {{ $slot }}
</button>

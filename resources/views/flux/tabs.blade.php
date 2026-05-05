@props(['wire' => null])

<div {{ $attributes->merge(['class' => 'flex border-b border-zinc-200 gap-4']) }} role="tablist">
    {{ $slot }}
</div>

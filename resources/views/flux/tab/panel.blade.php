@props(['name'])

<div
    x-show="selectedTab === '{{ $name }}'"
    role="tabpanel"
    {{ $attributes }}
>
    {{ $slot }}
</div>

@props(['variant' => 'horizontal', 'wire' => null])

<div {{ $attributes->merge(['class' => 'space-y-4']) }} x-data="{
    selectedTab: {{ $wire ? '$wire.entangle(\''.$wire.'\')' : 'null' }},
    init() {
        if (! this.selectedTab) {
            this.selectedTab = this.$el.querySelector('[role=tab]')?.getAttribute('name');
        }
    }
}">
    {{ $slot }}
</div>

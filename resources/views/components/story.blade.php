<div>
    <div class="mt-4 flex justify-center">
        <span class="text-2xl font-bold">
            {{ data_get($story, 'title') }}
        </span>
    </div>

    @if (filled(data_get($story, 'subtitle')))
        <div class="mt-2 flex justify-center">
            <span class="text-sm text-gray-600">
                {{ data_get($story, 'subtitle') }}
            </span>
        </div>
    @endif

    @if (filled(data_get($story, 'description')))
        <div class="my-4 flex justify-center">
            <span class="text-sm font-thin">
                {{ data_get($story, 'description') }}
            </span>
        </div>
    @endif

    @if (collect(data_get($story, 'images', []))->isNotEmpty())
        <div class="my-2 grid grid-flow-row grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach (data_get($story, 'images', []) as $image)
                <x-image :image="$image"/>
            @endforeach
        </div>
    @endif
</div>

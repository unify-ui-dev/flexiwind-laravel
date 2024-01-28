@props([
    'description' => $description
])
<div data-alert {{ $attributes->merge(['bg-gray-100 dark:bg-gray-900 rounded-md p-4']) }}>
    @if($description)
        <p class="text-gray-700 dark:text-gray-300">
            {{ $description }}
        </p>
    @endif

</div>

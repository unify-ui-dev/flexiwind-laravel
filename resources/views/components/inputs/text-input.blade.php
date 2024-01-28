@props([
    'name' => $name,
    'label' => $getLabel(),
    'type' => $getType(),
    'placeholder' => $getPlaceholder(),
])

@if($label)
    <label for="{{ $label }}">{{ $label }}</label>
@endif
<input
    type="{{ $type }}"
    name="{{ $label }}"
    id="{{ $label }}"
    value="{{ old($name) }}"
    {{ $attributes->merge(['class' => 'form-control']) }}
    placeholder="{{ $placeholder }}"
>


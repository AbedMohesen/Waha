@props(['value'])

<label {{ $attributes->merge(['class' => 'oasis-label']) }}>
    {{ $value ?? $slot }}
</label>

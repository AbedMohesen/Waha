<button {{ $attributes->merge(['type' => 'submit', 'class' => 'oasis-button oasis-button-primary']) }}>
    {{ $slot }}
</button>

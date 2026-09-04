<button {{ $attributes->merge(['type' => 'submit', 'class' => 'oasis-button oasis-button-danger']) }}>
    {{ $slot }}
</button>

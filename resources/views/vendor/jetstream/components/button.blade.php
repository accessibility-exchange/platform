<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-black rounded-sm font-semibold text-black disabled:opacity-25']) }}>
    {{ $slot }}
</button>

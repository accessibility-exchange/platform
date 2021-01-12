<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-red-700 border border-red-700 rounded-sm font-semibold text-white disabled:opacity-25']) }}>
    {{ $slot }}
</button>

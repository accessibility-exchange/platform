<div class="flex flex-col items-center min-h-screen pt-6 bg-gray-100 sm:justify-center sm:pt-0">
    <div>
        {{ $logo }}
    </div>

    <div class="w-full px-6 py-4 mt-6 overflow-hidden bg-white border border-black sm:max-w-md sm:rounded-lg">
        {{ $slot }}
    </div>

    <div class="w-full px-6 mt-6 text-left sm:max-w-md">
        <a href="{{ url('/') }}"><span class="aria-hidden">&larr;</span> Back to Home</a>
    </div>
</div>

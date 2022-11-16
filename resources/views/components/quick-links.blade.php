<div class="border-divider stack mb-12 border-x-0 border-t-3 border-b-0 border-solid pt-6 md:mt-14">
    <div class="flex items-center gap-5">
        @svg('heroicon-o-lightning-bolt', 'icon--2xl icon--blue')
        <h2 class="mt-0">{{ __('Quick links') }}</h2>
    </div>
    <ul class="link-list" role="list">
        {{ $slot }}
    </ul>
</div>

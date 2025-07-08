<div class="with-sidebar with-sidebar:2/3">
    <x-quick-links>
        <li>
            <ul class="link-list" role="list">
                <x-expander :summary="__('Analytics')" level="3">
                    <li>
                        <a href="{{ route('filament.admin.pages.activity') }}">{{ __('Activity') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('filament.admin.pages.downloads') }}">{{ __('Downloads') }}</a>
                    </li>
                </x-expander>
            </ul>
        </li>
        <li>
            <ul class="link-list" role="list">
                <x-expander :summary="__('Manage')" level="3">
                    <li>
                        <a href="{{ localized_route('admin.manage-accounts') }}">{{ __('Manage accounts') }}</a>
                    </li>
                    <li>
                        <a
                            href="{{ localized_route('admin.estimates-and-agreements') }}">{{ __('Estimates and agreements') }}</a>
                    </li>
                </x-expander>
            </ul>
        </li>
        <li>
            <ul class="link-list" role="list">
                <x-expander :summary="__('Metadata')" level="3">
                    <li>
                        <a
                            href="{{ route('filament.admin.resources.access-supports.index') }}">{{ __('Access supports') }}</a>
                    </li>
                    <li>
                        <a
                            href="{{ route('filament.admin.resources.impacts.index') }}">{{ __('Areas of accessibility planning') }}</a>
                    </li>
                    <li>
                        <a
                            href="{{ route('filament.admin.resources.content-types.index') }}">{{ __('Content types') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('filament.admin.resources.identities.index') }}">{{ __('Identities') }}</a>
                    </li>
                    <li>
                        <a
                            href="{{ route('filament.admin.resources.payment-types.index') }}">{{ __('Payment types') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('filament.admin.resources.sectors.index') }}">{{ __('Sectors') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('filament.admin.resources.topics.index') }}">{{ __('Topics') }}</a>
                    </li>
                    <li>
                        <a
                            href="{{ route('filament.admin.resources.languages.index') }}">{{ __('User languages') }}</a>
                    </li>
                </x-expander>
            </ul>
        </li>
        <li>
            <ul class="link-list" role="list">
                <x-expander :summary="__('Pages, resources and training')" level="3">
                    <li>
                        <a href="{{ route('filament.admin.resources.pages.index') }}">{{ __('Pages') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('filament.admin.resources.libraries.index') }}">{{ __('Libraries') }}</a>
                    </li>
                    <li>
                        <a
                            href="{{ route('filament.admin.resources.resource-collections.index') }}">{{ __('Resource collections') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('filament.admin.resources.resources.index') }}">{{ __('Resources') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('filament.admin.resources.tools.index') }}">{{ __('Tools') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('filament.admin.resources.documents.index') }}">{{ __('Documents') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('filament.admin.resources.revisions.index') }}">{{ __('Revisions') }}</a>
                    </li>
                </x-expander>
            </ul>
        </li>
        <li>
            <ul class="link-list" role="list">
                <x-expander :summary="__('Settings')" level="3">

                    <li>
                        <a
                            href="{{ route('filament.admin.resources.interpretations.index') }}">{{ __('Sign language interpretations') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('filament.admin.resources.videos.index') }}">{{ __('Videos') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('filament.admin.pages.settings') }}">{{ __('Website settings') }}</a>
                    </li>
                </x-expander>
            </ul>
        </li>
    </x-quick-links>
    <div class="border-divider mb-12 border-x-0 border-b-0 border-t-3 border-solid pt-6 md:mt-14">
        @include('dashboard.partials.notifications', [
            'notifications' => $user->allUnreadNotifications(),
        ])
    </div>
</div>

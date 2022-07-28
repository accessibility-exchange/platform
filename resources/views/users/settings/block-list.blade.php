<x-app-wide-layout>
    <x-slot name="title">{{ __('Blocked individuals and organizations') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
            <li><a href="{{ localized_route('settings.show') }}">{{ __('Settings') }}</a></li>
        </ol>
        <h1>
            {{ __('Blocked individuals and organizations') }}
        </h1>
    </x-slot>

    <p>{{ __('When you block someone, you will not be able to:') }}</p>
    <ul>
        <li>{{ __('access their page') }}</li>
        <li>{{ __('access their projects or engagements') }}</li>
        <li>{{ __('show up on search results for them') }}</li>
        <li>{{ __('receive communication from them') }}</li>
    </ul>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <h2 id="regulated-organizations">{{ __('Regulated organizations') }}</h2>

    <div role="region" aria-labelledby="regulated-organizations" tabindex="0">
        <table>
            <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th></th>
            </tr>
            </thead>
            @forelse (Auth::user()->blockedRegulatedOrganizations as $blockable)
                <tr>
                    <td>{{ $blockable->name }}</td>
                    <td>
                        <form action="{{ localized_route('block-list.unblock') }}" method="POST">
                            @csrf
                            <x-hearth-input type="hidden" name="blockable_type" :value="get_class($blockable)" />
                            <x-hearth-input type="hidden" name="blockable_id" :value="$blockable->id" />
                            <button class="secondary" :aria-label="__('Unblock :blockable', ['blockable' => $blockable->name])">
                                {{ __('Unblock') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td>{{ __('None found.') }}</td>
                    <td></td>
                </tr>
            @endforelse
        </table>
    </div>

    <h2 id="community-organizations">{{ __('Community organizations') }}</h2>

    <div role="region" aria-labelledby="community-organizations" tabindex="0">
        <table>
            <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th></th>
            </tr>
            </thead>
            @forelse (Auth::user()->blockedOrganizations as $blockable)
                <tr>
                    <td>{{ $blockable->name }}</td>
                    <td>
                        <form action="{{ localized_route('block-list.unblock') }}" method="POST">
                            @csrf
                            <x-hearth-input type="hidden" name="blockable_type" :value="get_class($blockable)" />
                            <x-hearth-input type="hidden" name="blockable_id" :value="$blockable->id" />
                            <button class="secondary" aria-label="{{ __('Unblock :blockable', ['blockable' => $blockable->name]) }}">
                                {{ __('Unblock') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td>{{ __('None found.') }}</td>
                    <td></td>
                </tr>
            @endforelse
        </table>
    </div>

    <h2 id="individuals">{{ __('Individuals') }}</h2>

    <div role="region" aria-labelledby="individuals" tabindex="0">
        <table>
            <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th></th>
            </tr>
            </thead>
            @forelse (Auth::user()->blockedIndividuals as $blockable)
                <tr>
                    <td>{{ $blockable->name }}</td>
                    <td>
                        <form action="{{ localized_route('block-list.unblock') }}" method="POST">
                            @csrf
                            <x-hearth-input type="hidden" name="blockable_type" :value="get_class($blockable)" />
                            <x-hearth-input type="hidden" name="blockable_id" :value="$blockable->id" />
                            <button class="secondary" :aria-label="__('Unblock :blockable', ['blockable' => $blockable->name])">
                                {{ __('Unblock') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td>{{ __('None found.') }}</td>
                    <td></td>
                </tr>
            @endforelse
        </table>
    </div>

</x-app-wide-layout>

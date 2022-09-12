<x-app-layout>
    <x-slot name="title">{{ __('Areas of interest') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
            <li><a href="{{ localized_route('settings.show') }}">{{ __('Settings') }}</a></li>
        </ol>
        <h1>
            {{ __('Areas of interest') }}
        </h1>
        <p>
            {{ __('This information will be used to match you to Regulated Organizations that you are interested in.') }}
        </p>
    </x-slot>

    @include('partials.validation-errors')

    <form class="stack" action="{{ localized_route('settings.update-areas-of-interest') }}" method="post">
        @csrf
        @method('put')

        <fieldset class="field @error('sectors') field--error @enderror">
            <legend>{{ __('What types of Regulated Organizations are you interested in? (optional)') }}</legend>
            <x-hearth-checkboxes name="sectors" :options="$sectors" :checked="old('sectors', $individual->sectorsOfInterest->pluck('id')->toArray())" />
        </fieldset>

        <fieldset class="field @error('impacts') field--error @enderror">
            <legend>{{ __('What areas would you most like to impact within a Regulated Organization? (optional)') }}
            </legend>
            <x-hearth-hint for="impacts">
                {{ __('These are the seven areas listed within the Accessible Canada Act. By law, entities must ensure these areas are accessible.') }}
            </x-hearth-hint>
            <x-hearth-checkboxes name="impacts" :options="$impacts" :checked="old('impacts', $individual->impactsOfInterest->pluck('id')->toArray())" hinted />
        </fieldset>

        <button>{{ __('Save') }}</button>
    </form>

</x-app-layout>

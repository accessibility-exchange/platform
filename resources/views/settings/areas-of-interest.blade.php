<x-app-layout>
    <x-slot name="title">{{ __('Areas of accessibility you are interested in') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('dashboard') }}">{{ __('My dashboard') }}</a></li>
            <li><a href="{{ localized_route('settings.show') }}">{{ __('Settings') }}</a></li>
        </ol>
        <h1>
            {{ __('Areas of accessibility you are interested in') }}
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
            <legend>
                {{ __('What types of Regulated Organization are you interested consulting with on accessibility planning and design?') . ' ' . __('(optional)') }}
            </legend>
            <x-interpretation
                name="{{ __('What types of Regulated Organization are you interested consulting with on accessibility planning and design?', [], 'en') }}"
                namespace="regulated_organization_sectors_you_are_interested_working-optional" />
            <x-hearth-checkboxes name="sectors" :options="$sectors" :checked="old('sectors', $individual->sectorsOfInterest->pluck('id')->toArray())" />
        </fieldset>

        <fieldset class="field @error('impacts') field--error @enderror">
            <legend>
                {{ __('What areas of accessibility planning and design are you most interested in consulting on?') . ' ' . __('(optional)') }}
            </legend>
            <x-interpretation
                name="{{ __('What areas of accessibility planning and design are you most interested in consulting on?', [], 'en') }}"
                namespace="areas_of_accessible_canada_act_you_are_interested_working-optional" />
            <x-hearth-hint for="impacts">
                {{ __('These are the seven areas listed within the Accessible Canada Act. By law, entities must ensure these areas are accessible.') }}
            </x-hearth-hint>
            <x-hearth-checkboxes name="impacts" :options="$impacts" :checked="old('impacts', $individual->impactsOfInterest->pluck('id')->toArray())" hinted />
        </fieldset>

        <button>{{ __('Save') }}</button>
    </form>

</x-app-layout>

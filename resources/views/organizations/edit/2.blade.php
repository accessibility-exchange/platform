<form class="stack" action="{{ localized_route('organizations.update-constituencies', $organization) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <div class="with-sidebar with-sidebar:last">

        @include('organizations.partials.progress')

        <div class="stack">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step') ?? 1, 'total' => 4]) }}<br />
                @if($organization->type === 'representative')
                    {{ __('Groups your organization represents') }}
                @else
                    {{ __('Groups your organization serves or supports') }}
                @endif
            </h2>

            <p class="repel">
                <button class="secondary" name="save_and_previous" value="1">{{ __('Save and back') }}</button>
                <button name="save" value="1">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>
            <h3>
                @if($organization->type === 'representative')
                    {{ __('What groups does your organization represent? Please tell us your primary constituencies.') }}
                @else
                    {{ __('What groups does your organization serve and support? Please tell us your primary constituencies.') }}
                @endif
            </h3>

            <fieldset class="field @error('lived_experiences') field--error @enderror">
                <legend>
                @if($organization->type === 'representative')
                    {{ __('Do you represent people with disabilities, Deaf persons, and/or their supporters? (required)') }}
                @else
                    {{ __('Do you support or serve people with disabilities, Deaf persons, and/or their supporters? (required)') }}
                @endif
                </legend>
                <x-hearth-hint for="lived_experiences">{{ __('Please check all that apply.') }}</x-hearth-hint>
                <x-hearth-checkboxes name="lived_experiences" :options="$livedExperiences" :checked="old('lived_experiences', [])" hinted="lived_experiences-hint" required />
            </fieldset>

            <p class="repel">
                <button class="secondary" name="save_and_previous" value="1">{{ __('Save and back') }}</button>
                <button name="save" value="1">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>
        </div>
    </div>
</form>

<form action="{{ localized_route('individuals.update-experiences', $individual) }}" method="POST"
    enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <div class="with-sidebar with-sidebar:last">
        @include('individuals.partials.progress')

        <div class="stack">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step') ?? 1, 'total' => $individual->isConnector() ? 5 : 4]) }}<br />
                {{ __('Experiences') }}
            </h2>
            <x-interpretation class="interpretation--start" name="{{ __('Experiences', [], 'en') }}" />
            <hr class="divider--thick">
            <fieldset>
                <legend>{{ __('Lived experience') }}</legend>

                <div class="field @error('lived_experience') field--error @enderror">
                    <x-translatable-textarea name="lived_experience" :model="$individual" hinted="lived_experience-hint"
                        :label="__(
                            'What are your lived experiences of disability or other intersectional identities?',
                        ) .
                            ' ' .
                            __('(optional)')" :shortLabel="__('lived experiences')" :hint="__(
                            'Feel free to self-identify your experiences of disability, if you feel it is relevant to your work.',
                        )"
                        interpretationName="What are your lived experiences of disability or other intersectional identities?"
                        interpretationNameSpace="your_lived_experience_disability_intersectional-optional" />
                    <x-hearth-error for="lived_experience" />
                </div>

                {{-- Upload a file --}}
            </fieldset>

            <hr class="divider--thick">

            <fieldset>
                <legend>{{ __('Skills and strengths') }}</legend>

                <div class="field @error('skills_and_strengths') field--error @enderror">
                    <x-translatable-textarea name="skills_and_strengths" :model="$individual" :label="__('What are your skills and strengths relevant to The Accessibility Exchange?') .
                        ' ' .
                        __('(optional)')"
                        :shortLabel="__('skills and strengths')"
                        interpretationName="What are your skills and strengths relevant to The Accessibility Exchange?"
                        interpretationNameSpace="your_skills_and_strengths-optional" />
                    <x-hearth-error for="skills_and_strengths" />
                </div>

                {{-- Upload a file --}}
            </fieldset>

            <fieldset class="stack">
                <legend>{{ __('Relevant experiences') }} {{ __('(optional)') }}</legend>
                <x-hearth-hint for="relevant_experiences">{{ __('This can be paid or volunteer work.') }}
                </x-hearth-hint>
                <x-interpretation class="interpretation--start"
                    name="{{ __('Relevant experiences (optional)', [], 'en') }}"
                    namespace="relevant_experiences-optional" />
                <livewire:experiences name="relevant_experiences" :experiences="$individual->relevant_experiences ?? []" />
            </fieldset>

            <hr class="divider--thick">

            <p class="flex flex-wrap gap-7">
                @if (locale() === 'asl' || locale() === 'lsq')
                    <div>
                        <button class="secondary" name="save_and_previous"
                            value="1">{{ __('Save and previous') }}</button>
                        <x-interpretation class="interpretation--start" name="{{ __('Save and previous', [], 'en') }}"
                            namespace="save_previous" />
                    </div>
                    <div>
                        <button class="secondary" name="save" value="1">{{ __('Save') }}</button>
                        <x-interpretation class="interpretation--start" name="{{ __('Save', [], 'en') }}"
                            namespace="save" />
                    </div>
                    <div>
                        <button name="save_and_next" value="1">{{ __('Save and next') }}</button>
                        <x-interpretation class="interpretation--start" name="{{ __('Save and next', [], 'en') }}"
                            namespace="save_next" />
                    </div>
                @else
                    <button class="secondary" name="save_and_previous"
                        value="1">{{ __('Save and previous') }}</button>
                    <button class="secondary" name="save" value="1">{{ __('Save') }}</button>
                    <button name="save_and_next" value="1">{{ __('Save and next') }}</button>
                @endif
            </p>
        </div>
    </div>
</form>

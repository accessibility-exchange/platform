<form action="{{ localized_route('community-members.update-experiences', $communityMember) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <div class="with-sidebar with-sidebar:last">
        @include('community-members.partials.progress')

        <div class="stack">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step'), 'total' => 4]) }}<br />
                {{ __('Experiences') }}
            </h2>

            <p class="repel">
                <x-hearth-input class="secondary" type="submit" name="save_and_previous" :value="__('Save and previous')" />
                <x-hearth-input type="submit" name="save" :value="__('Save')" />
                <x-hearth-input class="secondary" type="submit" name="save_and_next" :value="__('Save and next')" />
            </p>

            <fieldset>
                <legend>{{ __('Lived experience') }}</legend>

                <div class="field @error('lived_experience') field--error @enderror">
                    <x-translatable-textarea name="lived_experience" :model="$communityMember" hinted="lived_experience-hint" :label="__('What are your lived experiences of disability or other intersectional identities? (optional)')" />
                    <x-hearth-hint for="lived_experience">{{ __('Feel free to self-identify your experiences of disability, if you feel it is relevant to your work.') }}</x-hearth-hint>
                    <x-hearth-error for="lived_experience" />
                </div>

                {{-- Upload a file --}}
            </fieldset>

            <fieldset>
                <legend>{{ __('Skills and strengths') }}</legend>

                <div class="field @error('skills_and_strengths') field--error @enderror">
                    <x-translatable-textarea name="skills_and_strengths" :model="$communityMember" :label="__('What are your skills and strengths? (optional)')" />
                    <x-hearth-error for="skills_and_strengths" />
                </div>

                {{-- Upload a file --}}
            </fieldset>

            <fieldset class="stack">
                <legend>{{ __('Relevant experiences') }}</legend>
                <x-hearth-hint for="relevant_experiences">{{ __('This can be paid or volunteer work.') }}</x-hearth-hint>
                <livewire:experiences name="relevant_experiences" :experiences="$communityMember->relevant_experiences ?? [['title' => '', 'start_year' => '', 'end_year' => '', 'current' => false]]" />
            </fieldset>

            <p class="repel">
                <x-hearth-input class="secondary" type="submit" name="save_and_previous" :value="__('Save and previous')" />
                <x-hearth-input type="submit" name="save" :value="__('Save')" />
                <x-hearth-input class="secondary" type="submit" name="save_and_next" :value="__('Save and next')" />
            </p>
        </div>
    </div>
</form>

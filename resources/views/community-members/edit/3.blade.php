<h2>
    {{ __('Step :current of :total', ['current' => request()->get('step'), 'total' => 5]) }}<br />
    {{ __('Experiences') }}
</h2>

@include('community-members.partials.progress')

<form action="{{ localized_route('community-members.update-experiences', $communityMember) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <h2>{{ __('Experiences') }}</h2>

    <p>
        <x-hearth-input type="submit" name="save_and_previous" :value="__('Save and previous')" />
        <x-hearth-input type="submit" name="save" :value="__('Save')" />
        <x-hearth-input type="submit" name="save_and_next" :value="__('Save and next')" />
    </p>

    <h3>{{ __('Lived experiences') }}</h3>

    <p>{{ __('Entities may look for people with specific lived experiences. They may also try to consult with people across different disabilities and identities. Any information you provide will be used to help match you to an entity looking for someone like you.') }}</p>

    <x-privacy-indicator level="private" :value="__('Only organizations who work with you will be able to access this information.')" />

    <fieldset class="field @error('lived_experiences') field--error @enderror">
        <legend>{{ __('Which of these describe your lived experience? (optional)') }}</legend>
        <x-hearth-checkboxes name="lived_experiences" :options="$livedExperiences" :selected="old('lived_experiences', $communityMember->livedExperiences->pluck('id')->toArray())" />
        <x-hearth-error for="lived_experiences" />
    </fieldset>

    <fieldset class="field @error('age_group') field--error @enderror">
        <legend>{{ __('What is your age group (optional)') }}</legend>
        <x-hearth-radio-buttons name="age_group" :options="$ageGroups" :selected="old('age_group', $communityMember->age_group)" />
        <x-hearth-error for="age_group" />
    </fieldset>

    <fieldset class="field @error('living_situation') field--error @enderror">
        <legend>{{ __('How would you describe where you live? (optional)') }}</legend>
        <x-hearth-radio-buttons name="living_situation" :options="$livingSituations" :selected="old('living_situation', $communityMember->living_situation)" />
        <x-hearth-error for="living_situation" />
    </fieldset>

    <fieldset>
        <div class="field @error('lived_experience') field--error @enderror">
            <x-hearth-label for="lived_experience" :value="__('Do you want to share anything else about your lived experience? (optional)')" />
            <x-hearth-textarea name="lived_experience" hinted>{{ old('lived_experience', $communityMember->lived_experience) }}</x-hearth-textarea>
            <x-hearth-error for="lived_experience" />
        </div>
    </fieldset>

    {{-- Upload a file --}}

    <h3>{{ __('Skills and strengths') }}</h3>

    <x-privacy-indicator level="public" :value="__('Any member of the website can find this information.')" />

    <fieldset>
        <div class="field @error('skills_and_strengths') field--error @enderror">
            <x-hearth-label for="skills_and_strengths" :value="__('What are your skills and strengths? (optional)')" />
            <x-hearth-hint for="skills_and_strengths">{{ __('Feel free to list your skills and strengths, and say more about them.') }}</x-hearth-hint>
            <x-hearth-textarea name="skills_and_strengths" hinted>{{ old('skills_and_strengths', $communityMember->skills_and_strengths) }}</x-hearth-textarea>
            <x-hearth-error for="skills_and_strengths" />
        </div>
    </fieldset>

    {{-- Upload a file --}}



    <fieldset class="flow">
        <legend>{{ __('Work and volunteer experiences (optional)') }}</legend>
        <x-privacy-indicator level="public" :value="__('Any member of the website can find this information.')" />
        <livewire:work-and-volunteer-experiences :experiences="$communityMember->work_and_volunteer_experiences ?? []" />
    </fieldset>

    <p>
        <x-hearth-input type="submit" name="save_and_previous" :value="__('Save and previous')" />
        <x-hearth-input type="submit" name="save" :value="__('Save')" />
        <x-hearth-input type="submit" name="save_and_next" :value="__('Save and next')" />
    </p>
</form>

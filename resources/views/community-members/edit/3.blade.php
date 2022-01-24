<h2>
    {{ __('Step :current of :total', ['current' => request()->get('step'), 'total' => 5]) }}<br />
    {{ __('Experiences') }}
</h2>

@include('community-members.partials.progress')

<form action="{{ localized_route('community-members.update-experiences', $communityMember) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <h2>{{ __('Experiences') }}</h2>

    <x-privacy-indicator level="private" :value="__('Only the team matching you to a project can access this information.')" />

    <fieldset class="field @error('lived_experiences') field--error @enderror">
        <legend>{{ __('Which of these describe your lived experience? (optional)') }}</legend>
        <x-hearth-hint for="lived_experiences">{{ __('We collect this information to make sure there is cross-disability, intersectional representation on our projects. Sometimes, regulated entities also look for consultants who have a particular lived experience.') }}</x-hearth-hint>
        <x-hearth-checkboxes name="lived_experiences" :options="$livedExperiences" :selected="old('lived_experiences', $communityMember->livedExperiences->pluck('id')->toArray())" />
        <x-hearth-error for="lived_experiences" />
    </fieldset>

    <fieldset class="field @error('age_group') field--error @enderror">
        <legend>{{ __('What is your age group') }}</legend>
        <x-hearth-hint for="age_group">{{ __('This will help us make sure there is representation from all ages on our projects.') }}</x-hearth-hint>
        <x-hearth-radio-buttons name="age_group" :options="$ageGroups" :selected="old('age_group', $communityMember->age_group)" />
        <x-hearth-error for="age_group" />
    </fieldset>

    <fieldset>
        <div class="field @error('lived_experience') field--error @enderror">
            <x-hearth-label for="lived_experience" :value="__('Do you want to share anything else about your lived experience? (optional)')" />
            <x-hearth-textarea name="lived_experience" hinted>{{ old('lived_experience', $communityMember->lived_experience) }}</x-hearth-textarea>
            <x-hearth-error for="lived_experience" />
        </div>
    </fieldset>

    <h2>{{ __('Skills and strengths') }}</h2>

    <x-privacy-indicator level="public" :value="__('Everyone can access this information.')" />

    <fieldset>
        <div class="field @error('skills_and_strengths') field--error @enderror">
            <x-hearth-label for="skills_and_strengths" :value="__('What are your skills and strengths? (optional)')" />
            <x-hearth-hint for="skills_and_strengths">{{ __('Feel free to list your skills and strengths, and say more about them.') }}</x-hearth-hint>
            <x-hearth-textarea name="skills_and_strengths" hinted>{{ old('skills_and_strengths', $communityMember->skills_and_strengths) }}</x-hearth-textarea>
            <x-hearth-error for="skills_and_strengths" />
        </div>
    </fieldset>

    <h2>{{ __('Work and volunteer experiences') }}</h2>

    <x-privacy-indicator level="public" :value="__('Everyone can access this information.')" />

    <div
        class="flow"
        x-data="{ experiences: [
            @if($communityMember->work_and_volunteer_experiences)
            @foreach ($communityMember->work_and_volunteer_experiences as $experience)
            {
                title: '{{ $experience['title'] }}',
                start_year: '{{ $experience['start_year'] }}',
                end_year: '{{ $experience['end_year'] }}',
                current: {{ $experience['current'] ?? 'false' }}
            }@if(!$loop->last),@endif
            @endforeach
            @else
            {
                title: '',
                start_year: '',
                end_year: '',
                current: false
            }
            @endif
        ] }"
        x-init="$refs.ssr.remove()"
    >
        <div x-ref="ssr">
            @if($communityMember->work_and_volunteer_experiences)
            @foreach ($communityMember->work_and_volunteer_experiences as $experience)
            <fieldset>
                <div class="field">
                    <x-hearth-label :for="'experience_title_' . $loop->index" :value="__('Title of role')" />
                    <x-hearth-input :id="'experience_title_' . $loop->index" :name="'work_and_volunteer_experiences[' . $loop->index . '][title]'" :value="old('title', $experience['title'])" />
                </div>
                <div class="field">
                    <x-hearth-label :for="'experience_start_year_' . $loop->index" :value="__('Year started')" />
                    <x-hearth-input :id="'experience_start_year_' . $loop->index" :name="'work_and_volunteer_experiences[' . $loop->index . '][start_year]'" :value="old('start_year', $experience['start_year'])" />
                </div>
                <div class="field">
                    <x-hearth-label :for="'experience_end_year_' . $loop->index" :value="__('Year ended')" />
                    <x-hearth-input :id="'experience_end_year_' . $loop->index" :name="'work_and_volunteer_experiences[' . $loop->index . '][end_year]'" :value="old('end_year', $experience['end_year'])" />
                </div>
                <div class="field">
                    <x-hearth-input type="checkbox" :id="'experience_current_' . $loop->index" :name="'work_and_volunteer_experiences[' . $loop->index . '][current]'" />
                    <x-hearth-label :for="'experience_current_' . $loop->index" :value="__('I currently work here')" />
                </div>
            </fieldset>
            @endforeach
            @endif
        </div>
        <template x-for="(experience, index) in experiences">
            <div class="flow">
                <fieldset>
                    <div class="field">
                        <label x-bind:for="'title_' + index">Title of role</label>
                        <input type="text" x-bind:id="'title_' + index" x-bind:name="'work_and_volunteer_experiences['+ index + '][title]'" x-bind:value="experience.title"/ >
                    </div>
                    <div class="field"><label x-bind:for="'start_year_' + index">Year started</label>
                        <input type="text" x-bind:id="'start_year_' + index" x-bind:name="'work_and_volunteer_experiences['+ index + '][start_year]'" x-bind:value="experience.start_year"/ >
                    </div>
                    <div class="field"><label x-bind:for="'end_year_' + index">Year finished</label>
                        <input type="text" x-bind:id="'end_year_' + index" x-bind:name="'work_and_volunteer_experiences['+ index + '][end_year]'" x-bind:value="experience.end_year"/ >
                    </div>
                    <div class="field">
                        <input type="checkbox" x-bind:id="'current_' + index" x-bind:name="'work_and_volunteer_experiences['+ index + '][current]'" x-bind:checked="experience.current" value="1" />
                        <label x-bind:for="'current_' + index">I currently work or volunteer here</label>
                    </div>
                </fieldset>
                <p x-show="index > 0"><button @click="experiences.splice(index, 1)" type="button">Delete this item</button></p>
            </div>
        </template>
        <p><button @click="experiences.push({})" type="button">Add new item</button></p>
    </div>

    <p>
        <x-hearth-input type="submit" name="save" :value="__('Save')" />
        <x-hearth-input type="submit" name="save_and_next" :value="__('Save and next')" />
    </p>
</form>

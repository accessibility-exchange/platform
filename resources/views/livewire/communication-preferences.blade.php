<div class="stack">
    <fieldset>
        <legend>{{ __('My contact information (required)') }}</legend>
        <div class="field @error('email') field-error @enderror">
            <x-hearth-label for="email" :value="__('Email')" />
            <x-hearth-input type="email" name="email" :value="old('email', $communityMember->email ?? $communityMember->user->email)" wire:model.lazy="email" />
            <x-hearth-error for="email" />
        </div>
        <div class="field @error('phone') field-error @enderror">
            <x-hearth-label for="phone" :value="__('Phone number')" />
            <x-hearth-input type="tel" name="phone" :value="old('phone', $communityMember->phone)" wire:model.lazy="phone" />
            <x-hearth-error for="phone" />
        </div>

        <div class="field @error('vrs') field-error @enderror">
            <x-hearth-checkbox name="vrs" :checked="old('vrs', $communityMember->vrs ?? false)" wire:model="vrs" />
            <x-hearth-label for="vrs" :value="__('I require Video Relay Service (VRS) for phone calls')" />
            <x-hearth-error for="vrs" />
        </div>
    </fieldset>
    <fieldset>
        <legend>{{ __('Support person (optional)') }}</legend>
        <div class="stack">
            <ul role="list" class="stack">
                @foreach($supportPeople as $i => $person)
                    <li class="stack">
                        <div class="field @error("support_people.{$i}.name") field-error @enderror">
                            <x-hearth-label :for="'support_people_' . $i . '_name'" :value="__('Contact name')" />
                            <x-hearth-hint :for="'support_people_' . $i . '_name'">{{ __('This does not have to be their legal name.') }}</x-hearth-hint>
                            <x-hearth-input :id="'support_people_' . $i . '_name'" :name="'support_people[' . $i . '][name]'" :value="$person['name']" required hinted wire:model.lazy="supportPeople.{{ $i }}.name" />
                            <x-hearth-error :for="'support_people_' . $i . '_name'" :field="'support_people.' . $i . '.name'" />
                        </div>
                        <div class="field @error("support_people.{$i}.email") field-error @enderror">
                            <x-hearth-label :for="'support_people_' . $i . '_email'" :value="__('Email')" />
                            <x-hearth-input type="email" :id="'support_people_' . $i . '_email'" :name="'support_people[' . $i . '][email]'" :value="$person['email'] ?? ''" wire:model.lazy="supportPeople.{{ $i }}.email" />
                            <x-hearth-error :for="'support_people_' . $i . '_email'" :field="'support_people.' . $i . '.email'" />
                        </div>
                        <div class="field @error("support_people.{$i}.phone") field-error @enderror">
                            <x-hearth-label :for="'support_people_' . $i . '_phone'" :value="__('Phone number')" />
                            <x-hearth-input type="tel" :id="'support_people_' . $i . '_phone'" :name="'support_people[' . $i . '][phone]'" :value="$person['phone'] ?? ''" wire:model.lazy="supportPeople.{{ $i }}.phone" />
                            <x-hearth-error :for="'support_people_' . $i . '_phone'" :field="'support_people.' . $i . '.phone'" />
                        </div>
                        @if($loop->count > 1)
                            <button class="secondary" type="button" wire:click="removePerson({{ $i }})">{{ __('Remove this support person') }}</button>
                        @endif
                    </li>
                @endforeach
            </ul>
            @if ($this->canAddMorePeople())
                <button class="secondary" type="button" wire:click="addPerson">{{ __('Add a support person') }}</button>
            @endif
        </div>
    </fieldset>

    @if(count($this->contactPeople) > 2 || count($this->getContactMethodsFor($contactPerson)) > 2)
    <fieldset class="stack">
        <legend>{{ __('What is the best way to contact you? (required)') }}</legend>

        @if(count($this->contactPeople) > 2)
        <div class="field @error('preferred_contact_person') field-error @enderror">
            <x-hearth-label for="preferred_contact_person" :value="__('Contact person (required)')" />
            <x-hearth-select name="preferred_contact_person" :options="$this->contactPeople" :selected="old('preferred_contact_person', $communityMember->preferred_contact_person)" required wire:model="contactPerson" />
            <x-hearth-error for="preferred_contact_person" />
        </div>
        @endif

        @if(count($this->getContactMethodsFor($contactPerson)) > 2)
        <div class="field @error('preferred_contact_method') field-error @enderror">
            <x-hearth-label for="preferred_contact_method" :value="__('Contact method (required)')" />
            <x-hearth-select name="preferred_contact_method" :options="$this->getContactMethodsFor($contactPerson)" :selected="old('preferred_contact_method', $communityMember->preferred_contact_method)" required wire:model="contactMethod" />
            <x-hearth-error for="preferred_contact_method" />
        </div>
        @endif
    </fieldset>
    @endif
</div>

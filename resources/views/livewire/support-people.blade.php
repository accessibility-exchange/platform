<div class="flow">
    <ul role="list" class="flow">
        @foreach($people as $i => $person)
        <li class="flow">
            <div class="field @error("support_people.{$i}.name") field-error @enderror">
                <x-hearth-label :for="'support_people_' . $i . '_name'" :value="__('Contact name')" />
                <x-hearth-hint :for="'support_people_' . $i . '_name'">{{ __('This does not have to be their legal name.') }}</x-hearth-hint>
                <x-hearth-input :id="'support_people_' . $i . '_name'" :name="'support_people[' . $i . '][name]'" :value="$person['name']" required hinted />
                <x-hearth-error :for="'support_people_' . $i . '_name'" :field="'support_people.' . $i . '.name'" />
            </div>
            <div class="field @error("support_people.{$i}.email") field-error @enderror">
                <x-hearth-label :for="'support_people_' . $i . '_email'" :value="__('Email')" />
                <x-hearth-input type="email" :id="'support_people_' . $i . '_email'" :name="'support_people[' . $i . '][email]'" :value="$person['email']" required />
                <x-hearth-error :for="'support_people_' . $i . '_email'" :field="'support_people.' . $i . '.email'" />
            </div>
            <div class="field @error("support_people.{$i}.phone") field-error @enderror">
                <x-hearth-label :for="'support_people_' . $i . '_phone'" :value="__('Phone')" />
                <x-hearth-input type="tel" :id="'support_people_' . $i . '_phone'" :name="'support_people[' . $i . '][phone]'" :value="$person['phone']" required />
                <x-hearth-error :for="'support_people_' . $i . '_phone'" :field="'support_people.' . $i . '.phone'" />
            </div>
            <div class="field">
                <x-hearth-checkbox :id="'support_people_' . $i . '_page_creator'" :name="'support_people[' . $i . '][page_creator]'" :checked="$person['page_creator'] ?? false" />
                <x-hearth-label :for="'support_people_' . $i . '_page_creator'" :value="__('This person created my page on my behalf')" />
            </div>
            @if($loop->count > 1)
            <button type="button" wire:click="removePerson({{ $i }})">{{ __('Remove this support person') }}</button>
            @endif
        </li>
        @endforeach
    </ul>
    @if ($this->canAddMorePeople())
    <button type="button" wire:click="addPerson">{{ __('Add a support person') }}</button>
    @endif
</div>

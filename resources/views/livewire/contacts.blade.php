<div class="stack">
    @if($contacts)
    <ul role="list" class="stack">
        @foreach($contacts as $i => $contact)
        <li class="stack">
            <div class="field @error("contacts.{$i}.name") field-error @enderror">
                <x-hearth-label :for="'contacts_' . $i . '_name'" :value="__('Contact name')" />
                <x-hearth-hint :for="'contacts_' . $i . '_name'">{{ __('This does not have to be their legal name.') }}</x-hearth-hint>
                <x-hearth-input :id="'contacts_' . $i . '_name'" :name="'contacts[' . $i . '][name]'" :value="$contact['name']" required hinted />
                <x-hearth-error :for="'contacts_' . $i . '_name'" :field="'contacts.' . $i . '.name'" />
            </div>
            <div class="field @error("contacts.{$i}.email") field-error @enderror">
                <x-hearth-label :for="'contacts_' . $i . '_email'" :value="__('Email')" />
                <x-hearth-input type="email" :id="'contacts_' . $i . '_email'" :name="'contacts[' . $i . '][email]'" :value="$contact['email']" required />
                <x-hearth-error :for="'contacts_' . $i . '_email'" :field="'contacts.' . $i . '.email'" />
            </div>
            <div class="field @error("contacts.{$i}.phone") field-error @enderror">
                <x-hearth-label :for="'contacts_' . $i . '_phone'" :value="__('Phone')" />
                <x-hearth-input type="tel" :id="'contacts_' . $i . '_phone'" :name="'contacts[' . $i . '][phone]'" :value="$contact['phone']" required />
                <x-hearth-error :for="'contacts_' . $i . '_phone'" :field="'contacts.' . $i . '.phone'" />
            </div>
            @if($loop->count > 1)
            <button class="secondary" type="button" wire:click="removeContact({{ $i }})">{{ __('Remove this contact') }}</button>
            @endif
        </li>
        @endforeach
    </ul>
    @endif
    @if ($this->canAddMoreContacts())
    <button class="secondary" type="button" wire:click="addContact">{{ __('Add a contact') }}</button>
    @endif
</div>

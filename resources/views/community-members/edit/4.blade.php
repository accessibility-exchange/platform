<h2>
    {{ __('Step :current of :total', ['current' => request()->get('step'), 'total' => 5]) }}<br />
    {{ __('Communication preferences') }}
</h2>

@include('community-members.partials.progress')

<form action="{{ localized_route('community-members.update-communication-preferences', $communityMember) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <h3>{{ __('Contact information') }}</h3>

    <p>
        <x-hearth-input type="submit" name="save_and_previous" :value="__('Save and previous')" />
        <x-hearth-input type="submit" name="save" :value="__('Save')" />
        <x-hearth-input type="submit" name="save_and_next" :value="__('Save and next')" />
    </p>

    <fieldset>
        <legend>{{ __('For you') }}</legend>
        <div class="field @error('email') field-error @enderror">
            <x-hearth-label for="email" :value="__('Email')" />
            <x-hearth-input type="email" name="email" :value="old('email', $communityMember->email ?? $communityMember->user->email)" required />
            <x-hearth-error for="email" />
        </div>
        <div class="field @error('phone') field-error @enderror">
            <x-hearth-label for="phone" :value="__('Phone')" />
            <x-hearth-input type="tel" name="phone" :value="old('phone', $communityMember->phone)" required />
            <x-hearth-error for="phone" />
        </div>
    </fieldset>
    <fieldset>
        <legend>{{ __('For your support people') }}</legend>
        <livewire:support-people :people="$communityMember->support_people ?? [['name' => '', 'email' => '', 'phone' => '', 'page_creator' => false]]" />
    </fieldset>

    <h3>{{ __('Communication with you') }}</h3>

    <fieldset>
        <legend>{{ __('How do you want to be contacted when a regulated entity wants to consult with you?') }}</legend>
        <x-hearth-checkboxes name="preferred_contact_methods" :options="$contactMethods" :selected="old('preferred_contact_methods', $communityMember->preferred_contact_methods ?? [])" />
    </fieldset>

    <fieldset>
        <legend>{{ __('What languages do you use?') }}</legend>
        <livewire:language-picker :languages="$communityMember->languages ?? [$communityMember->user->locale]" :availableLanguages="$languages" />
    </fieldset>

    <p>
        <x-hearth-input type="submit" name="save_and_previous" :value="__('Save and previous')" />
        <x-hearth-input type="submit" name="save" :value="__('Save')" />
        <x-hearth-input type="submit" name="save_and_next" :value="__('Save and next')" />
    </p>
</form>

<form class="stack" action="{{ localized_route('organizations.update-contact-information', $organization) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <div class="with-sidebar with-sidebar:last">

        @include('organizations.partials.progress')

        <div class="stack">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step') ?? 1, 'total' => 4]) }}<br />
                {{ __('Contact information') }}
            </h2>

            <p class="repel">
                <button class="secondary" name="save_and_previous" value="1">{{ __('Save and back') }}</button>
                <button name="save" value="1">{{ __('Save') }}</button>
            </p>

            <div class="field @error("contact_person_name") field-error @enderror">
                <x-hearth-label for="contact_person_name" :value="__('Name of contact person (required)')" />
                <x-hearth-hint for="contact_person_name">{{ __('This does not have to be their legal name.') }}</x-hearth-hint>
                <x-hearth-input id="contact_person_name" name="contact_person_name" :value="old('contact_person_name', $organization->contact_person_name)" required hinted />
                <x-hearth-error for="contact_person_name" field="contact_person_name" />
            </div>
            <div class="field @error('contact_person_email') field-error @enderror">
                <x-hearth-label for="contact_person_email" :value="__('Contact person’s email')" />
                <x-hearth-input type="email" name="contact_person_email" :value="old('contact_person_email', $organization->contact_person_email)" />
                <x-hearth-error for="contact_person_email" />
            </div>
            <div class="field @error('contact_person_phone') field-error @enderror">
                <x-hearth-label for="contact_person_phone" :value="__('Contact person’s phone number')" />
                <x-hearth-input type="tel" name="contact_person_phone" :value="old('contact_person_phone', $organization->contact_person_phone?->formatForCountry('CA'))" />
                <x-hearth-error for="contact_person_phone" />
            </div>

            <div class="field @error('contact_person_vrs') field-error @enderror">
                <x-hearth-checkbox name="contact_person_vrs" :checked="old('contact_person_vrs', $organization->contact_person_vrs ?? false)" />
                <x-hearth-label for="contact_person_vrs" :value="__('They require Video Relay Service (VRS) for phone calls')" />
                <x-hearth-error for="contact_person_vrs" />
            </div>

            <div class="field @error('preferred_contact_method') field-error @enderror">
                <x-hearth-label for="preferred_contact_method">{{ __('Preferred contact method (required)') }}</x-hearth-label>
                <x-hearth-select name="preferred_contact_method" :options="Spatie\LaravelOptions\Options::forArray(['email' => __('Email'), 'phone' => __('Phone')])->toArray()" :selected="old('preferred_contact_method', $organization->preferred_contact_method ?? 'email')"/>
                <x-hearth-error for="preferred_contact_method" />
            </div>

            <p class="repel">
                <button class="secondary" name="save_and_previous" value="1">{{ __('Save and back') }}</button>
                <button name="save" value="1">{{ __('Save') }}</button>
            </p>
        </div>
    </div>
</form>

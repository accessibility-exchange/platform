<form action="{{ localized_route('community-members.update-communication-and-meeting-preferences', $communityMember) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')
    <div class="with-sidebar with-sidebar:last">
        @include('community-members.partials.progress')

        <div class="stack">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step'), 'total' => 5]) }}<br />
                {{ __('Communication and meeting preferences') }}
            </h2>

            <p class="repel">
                <x-hearth-input class="secondary" type="submit" name="save_and_previous" :value="__('Save and previous')" />
                <x-hearth-input type="submit" name="save" :value="__('Save')" />
            </p>

            <livewire:communication-preferences :communityMember="$communityMember" />

            <fieldset class="field @error('meeting_types') field--error @enderror">
                <legend>{{ __('What types of meetings are you able to attend? (required)') }}</legend>
                <x-hearth-checkboxes name="meeting_types" :options="$meetingTypes" :checked="old('meeting_types', $communityMember->meeting_types ?? [])" />
                <x-hearth-error for="meeting_types" />
            </fieldset>

            <p class="repel">
                <x-hearth-input class="secondary" type="submit" name="save_and_previous" :value="__('Save and previous')" />
                <x-hearth-input type="submit" name="save" :value="__('Save')" />
            </p>
        </div>
    </div>
</form>

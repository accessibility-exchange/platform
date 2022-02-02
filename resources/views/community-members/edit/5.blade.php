<h2>
    {{ __('Step :current of :total', ['current' => request()->get('step'), 'total' => 5]) }}<br />
    {{ __('Access and accomodations') }}
</h2>

@include('community-members.partials.progress')

<form action="{{ localized_route('community-members.update-access-and-accomodations', $communityMember) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <p>
        <x-hearth-input type="submit" name="save_and_previous" :value="__('Save and previous')" />
        <x-hearth-input type="submit" name="save" :value="__('Save')" />
    </p>

    <x-privacy-indicator level="private" :value="__('Only organizations who work with you will be able to access this information.')" />

    @php
        $meetingTypes = [
            'in_person' => __('In person'),
            'web_conference' => __('Virtual – web conference'),
            'phone' => __('Virtual – phone call')
        ]
    @endphp

    <fieldset class="field @error('meeting_types') field--error @enderror">
        <legend>{{ __('What types of meetings are you able to participate in?') }}</legend>
        <x-hearth-checkboxes name="meeting_types" :options="$meetingTypes" :selected="old('meeting_types', $communityMember->meeting_types ?? [])" />
        <x-hearth-error for="meeting_types" />
    </fieldset>

    <fieldset class="field @error('access_needs') field--error @enderror">
        <legend>{{ __('What are your access needs?') }}</legend>
        <x-hearth-checkboxes name="access_needs" :options="$accessNeeds" :selected="old('access_needs', $communityMember->accessSupports->pluck('id')->toArray() ?? [])" />
        <x-hearth-error for="access_needs" />
    </fieldset>

    <p>
        <x-hearth-input type="submit" name="save_and_previous" :value="__('Save and previous')" />
        <x-hearth-input type="submit" name="save" :value="__('Save')" />
    </p>
</form>

<h2>
    {{ __('Step :current of :total', ['current' => request()->get('step'), 'total' => 5]) }}<br />
    {{ __('Communication preferences') }}
</h2>

@include('community-members.partials.progress')

<form action="{{ localized_route('community-members.update-communication-preferences-needs', $communityMember) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <p>
        <x-hearth-input type="submit" name="save" :value="__('Save')" />
        <x-hearth-input type="submit" name="save_and_next" :value="__('Save and next')" />
    </p>
</form>

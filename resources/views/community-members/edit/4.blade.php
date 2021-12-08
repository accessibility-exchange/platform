<form action="{{ localized_route('community-members.update-access-needs', $communityMember) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <x-hearth-button>{{ __('Save changes') }}</x-hearth-button>
</form>

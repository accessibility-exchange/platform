<form action="{{ localized_route('community-members.update-experiences', $communityMember) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <x-hearth-button>{{ __('Save changes') }}</x-hearth-button>
</form>

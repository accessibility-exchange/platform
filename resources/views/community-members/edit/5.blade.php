<h2>
    {{ __('Step :current of :total', ['current' => request()->get('step'), 'total' => 5]) }}<br />
    {{ __('Access and accomodations') }}
</h2>

@include('community-members.partials.progress')

<form action="{{ localized_route('community-members.update-access-and-accomodations', $communityMember) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <x-hearth-button>{{ __('Save changes') }}</x-hearth-button>
</form>

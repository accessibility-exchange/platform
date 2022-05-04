<form class="stack" action="{{ localized_route('regulated-organizations.store') }}" method="post" novalidate>
    <x-translation-picker />

    <x-hearth-button>{{ __('Create regulated organization') }}</x-hearth-button>

    @csrf
</form>

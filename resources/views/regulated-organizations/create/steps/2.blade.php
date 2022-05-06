<form class="stack" action="{{ localized_route('regulated-organizations.store') }}" method="post" novalidate>
    <x-translation-picker />

    <button>{{ __('Create regulated organization') }}</button>

    @csrf
</form>

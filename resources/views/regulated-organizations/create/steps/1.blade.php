<form class="stack" action="{{ localized_route('regulated-organizations.store-name') }}" method="POST" novalidate>
    @csrf
    <x-hearth-input id="user_id" type="hidden" name="user_id" :value="Auth::user()->id" required />
    <div class="field">
        <x-hearth-label for="name" :value="__('Regulated organization name')" />
        <x-hearth-input name="name" required :value="old('name', '')"/>
    </div>

    <p class="repel">
        <a class="cta secondary" href="{{ localized_route('dashboard') }}">{{ __('Cancel') }}</a>
        <button>{{ __('Next') }}</button>
    </p>
</form>

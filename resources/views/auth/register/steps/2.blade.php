<form method="POST" action="{{ localized_route('register-details') }}" novalidate>
    @csrf

    <x-hearth-input id="locale" type="hidden" name="locale" value="{{ locale() ?: config('app.locale') }}" />

    <!-- Name -->
    <div class="field @error('name') field--error @enderror">
        <x-hearth-label for="name" :value="__('hearth::user.label_name')" />
        <x-hearth-input type="text" name="name" value="{{ old('name', session()->get('name')) }}" required autofocus />
        <x-hearth-error for="name" />
    </div>

    <!-- Email Address -->
    <div class="field @error('email') field--error @enderror">
        <x-hearth-label for="email" :value="__('hearth::forms.label_email')" />
        <x-hearth-input type="email" name="email" value="{{ old('email', session()->get('email')) }}" required />
        <x-hearth-error for="email" />
    </div>

    <p>
        <a class="button" href="{{ localized_route('register', ['step' => 1]) }}">{{ __('Back') }}</a>

        <x-hearth-button>
            {{ __('Next') }}
        </x-hearth-button>
    </p>
</form>

@if (session()->get('isNewOrganizationContext'))
    <p>
        {{ __('Please enter your own name and email, rather than you organization’s. You will be able to create your organization in a later step.') }}
    </p>
@endif

<form class="stack" method="POST" action="{{ localized_route('register-details') }}" novalidate>
    @csrf

    <!-- Name -->
    <div class="field @error('name') field--error @enderror stack">
        <x-hearth-label for="name" :value="__('Full name')" />
        <x-hearth-hint for="name">{{ __('This does not have to be your legal name.') }}</x-hearth-hint>
        <x-hearth-input name="name" type="text" value="{{ old('name', session('name')) }}" required autofocus />
        <x-hearth-error for="name" />
    </div>

    <!-- Email Address -->
    <div class="field @error('email') field--error @enderror stack">
        <x-hearth-label for="email" :value="__('hearth::forms.label_email')" />
        <x-hearth-hint for="email">
            {{ __('This is the email address you will use to sign in to The Accessibility Exchange.') }}
        </x-hearth-hint>
        <x-hearth-input name="email" type="email" value="{{ old('email', session('email')) }}" required />
        <x-hearth-error for="email" />
    </div>

    <p class="repel">
        <a class="cta secondary" href="{{ localized_route('register', ['step' => 2]) }}">{{ __('Back') }}</a>

        <button>
            {{ __('Next') }}
        </button>
    </p>
</form>

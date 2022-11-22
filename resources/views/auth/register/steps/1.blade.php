<form class="stack" method="POST" action="{{ localized_route('register-languages') }}" novalidate>
    @csrf

    @if (request()->get('context'))
        <input name="context" type="hidden" value="{{ request()->get('context') }}" />
    @endif

    @if (request()->get('role'))
        <input name="role" type="hidden" value="{{ request()->get('role') }}" />
    @endif

    @if (request()->get('invitation'))
        <input name="invitation" type="hidden" value="{{ request()->get('invitation') }}" />
    @endif

    @if (request()->get('email'))
        <input name="email" type="hidden" value="{{ request()->get('email') }}" />
    @endif

    <fieldset class="stack">
        <legend>{{ __('Please tell us which language you would like to use on The Accessibility Exchange.') }}</legend>

        <p id="languages-hint">
            {{ __('Please choose the language or languages you would like to use on this website.') }}<br />
            {{ __('Later, you will have the chance to choose the language or languages for your consultations.') }}
        </p>

        <div class="field @error('locale') field--error @enderror stack">
            <x-hearth-label for="locale" :value="__('Website language') . ' ' . __('(required)')" />
            <x-hearth-locale-select name="locale" :selected="old('locale', locale())" hinted="languages-hint" />
            <x-hearth-error for="locale" />
        </div>
    </fieldset>

    <p class="repel">
        <button>
            {{ __('Next') }}
        </button>
    </p>
</form>

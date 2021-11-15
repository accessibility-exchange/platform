<x-app-layout>
    <x-slot name="title">{{ __('hearth::user.settings') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('hearth::user.settings') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('user-profile-information.update') }}" method="POST" novalidate>
        @csrf

        @method('PUT')

        <div class="field @error('name', 'updateProfileInformation') field--error @enderror">
            <x-hearth-label for="name" :value="__('hearth::user.label_name')" />
            <x-hearth-input id="name" type="text" name="name" :value="old('name', $user->name)" required />
            <x-hearth-error for="name" bag="updateProfileInformation" />
        </div>

        <div class="field @error('email', 'updateProfileInformation') field--error @enderror">
            <x-hearth-label for="email" :value="__('hearth::forms.label_email')" />
            <x-hearth-input id="email" type="email" name="email" :value="old('email', $user->email)" required />
            <x-hearth-error for="email" bag="updateProfileInformation" />
        </div>

        <div class="field @error('locale', 'updateProfileInformation') field--error @enderror">
            <x-hearth-label for="locale" :value="__('hearth::user.label_locale')" />
            <x-hearth-locale-select name="locale" :selected="old('locale', $user->locale)" />
            <x-hearth-error for="locale" bag="updateProfileInformation" />
        </div>

        <div class="field" x-data="previewHandler()">
            <x-hearth-label for="theme" :value="__('themes.label_theme')" />
            <x-hearth-select x-model.string="theme" id="theme" name="theme" :options="$themes" :selected="old('theme', $user->theme)" @change="preview()" />
            <script>
                function previewHandler() {
                    return {
                        theme: '{{ $user->theme }}',
                        preview() {
                            document.documentElement.dataset.theme = this.theme;
                        }
                    }
                }
            </script>
        </div>

        <x-hearth-button>
            {{ __('Save changes') }}
        </x-hearth-button>
    </form>
</x-app-layout>

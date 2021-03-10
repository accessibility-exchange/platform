<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('user.settings') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('user-profile-information.update') }}" method="POST" novalidate>
        @csrf
        @method('PUT')
        <div class="field">
            <x-label for="name" :value="__('user.label_name')" />
            <x-input id="name" type="name" name="name" :value="old('name', $user->name)" required />
            @error('name', 'updateProfileInformation')
            <x-validation-error>{{ $message }}</x-validation-error>
            @enderror
        </div>

        <div class="field">
            <x-label for="email" :value="__('forms.label_email')" />
            <x-input id="email" type="email" name="email" :value="old('email', $user->email)" required />
            @error('email', 'updateProfileInformation')
            <x-validation-error>{{ $message }}</x-validation-error>
            @enderror
        </div>

        <div class="field">
            <x-label for="locale" :value="__('user.label_locale')" />
            <x-locale-select :selected="old('locale', $user->locale)" />
        </div>

        <div class="field" x-data="previewHandler()">
            <x-label for="theme" :value="__('themes.label_theme')" />
            <x-select x-model.string="theme" id="theme" name="theme" :options="$themes" :selected="old('theme', $user->theme)" @change="preview()" />
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

        <x-button>
            {{ __('forms.save_changes') }}
        </x-button>
    </form>
</x-app-layout>

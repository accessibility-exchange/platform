<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('user.my_profile') }}
        </h1>
    </x-slot>

    <form action="{{ localized_route('user-profile-information.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="field">
            <x-label for="name" :value="__('user.label_name')" />
            <x-input id="name" type="name" name="name" :value="$user->name" required novalidated />
            @error('name', 'updateProfileInformation')
            <x-validation-error>{{ $message }}</x-validation-error>
            @enderror
        </div>

        <div class="field">
            <x-label for="email" :value="__('forms.label_email')" />
            <x-input id="email" type="email" name="email" :value="$user->email" required novalidated />
            @error('email', 'updateProfileInformation')
            <x-validation-error>{{ $message }}</x-validation-error>
            @enderror
        </div>

        <div class="field">
            <x-label for="locale" :value="__('user.label_locale')" />
            <x-locale-select :selected="$user->locale" />
        </div>

        <div class="field" x-data="previewHandler()">
            <x-label for="theme" :value="__('themes.label_theme')" />
            <x-select x-model.string="theme" id="theme" name="theme" :options="['system' => __('themes.system'), 'light' => __('themes.light'), 'dark' => __('themes.dark')]" :selected="$user->theme" @change="preview()" />
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

<x-app-layout>
    <x-slot name="title">{{ __('Display preferences') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
            <li><a href="{{ localized_route('users.settings') }}">{{ __('Settings') }}</a></li>
        </ol>
        <h1>
            {{ __('Display preferences') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form class="flow" action="{{ localized_route('users.update_display_preferences') }}" method="POST" novalidate>
        @csrf

        @method('PUT')

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

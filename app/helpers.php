<?php


if (! function_exists('settings')) {
    /**
     * Retrieve a setting from the settiungs Valuestore.
     *
     * @param string $key The setting key.
     * @param mixed $default A default value for the setting.
     *
     * @return mixed
     */
    function settings($key = null, $default = null): mixed
    {
        if ($key === null) {
            return app(App\Settings::class);
        }

        return app(App\Settings::class)->get($key, $default);
    }
}

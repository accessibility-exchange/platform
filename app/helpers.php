<?php

use App\Settings;

if (! function_exists('settings')) {
    /**
     * Retrieve a setting from the settings Valuestore.
     *
     * @param string $key The setting key.
     * @param mixed $default A default value for the setting.
     *
     * @return mixed
     */
    function settings($key = null, $default = null): mixed
    {
        if ($key === null) {
            return app(Settings::class);
        }

        return app(Settings::class)->get($key, $default);
    }
}

if (! function_exists('is_signed_language')) {
    /**
     * Does an ISO-639 locale code represent a signed language?
     *
     * @link https://iso639-3.sil.org/code_tables/639/data ISO 639 code table.
     *
     * @param string $locale An ISO 639 code.
     *
     * @return bool
     */
    function is_signed_language($locale)
    {
        return in_array($locale, ['ase', 'fcs']);
    }
}

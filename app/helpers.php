<?php

use App\Settings;

if (! function_exists('settings')) {
    /**
     * Retrieve a setting from the settings Valuestore.
     *
     * @param  string|null  $key The setting key.
     * @param  mixed|null  $default A default value for the setting.
     * @return mixed
     */
    function settings(string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return app(Settings::class);
        }

        return app(Settings::class)->get($key, $default);
    }
}

if (! function_exists('get_available_languages')) {
    /**
     * Get available languages.
     *
     * @param  bool  $all Should all languages be shown? Otherwise, only supported locales will be included.
     * @return array
     */
    function get_available_languages(bool $all = false, bool $signed = true): array
    {
        $languages = [
            'fcs' => __('locales.fcs'),
        ] + require __DIR__.'./../vendor/umpirsky/language-list/data/'.locale().'/language.php';

        if ($all) {
            $result = $languages;
        } else {
            $result = [];
            $minimum = array_merge(['ase', 'fcs'], config('locales.supported'));
            foreach ($minimum as $locale) {
                $result[$locale] = $languages[$locale];
            }
        }

        if (! $signed) {
            unset($result['ase']);
            unset($result['fcs']);
        }

        asort($result);

        return $result;
    }
}

if (! function_exists('is_signed_language')) {
    /**
     * Does an ISO-639 locale code represent a signed language?
     *
     * @link https://iso639-3.sil.org/code_tables/639/data ISO 639 code table.
     *
     * @param  string  $code An ISO 639 code.
     * @return bool
     */
    function is_signed_language(string $code): bool
    {
        return in_array($code, ['ase', 'fcs']);
    }
}

if (! function_exists('get_written_language_for_signed_language')) {
    /**
     * Get the written language which most closely corresponds to a signed language.
     *
     * @link https://iso639-3.sil.org/code_tables/639/data ISO 639 code table.
     *
     * @param  string  $code An ISO 639 code.
     * @return string
     */
    function get_written_language_for_signed_language(string $code): string
    {
        return match ($code) {
            'fcs' => 'fr',
            default => 'en',
        };
    }
}

if (! function_exists('get_language_exonym')) {
    /**
     * Get the name of a locale from its code.
     *
     * @param  string  $code An ISO 639 language code.
     * @param  string  $locale An ISO 639-1 language code (in which the locale name should be returned).
     * @param  bool  $capitalize Whether the returned language exonym should be capitalized.
     * @return null|string The localized name of the locale, if found.
     */
    function get_language_exonym(string $code, string $locale = '', bool $capitalize = true): null|string
    {
        if ($locale === '') {
            $locale = locale();
        }

        switch ($code) {
            case 'fcs':
                return trans('locales.'.$code, [], $locale);
            default:
                $languages = require __DIR__.'./../vendor/umpirsky/language-list/data/'.$locale.'/language.php';

                $language = $languages[$code] ?? null;

                return $language && $capitalize ? Str::ucfirst($language) : $language;
        }
    }
}

if (! function_exists('get_regions_from_provinces_and_territories')) {
    function get_regions_from_provinces_and_territories(array $provinces_and_territories): array
    {
        if (empty($provinces_and_territories)) {
            return [
                'west-coast' => __('West Coast'),
                'prairie-provinces' => __('Prairie Provinces'),
                'central-canada' => __('Central Canada'),
                'northern-territories' => __('Northern Territories'),
                'atlantic-provinces' => __('Atlantic Provinces'),
            ];
        }

        $regions = [];

        if (! empty(array_intersect(['BC'], $provinces_and_territories))) {
            $regions['west-coast'] = __('West Coast');
        }

        if (! empty(array_intersect(['AB', 'SK', 'MB'], $provinces_and_territories))) {
            $regions['prairie-provinces'] = __('Prairie Provinces');
        }

        if (! empty(array_intersect(['ON', 'QC'], $provinces_and_territories))) {
            $regions['central-canada'] = __('Central Canada');
        }

        if (! empty(array_intersect(['NU', 'NT', 'YT'], $provinces_and_territories))) {
            $regions['northern-territories'] = __('Northern Territories');
        }

        if (! empty(array_intersect(['NB', 'NS', 'PE', 'NL'], $provinces_and_territories))) {
            $regions['atlantic-provinces'] = __('Atlantic Provinces');
        }

        return $regions;
    }
}

if (! function_exists('normalize_url')) {
    /**
     * Normalize a URL by adding a scheme if one isn't already present.
     */
    function normalize_url(string|null $url, string $scheme = 'https://'): string|null
    {
        if (! blank($url)) {
            $result = is_null(parse_url($url, PHP_URL_SCHEME)) ? $scheme.$url : $url;

            return (filter_var($result, FILTER_VALIDATE_URL)) ? $result : $url;
        }

        return $url;
    }
}

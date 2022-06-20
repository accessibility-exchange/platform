<?php

use App\Settings;
use CommerceGuys\Intl\Language\LanguageRepository;

if (! function_exists('settings')) {
    /**
     * Retrieve a setting from the settings Valuestore.
     *
     * @param string|null $key The setting key.
     * @param mixed|null $default A default value for the setting.
     *
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
     * @param bool $all Should all languages be shown? Otherwise, only supported locales will be included.
     *
     * @return array
     */
    function get_available_languages(bool $all = false): array
    {
        $languages = [
            'ase' => __('locales.ase'),
            'fcs' => __('locales.fcs'),
        ];

        if ($all) {
            $languages = $languages + (new LanguageRepository)->getList(locale());
        } else {
            foreach (config('locales.supported') as $locale) {
                $languages[$locale] = get_language_exonym($locale, locale());
            }
        }

        asort($languages);

        return $languages;
    }
}

if (! function_exists('is_signed_language')) {
    /**
     * Does an ISO-639 locale code represent a signed language?
     *
     * @link https://iso639-3.sil.org/code_tables/639/data ISO 639 code table.
     *
     * @param string $code An ISO 639 code.
     *
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
     * @param string $code An ISO 639 code.
     *
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
     * @param string $code An ISO 639 language code.
     * @param string $locale An ISO 639-1 language code (in which the locale name should be returned).
     * @param bool $capitalize Whether the returned language exonym should be capitalized.
     *
     * @return null|string The localized name of the locale, if found.
     */
    function get_language_exonym(string $code, string $locale = '', bool $capitalize = true): null|string
    {
        if ($locale === '') {
            $locale = locale();
        }

        switch ($code) {
            case 'ase':
            case 'fcs':
                return trans('locales.' . $code, [], $locale);
            default:
                $languages = new LanguageRepository();

                try {
                    $language = $languages->get($code, $locale);

                    return $capitalize ? Str::ucfirst($language->getName()) : $language->getName();
                } catch (CommerceGuys\Intl\Exception\UnknownLanguageException $e) {
                    return null;
                }
        }
    }
}

if (! function_exists('get_regions_from_provinces_and_territories')) {
    function get_regions_from_provinces_and_territories(array $provinces_and_territories): array
    {
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

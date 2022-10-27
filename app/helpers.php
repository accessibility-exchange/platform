<?php

use App\Settings;
use Illuminate\Support\Str;

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
            $result = array_filter(
                $languages,
                function ($language) {
                    return
                        (! str_starts_with($language, 'en') && ! str_starts_with($language, 'fr'))
                        || ! strpos($language, '_')
                        || in_array($language, [
                            'egy',
                            'grc',
                            'zbl',
                            'nwc',
                            'syc',
                            'eo',
                            'jam',
                            'dum',
                            'enm',
                            'frm',
                            'gmh',
                            'mga',
                            'mul',
                            'mgo',
                            'zxx',
                            'ang',
                            'fro',
                            'goh',
                            'sga',
                            'non',
                            'peo',
                            'pro',
                            'pfl',
                            'pdc',
                            'de_CH',
                            'frc',
                            'und',
                            'tlh',
                        ]);
                },
                ARRAY_FILTER_USE_KEY
            );
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

if (! function_exists('context_from_model')) {
    /**
     * Get the context version (kebab case) of a model's class name.
     */
    function context_from_model(mixed $model): string
    {
        return Str::kebab(class_basename($model));
    }
}

if (! function_exists('contact_information')) {
    function contact_information(): string
    {
        $email = settings()->get('email', 'support@accessibilityexchange.ca');
        $phone = phone(settings()->get('phone', '+1-888-867-0053'), 'CA')->formatForCountry('CA');

        return Str::markdown(
            '**'
            .__('Email').':** ['.$email.'](mailto:'.$email.')  '
            ."\n"
            .'**'.__('Call or :vrs', [
                'vrs' => '<a href="https://srvcanadavrs.ca/en/resources/resource-centre/vrs-basics/register/" rel="external">'.__('VRS').'</a>',
            ]).':** '.$phone
        );
    }
}

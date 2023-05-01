<?php

use App\Settings;
use App\Settings\GeneralSettings;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

if (! function_exists('settings')) {
    /**
     * Retrieve a setting from the settings Valuestore.
     *
     * @param  string|null  $key The setting key.
     * @param  mixed|null  $default A default value for the setting.
     */
    function settings(string $key = null, mixed $default = null): mixed
    {
        return app(GeneralSettings::class)->$key ?? $default;
    }
}

if (! function_exists('get_available_languages')) {
    /**
     * Get available languages.
     *
     * @param  bool  $all Should all languages be shown? Otherwise, only supported locales will be included.
     */
    function get_available_languages(bool $all = false, bool $signed = true): array
    {
        $locale = match (locale()) {
            'asl' => 'en',
            'lsq' => 'fr',
            default => locale()
        };

        $languages = [
            'lsq' => __('locales.lsq'),
            'asl' => __('locales.asl'),
        ] + require __DIR__.'./../vendor/umpirsky/language-list/data/'.$locale.'/language.php';

        if ($all) {
            $result = array_filter(
                $languages,
                function ($language) {
                    return
                        (! str_starts_with($language, 'en') && ! str_starts_with($language, 'fr'))
                        || ! strpos($language, '_')
                        || in_array($language, [
                            'ase',
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
            $minimum = config('locales.supported');
            foreach ($minimum as $locale) {
                $result[$locale] = $languages[$locale];
            }
        }

        asort($result);

        $en = $result['en'];
        unset($result['en']);
        $fr = $result['fr'];
        unset($result['fr']);
        $asl = $result['asl'];
        unset($result['asl']);
        $lsq = $result['lsq'];
        unset($result['lsq']);

        if ($signed) {
            foreach (array_reverse(config('locales.supported')) as $code) {
                $result = Arr::prepend($result, $$code, $code);
            }
        } else {
            foreach (['fr', 'en'] as $code) {
                $result = Arr::prepend($result, $$code, $code);
            }
        }

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
     */
    function is_signed_language(string $code): bool
    {
        return in_array($code, ['asl', 'lsq']);
    }
}

if (! function_exists('get_supported_locales')) {
    /**
     * Get supported locales. Mostly used to filter out signed locales.
     *
     * @param  bool  $signed Determines if signed locales (e.g. asl, lsq) are included.
     */
    function get_supported_locales(bool $signed = true): array
    {
        if ($signed) {
            return config('locales.supported');
        }

        return array_filter(config('locales.supported'), fn ($locale) => ! is_signed_language($locale));
    };
}

if (! function_exists('get_written_language_for_signed_language')) {
    /**
     * Get the written language which most closely corresponds to a signed language.
     * If a code other than ASL or LSQ is passed, it will be returned without modification.
     *
     * @link https://iso639-3.sil.org/code_tables/639/data ISO 639 code table.
     *
     * @param  string  $code Either 'asl' or 'lsq'
     * @return string  An ISO 639 code
     */
    function get_written_language_for_signed_language(string $code): string
    {
        return match ($code) {
            'asl' => 'en',
            'lsq' => 'fr',
            default => $code,
        };
    }
}

if (! function_exists('get_signed_language_for_written_language')) {
    /**
     * Get the signed language which most closely corresponds to a written language.
     *
     * @link https://iso639-3.sil.org/code_tables/639/data ISO 639 code table.
     *
     * @param  string  $code An ISO 639 code.
     */
    function get_signed_language_for_written_language(string $code): string
    {
        return match ($code) {
            'fr' => 'lsq',
            default => 'asl',
        };
    }
}

if (! function_exists('to_written_languages')) {
    /**
     * Convert all signed languages to their most closely corresponding written language.
     * If a code other than ASL or LSQ is passed, it will be returned without modification.
     *
     * @link https://iso639-3.sil.org/code_tables/639/data ISO 639 code table.
     *
     * @param  array<string>  $codes
     * @return array<string>  An array of ISO 639 codes
     */
    function to_written_languages(array $codes): array
    {
        foreach ($codes as $key => $code) {
            $codes[$key] = get_written_language_for_signed_language($code);
        }

        return array_unique($codes);
    }
}

if (! function_exists('get_language_exonym')) {
    /**
     * Get the name of a locale from its code.
     *
     * @param  string  $code An ISO 639 language code, or 'asl'/'lsq'.
     * @param  ?string  $locale An ISO 639-1 language code (in which the locale name should be returned).
     * @param  bool  $capitalize Whether the returned language exonym should be capitalized.
     * @return null|string The localized name of the locale, if found.
     */
    function get_language_exonym(string $code, ?string $locale = null, bool $capitalize = true, bool $acronym = false): null|string
    {
        $locale ??= locale();

        $locale = match ($locale) {
            'asl' => 'en',
            'lsq' => 'fr',
            default => $locale
        };

        $languages = require __DIR__.'./../vendor/umpirsky/language-list/data/'.$locale.'/language.php';

        $language = match ($code) {
            'asl', 'lsq' => $acronym ? Str::upper($code) : trans('locales.'.$code, [], $locale),
            default => $languages[$code] ?? null
        };

        if ($language) {
            return $capitalize ? Str::ucfirst($language) : $language;
        }

        return null;
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
        $email = settings('email');
        $phone = phone(settings('phone'), 'CA')->formatForCountry('CA');

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

if (! function_exists('orientation_link')) {
    function orientation_link(string $userType): string
    {
        return match ($userType) {
            App\Enums\UserContext::Individual->value => settings('individual_orientation'),
            App\Enums\UserContext::Organization->value => settings('org_orientation'),
            App\Enums\UserContext::RegulatedOrganization->value => settings('fro_orientation'),
            default => '#',
        };
    }
}

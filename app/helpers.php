<?php

use App\Settings;
use App\Settings\GeneralSettings;
use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

if (! function_exists('settings')) {
    /**
     * Retrieve a setting from the general settings table.
     *
     * @param  string|null  $key The setting key.
     * @param  mixed|null  $default A default value for the setting.
     */
    function settings(?string $key = null, mixed $default = null): mixed
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
                        ! ((str_starts_with($language, 'en_') || str_starts_with($language, 'fr_')))
                        && ! in_array($language, [
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

if (! function_exists('to_written_language')) {
    /**
     * Get the written language which most closely corresponds to a signed language.
     * If a code other than ASL or LSQ is passed, it will be returned without modification.
     *
     * @link https://iso639-3.sil.org/code_tables/639/data ISO 639 code table.
     *
     * @param  string  $code Either 'asl' or 'lsq'
     * @return string  An ISO 639 code
     */
    function to_written_language(string $code): string
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
            $codes[$key] = to_written_language($code);
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
    function get_language_exonym(string $code, ?string $locale = null, bool $capitalize = true, bool $acronym = false): ?string
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

if (! function_exists('localized_route_for_locale')) {
    /**
     * Gets the localized URL for the named route in the requested locale. It takes the same arguments as the
     * `localized_route` method from laravel-multilingual-routes.
     *
     * This is to address an issue with laravel-sluggable that only returns the localized slug in the applications
     * locale.
     *
     * See: https://github.com/spatie/laravel-sluggable/discussions/228
     */
    function localized_route_for_locale(string $name, mixed $parameters, ?string $locale = null, bool $absolute = true): string
    {
        // dd(is_null($locale), $locale === $locale);
        if (is_null($locale) || $locale === locale()) {
            return localized_route($name, $parameters, $locale, $absolute);
        }

        $originalLocale = locale();

        // Change to requested locale to work around https://github.com/spatie/laravel-sluggable/discussions/228
        locale($locale);

        $localizedURL = localized_route($name, $parameters, $locale, $absolute);

        // Restore original locale
        locale($originalLocale);

        return $localizedURL;
    }
}

if (! function_exists('route_name')) {
    /**
     * Returns the route name. By default it returns the unlocalized but can return the localized name if needed; which
     * would be the same as calling the `getName` method on the route directly. If no route is passed in, it will attempt
     * to use the current route.
     */
    function route_name(?Route $route = null, bool $localized = false): ?string
    {
        $route ??= RouteFacade::getCurrentRoute();
        $routeName = $route->getName();

        if ($localized) {
            return $routeName;
        }

        foreach (get_supported_locales() as $locale) {
            $prefix = "{$locale}.";
            if (Str::startsWith($routeName, $prefix)) {
                return Str::after($routeName, $prefix);
            }
        }

        return $routeName;
    }
}

if (! function_exists('normalize_url')) {
    /**
     * Normalize a URL by adding a scheme if one isn't already present.
     */
    function normalize_url(?string $url, string $scheme = 'https://'): ?string
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

if (! function_exists('safe_link_replacement')) {
    function safe_link_replacement(string $string): string
    {
        if (filter_var($string, FILTER_VALIDATE_EMAIL)) {
            $string = htmlentities($string);

            return "<a href=\"mailto:{$string}\">{$string}</a>";
        }

        if (filter_var($string, FILTER_VALIDATE_URL)) {
            $string = htmlentities($string);

            return "<a href=\"{$string}\">{$string}</a>";
        }

        $string = htmlentities($string);

        return "&lt;{$string}&gt;";
    }
}

// The placeholder replacement is based off of the makeReplacements method from Laravel's
// framework/src/Illuminate/Translation/Translator.php
// See: https://github.com/laravel/framework/blob/4d4898878d1ba52d6689506527d1d4bfa24d57f2/src/Illuminate/Translation/Translator.php#L227
// Original License: MIT (https://github.com/laravel/framework/tree/4d4898878d1ba52d6689506527d1d4bfa24d57f2#license)
if (! function_exists('html_replacements')) {
    function html_replacements(string $string, array $replacements = []): string
    {
        if (empty($replacements)) {
            return $string;
        }

        $replace_pairs = [];

        foreach ($replacements as $key => $value) {
            $replace_pairs[":{$key}"] = htmlentities($value);
            $replace_pairs[":!{$key}"] = $value;
            // Replaces `<:placeholder>` in the $string when the $string has
            // already been processed, with HTML characters such
            // as `<` and `>` replaced by entities.
            $linkReplacement = safe_link_replacement($value);
            $replace_pairs["&lt;:{$key}&gt;"] = $linkReplacement;
            $replace_pairs["<:{$key}>"] = $linkReplacement;
        }

        return strtr($string, $replace_pairs);
    }
}

if (! function_exists('safe_markdown')) {
    function safe_markdown(string $string, array $replacements = [], ?string $locale = null, bool $inline = false): HtmlString
    {
        $markdownFuncName = $inline ? 'inlineMarkdown' : 'markdown';

        $localized = __($string, [], $locale);
        $html = Str::$markdownFuncName($localized, config('markdown'));

        return new HtmlString(html_replacements($html, $replacements));
    }
}

if (! function_exists('safe_inlineMarkdown')) {
    function safe_inlineMarkdown(string $string, array $replacements = [], ?string $locale = null): HtmlString
    {
        return safe_markdown($string, $replacements, $locale, true);
    }
}

if (! function_exists('safe_nl2br')) {
    function safe_nl2br(string $string, bool $use_xhtml = true): HtmlString
    {
        return new HtmlString(nl2br(htmlentities($string), $use_xhtml));
    }
}

if (! function_exists('orientation_link')) {
    function orientation_link(string $userType): string
    {
        return match ($userType) {
            App\Enums\UserContext::Individual->value => settings_localized('individual_orientation', locale()),
            App\Enums\UserContext::Organization->value => settings_localized('org_orientation', locale()),
            App\Enums\UserContext::RegulatedOrganization->value => settings_localized('fro_orientation', locale()),
            default => '#',
        };
    }
}

if (! function_exists('settings_localized')) {
    /**
     * Retrieve a setting from the general settings table.
     *
     * @param  string|null  $key The setting key.
     * @param  string|null  $locale The requested locale for the setting to be returned in
     * @param  mixed|null  $default A default value for the setting.
     */
    function settings_localized(?string $key = null, ?string $locale = null, mixed $default = null): mixed
    {
        $locale = to_written_language($locale ?? config('app.locale'));
        $settings = settings($key, []);

        return $settings[$locale] ?? $settings[config('app.fallback_locale')];
    }
}

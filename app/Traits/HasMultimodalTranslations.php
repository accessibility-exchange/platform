<?php

namespace App\Traits;

trait HasMultimodalTranslations
{
    /**
     * @param  string  $attribute
     * @param  string  $code
     * @return string
     */
    public function getWrittenTranslation(string $attribute, string $code = ''): string
    {
        /** If no language code was passed, return the default attribute. */
        if (! $code) {
            return $this->$attribute;
        }

        /** If the language code is for a signed language, get the attribute in the written language which most closely corresponds to the signed language. */
        if (is_signed_language($code)) {
            return $this->getTranslation($attribute, get_written_language_for_signed_language($code));
        }

        /** Get the attribute in the language. */
        return $this->getTranslation($attribute, $code);
    }

    /**
     * @param  string  $attribute
     * @param  string  $code
     * @return void
     */
    // TODO: Implement this
    // public function getSignedTranslation(string $attribute, string $code = '')
    // {
    //     /** If no language code was passed, or if the language code is not for a signed language, return null. */
    //     if (! $code || ! is_signed_language($code)) {
    //         return null;
    //     }
    //
    //     /** Get the attribute in the signed language. */
    //     return $this->getTranslation($attribute, $code);
    // } */
}

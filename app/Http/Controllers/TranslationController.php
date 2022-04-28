<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTranslationRequest;
use App\Http\Requests\DestroyTranslationRequest;
use Illuminate\Http\RedirectResponse;

class TranslationController extends Controller
{
    /**
     * Add the specified translation to the resource.
     *
     * @param AddTranslationRequest $request
     * @return RedirectResponse
     */
    public function add(AddTranslationRequest $request)
    {
        $data = $request->validated();

        $model = $data['translatable_type']::find($data['translatable_id']);

        $languages = $model->languages;
        $languages[] = $data['new_language'];
        $model->update(['languages' => array_values($languages)]);

        flash(__('Language :language added.', ['language' => get_locale_name($data['new_language'])]), 'success');

        return back();
    }

    /**
     * Remove the specified translation from the resource.
     *
     * @param DestroyTranslationRequest $request
     * @return RedirectResponse
     */
    public function destroy(DestroyTranslationRequest $request)
    {
        $data = $request->validated();

        $model = $data['translatable_type']::find($data['translatable_id']);

        $languages = $model->languages;
        $key = array_search($data['language'], $languages);
        unset($languages[$key]);
        $model->update(['languages' => array_values($languages)]);
        $model->forgetAllTranslations($data['language']);
        $model->save();

        flash(__('Language :language removed.', ['language' => get_locale_name($data['language'])]), 'success');

        return back();
    }
}

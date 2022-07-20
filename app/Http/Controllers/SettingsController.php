<?php

namespace App\Http\Controllers;

use App\Enums\MeetingTypes;
use App\Enums\ProvincesAndTerritories;
use App\Enums\Themes;
use App\Http\Requests\UpdateAccessNeedsRequest;
use App\Http\Requests\UpdateAreasOfInterestRequest;
use App\Http\Requests\UpdateCommunicationAndConsultationPreferences;
use App\Http\Requests\UpdateLanguagePreferencesRequest;
use App\Http\Requests\UpdatePaymentInformationRequest;
use App\Http\Requests\UpdateWebsiteAccessibilityPreferencesRequest;
use App\Models\AccessSupport;
use App\Models\ConsultingMethod;
use App\Models\Impact;
use App\Models\PaymentType;
use App\Models\Sector;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Gate;
use Spatie\LaravelOptions\Options;

class SettingsController extends Controller
{
    public function settings(): View
    {
        return view(
            'settings.show',
            [
                'user' => Auth::user(),
            ]
        );
    }

    public function editAccessNeeds(): View
    {
        Gate::allowIf(fn ($user) => $user->context === 'individual');

        $individual = Auth::user()->individual;

        return view('settings.access-needs', [
            'individual' => $individual,
            'generalAccessSupports' => Options::forModels(AccessSupport::where([
                ['in_person', true],
                ['virtual', true],
                ['documents', true],
                ['name->en', '!=', 'I would like to speak to someone to discuss additional access needs or concerns'],
            ]))->toArray(),
            'meetingAccessSupports' => Options::forModels(AccessSupport::where([
                ['in_person', true],
                ['virtual', true],
                ['documents', false],
            ]))->toArray(),
            'signLanguageInterpretation' => AccessSupport::where('name->en', 'Sign language interpretation')->first()->id,
            'spokenLanguageInterpretation' => AccessSupport::where('name->en', 'Spoken language interpretation')->first()->id,
            'inPersonAccessSupports' => Options::forModels(AccessSupport::where([
                ['in_person', true],
                ['virtual', false],
                ['documents', false],
            ]))->toArray(),
            'documentAccessSupports' => Options::forModels(AccessSupport::where([
                ['in_person', false],
                ['virtual', false],
                ['documents', true],
            ]))->toArray(),
            'signLanguageTranslation' => AccessSupport::where('name->en', 'Sign language translation')->first()->id,
            'writtenLanguageTranslation' => AccessSupport::where('name->en', 'Written language translation')->first()->id,
            'printedVersion' => AccessSupport::where('name->en', 'Printed version of engagement documents')->first()->id,
            'additionalNeedsOrConcerns' => AccessSupport::where('name->en', 'I would like to speak to someone to discuss additional access needs or concerns')->first(),
            'selectedAccessSupports' => $individual->accessSupports->pluck('id')->toArray(),
            'signedLanguages' => Options::forArray([
                'ase' => __('locales.ase'),
                'fcs' => __('locales.fcs'),
            ])->nullable(__('Choose a signed language…'))->toArray(),
            'spokenOrWrittenLanguages' => Options::forArray(get_available_languages(true, false))->nullable(__('Choose a language…'))->toArray(),
            'regions' => Options::forEnum(ProvincesAndTerritories::class)->nullable(__('Choose a province or territory…'))->toArray(),
            'guessedSpokenOrWrittenLanguage' => $individual->first_language && ! is_signed_language($individual->first_language) ? $individual->first_language : false,
            'guessedSignedLanguage' => $individual->first_language && is_signed_language($individual->first_language) ? $individual->first_language : false,
        ]);
    }

    public function updateAccessNeeds(UpdateAccessNeedsRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $signLanguageInterpretation = AccessSupport::where('name->en', 'Sign language interpretation')->first()->id;
        $spokenLanguageInterpretation = AccessSupport::where('name->en', 'Spoken language interpretation')->first()->id;
        $signLanguageTranslation = AccessSupport::where('name->en', 'Sign language translation')->first()->id;
        $writtenLanguageTranslation = AccessSupport::where('name->en', 'Written language translation')->first()->id;
        $printedVersion = AccessSupport::where('name->en', 'Printed version of engagement documents')->first()->id;

        $individual = Auth::user()->individual;

        if (! isset($data['meeting_access_needs']) || (isset($data['meeting_access_needs']) && ! in_array($signLanguageInterpretation, $data['meeting_access_needs']))) {
            $data['signed_language_for_interpretation'] = null;
        }

        if (! isset($data['meeting_access_needs']) || (isset($data['meeting_access_needs']) && ! in_array($spokenLanguageInterpretation, $data['meeting_access_needs']))) {
            $data['spoken_language_for_interpretation'] = null;
        }

        if (! isset($data['document_access_needs']) || (isset($data['document_access_needs']) && ! in_array($signLanguageTranslation, $data['document_access_needs']))) {
            $data['signed_language_for_translation'] = null;
        }

        if (! isset($data['document_access_needs']) || (isset($data['document_access_needs']) && ! in_array($writtenLanguageTranslation, $data['document_access_needs']))) {
            $data['written_language_for_translation'] = null;
        }

        if (! isset($data['document_access_needs']) || (isset($data['document_access_needs']) && ! in_array($printedVersion, $data['document_access_needs']))) {
            $data['street_address'] = null;
            $data['unit_apartment_suite'] = null;
            $data['locality'] = null;
            $data['region'] = null;
            $data['postal_code'] = null;
        }

        $individual->fill($data);

        $individual->save();

        $access_supports = array_merge(
            $data['general_access_needs'] ?? [],
            $data['meeting_access_needs'] ?? [],
            $data['in_person_access_needs'] ?? [],
            $data['document_access_needs'] ?? []
        );

        if (isset($data['additional_needs_or_concerns'])) {
            $access_supports[] = $data['additional_needs_or_concerns'];
        }

        $individual->accessSupports()->sync($access_supports);

        flash(__('Your access needs have been updated.'), 'success');

        return redirect(localized_route('settings.edit-access-needs'));
    }

    public function editCommunicationAndConsultationPreferences(): View
    {
        Gate::allowIf(fn ($user) => $user->context === 'individual');

        $individual = Auth::user()->individual;

        return view('settings.communication-and-consultation-preferences', [
            'individual' => $individual,
            'consultingMethods' => Options::forModels(ConsultingMethod::class)->toArray(),
            'selectedConsultingMethods' => $individual->consultingMethods->pluck('id')->toArray(),
            'meetingTypes' => Options::forEnum(MeetingTypes::class)->toArray(),
            'interviews' => ConsultingMethod::where('name->en', 'Interviews')->first()->id,
            'focusGroups' => ConsultingMethod::where('name->en', 'Focus groups')->first()->id,
            'workshops' => ConsultingMethod::where('name->en', 'Workshops')->first()->id,
        ]);
    }

    public function updateCommunicationAndConsultationPreferences(UpdateCommunicationAndConsultationPreferences $request): RedirectResponse
    {
        $data = $request->validated();

        $individual = Auth::user()->individual;

        $individual->fill($data);

        $individual->save();

        $individual->consultingMethods()->sync($data['consulting_methods'] ?? []);

        flash(__('Your communication and consultation preferences have been updated.'), 'success');

        return redirect(localized_route('settings.edit-communication-and-consultation-preferences'));
    }

    public function editLanguagePreferences(): View
    {
        $user = Auth::user();
        $individual = $user->individual;

        $workingLanguages = [
            $user->locale,
        ];

        if ($user->signed_language) {
            $workingLanguages[] = $user->signed_language;
        }

        return view('settings.language-preferences', [
            'user' => $user,
            'individual' => $individual,
            'languages' => Options::forArray(get_available_languages(true))->nullable(__('Choose a language…'))->toArray(),
            'signedLanguages' => Options::forArray([
                'ase' => __('locales.ase'),
                'fcs' => __('locales.fcs'),
            ])->nullable(__('Choose a signed language…'))->toArray(),
            'workingLanguages' => $workingLanguages,
        ]);
    }

    public function updateLanguagePreferences(UpdateLanguagePreferencesRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = Auth::user();

        $user->fill($data);

        $user->save();

        if ($individual = $user->individual) {
            $individual->fill($data);

            $individual->save();
        }

        flash(__('Your language preferences have been updated.'), 'success');

        return redirect(localized_route('settings.edit-language-preferences'));
    }

    public function editPaymentInformation(): View
    {
        Gate::allowIf(fn ($user) => $user->context === 'individual');

        return view('settings.payment-information', [
            'individual' => Auth::user()->individual,
            'paymentTypes' => Options::forModels(PaymentType::class)->toArray(),
        ]);
    }

    public function updatePaymentInformation(UpdatePaymentInformationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (! $request->has('other') || $data['other'] == 0) {
            $data['other_payment_type'] = '';
        }

        $individual = Auth::user()->individual;

        $individual->fill($data);

        $individual->save();

        $individual->paymentTypes()->sync($data['payment_types'] ?? []);

        flash(__('Your payment information has been updated.'), 'success');

        return redirect(localized_route('settings.edit-payment-information'));
    }

    public function editAreasOfInterest(): View
    {
        Gate::allowIf(fn ($user) => $user->context === 'individual');

        return view('settings.areas-of-interest', [
            'individual' => Auth::user()->individual,
            'sectors' => Options::forModels(Sector::class)->toArray(),
            'impacts' => Options::forModels(Impact::class)->toArray(),
        ]);
    }

    public function updateAreasOfInterest(UpdateAreasOfInterestRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $individual = Auth::user()->individual;

        $individual->sectorsOfInterest()->sync($data['sectors'] ?? []);

        $individual->impactsOfInterest()->sync($data['impacts'] ?? []);

        flash(__('Your areas of interest have been updated.'), 'success');

        return redirect(localized_route('settings.edit-areas-of-interest'));
    }

    /**
     * Show the display preferences edit view for the logged-in user.
     *
     * @return View
     */
    public function editWebsiteAccessibilityPreferences(): View
    {
        return view('settings.website-accessibility-preferences', [
            'user' => Auth::user(),
            'themes' => Options::forEnum(Themes::class)->toArray(),
            'signedLanguages' => Options::forArray([
                'ase' => __('locales.ase'),
                'fcs' => __('locales.fcs'),
            ])->nullable(__('Off'))->toArray(),
        ]);
    }

    /**
     * Show the display preferences edit view for the logged-in user.
     *
     * @param  UpdateWebsiteAccessibilityPreferencesRequest  $request
     * @return RedirectResponse
     */
    public function updateWebsiteAccessibilityPreferences(UpdateWebsiteAccessibilityPreferencesRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $data = $request->validated();

        $user->fill($data);
        $user->save();

        flash(__('Your website accessibility preferences have been updated.'), 'success');

        Cookie::queue('theme', $data['theme']);

        return redirect(localized_route('settings.edit-website-accessibility-preferences'));
    }
}

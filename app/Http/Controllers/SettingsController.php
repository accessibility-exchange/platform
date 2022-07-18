<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateLanguagePreferencesRequest;
use App\Http\Requests\UpdatePaymentInformationRequest;
use App\Models\PaymentType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\LaravelOptions\Options;

class SettingsController extends Controller
{
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
                'ase' => __('American Sign Language (ASL)'),
                'fcs' => 'Langue des signes québécoise (LSQ)',
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
}

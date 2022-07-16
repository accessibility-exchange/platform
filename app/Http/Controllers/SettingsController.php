<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePaymentInformationRequest;
use App\Models\PaymentType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\LaravelOptions\Options;

class SettingsController extends Controller
{
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

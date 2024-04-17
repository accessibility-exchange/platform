<?php

namespace App\Http\Controllers;

use App\Enums\ProjectInvolvement;
use App\Enums\UserContext;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserEngagementsController extends Controller
{
    public function show(): Response|View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->context === UserContext::Organization->value && ! $user->organization) {
            return redirect(localized_route('organizations.show-type-selection'));
        }

        if ($this->isParticipant($user)) {
            $section = ProjectInvolvement::Participating->value;
            $activeEngagements = $user->{$user->context}->engagements()->active()->get();
            $completeEngagements = $user->{$user->context}->engagements()->complete()->get();
        } elseif ($this->isConnector($user)) {
            $section = ProjectInvolvement::Contracted->value;
            $activeEngagements = $user->{$user->context}->connectingEngagements()->active()->get();
            $completeEngagements = $user->{$user->context}->connectingEngagements()->complete()->get();
        } else {
            abort(404);
        }

        return view('engagements.joined', [
            'section' => $section ?? '',
            'showParticipating' => $this->isParticipant($user),
            'showConnecting' => $this->isConnector($user),
            'activeEngagements' => $activeEngagements ?? [],
            'completeEngagements' => $completeEngagements ?? [],
        ]);
    }

    public function showContracted(): Response|View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->context === UserContext::Organization->value && ! $user->organization) {
            return redirect(localized_route('organizations.show-type-selection'));
        }

        if ($this->isConnector($user)) {
            $activeEngagements = $user->{$user->context}->connectingEngagements()->active()->get();
            $completeEngagements = $user->{$user->context}->connectingEngagements()->complete()->get();

            return view('engagements.joined', [
                'title' => __('Engagements I’ve joined as a Community Connector'),
                'section' => 'contracted',
                'showParticipating' => $this->isParticipant($user),
                'showConnecting' => true,
                'activeEngagements' => $activeEngagements,
                'completeEngagements' => $completeEngagements,
            ]);
        }

        abort(404);
    }

    public function showParticipating(): Response|View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->context === UserContext::Organization->value && ! $user->organization) {
            return redirect(localized_route('organizations.show-type-selection'));
        }

        if ($this->isParticipant($user)) {
            $activeEngagements = $user->{$user->context}->engagements()->active()->get();
            $completeEngagements = $user->{$user->context}->engagements()->complete()->get();

            return view('engagements.joined', [
                'title' => __('Engagements I’ve joined as a Consultation Participant'),
                'section' => 'participating',
                'showParticipating' => true,
                'showConnecting' => $this->isConnector($user),
                'activeEngagements' => $activeEngagements,
                'completeEngagements' => $completeEngagements,
            ]);
        }

        abort(404);
    }

    public function isParticipant(User $user): bool
    {
        $userContext = $user->{$user->context};

        return $userContext && ($userContext->isParticipant() || $userContext->engagements()->count());
    }

    public function isConnector(User $user): bool
    {
        $userContext = $user->{$user->context};

        return $userContext && ($userContext->isConnector() || $userContext->connectingEngagements()->count());
    }
}

<?php

namespace App\Http\Livewire;

use App\Mail\ContractorInvitation;
use App\Models\Engagement;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use App\Notifications\IndividualContractorInvited;
use App\Notifications\OrganizationalContractorInvited;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Livewire\Component;
use Spatie\LaravelOptions\Options;

class AddEngagementConnector extends Component
{
    use AuthorizesRequests;

    public Engagement $engagement;

    public Project $project;

    public string $who = '';

    public string $email = '';

    public array $organizations = [];

    public string $organization = '';

    public function __invoke(Container $container, Route $route, ?Engagement $engagement = null)
    {
        return parent::__invoke($container, $route);
    }

    public function mount()
    {
        $this->project = $this->engagement->project;
        $this->organizations = Options::forModels(Organization::query()->whereJsonContains('roles', 'connector'))->nullable(__('Choose a community organization…'))->toArray();
    }

    public function render()
    {
        return view('livewire.add-engagement-connector')
            ->layout('layouts.app-medium');
    }

    public function inviteConnector()
    {
        $this->authorize('update', $this->engagement);

        if ($this->who === 'individual') {
            $validated = $this->withValidator(function (Validator $validator) {
                $validator->after(function ($validator) {
                    $user = User::whereBlind('email', 'email_index', $this->email)->first() ?? null;
                    if ($user) {
                        $individual = $user->individual ?? null;
                        if (is_null($individual) || ! $individual->checkStatus('published') || ! $individual->isConnector()) {
                            $validator->errors()->add('email', __('The individual on this website with the email address you provided is not a community connector.'));
                        }
                    }
                });
            })->validate([
                'email' => [
                    'required',
                    'email',
                    Rule::unique('invitations')->where(function ($query) {
                        return $query->where([
                            ['invitationable_type', 'App\Models\Engagement'],
                            ['invitationable_id', $this->engagement->id],
                        ]);
                    }),
                ],
            ]);

            $validated['type'] = 'individual';
        } else {
            $validated = $this->validate(
                [
                    'organization' => [
                        'required',
                        'integer',
                        Rule::exists('organizations', 'id')->where(function ($query) {
                            return $query->whereJsonContains('roles', 'connector');
                        }),
                    ],
                    'email' => [
                        'required',
                        'email',
                        Rule::unique('invitations')->where(function ($query) {
                            return $query->where([
                                ['invitationable_type', 'App\Models\Engagement'],
                                ['invitationable_id', $this->engagement->id],
                            ]);
                        }),
                    ],
                ],
                [
                    'email.unique' => __('This organization has already been invited.'),
                ]
            );

            $validated['type'] = 'organization';
        }

        $validated['role'] = 'connector';

        $invitation = $this->engagement->invitations()->create($validated);

        if ($this->who === 'individual') {
            $user = User::whereBlind('email', 'email_index', $this->email)->first() ?? null;
            if ($user) {
                $user->notify(new IndividualContractorInvited($invitation));
            } else {
                Mail::to($validated['email'])->send(new ContractorInvitation($invitation));
            }
        } else {
            $administrators = Organization::find((int) $this->organization)->administrators;
            Notification::send($administrators, new OrganizationalContractorInvited($invitation));
        }

        $this->engagement->extra_attributes->set('seeking_community_connector', false);

        $this->engagement->save();

        flash(__('invitation.create_invitation_succeeded'), 'success');

        return redirect(localized_route('engagements.manage-connector', $this->engagement));
    }

    protected function prepareForValidation($attributes): array
    {
        if (! empty($attributes['organization'])) {
            $attributes['email'] = Organization::find((int) $attributes['organization'])->contact_person_email;
        }

        return $attributes;
    }
}

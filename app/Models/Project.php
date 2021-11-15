<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use function localized_route;
use Makeable\EloquentStatus\HasStatus;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Project extends Model
{
    use HasFactory;
    use HasSlug;
    use HasStatus;
    use HasTranslations;
    use Notifiable;

    /**
     * The attributes that are protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
        'published_at' => 'datetime:Y-m-d',
        'found_consultants' => 'boolean',
        'confirmed_consultants' => 'boolean',
        'scheduled_planning_meeting' => 'boolean',
        'notified_of_planning_meeting' => 'boolean',
        'prepared_project_orientation' => 'boolean',
        'prepared_contractual_documents' => 'boolean',
        'booked_access_services_for_planning' => 'boolean',
        'finished_planning_meeting' => 'boolean',
        'scheduled_consultation_meetings' => 'boolean',
        'notified_of_consultation_meetings' => 'boolean',
        'prepared_consultation_materials' => 'boolean',
        'booked_access_services_for_consultations' => 'boolean',
        'finished_consultation_meetings' => 'boolean',
        'prepared_accessibility_plan' => 'boolean',
        'prepared_follow_up_plan' => 'boolean',
        'shared_plans_with_consultants' => 'boolean',
        'regions' => 'array',
        'payment_negotiable' => 'boolean',
        'virtual_consultation' => 'boolean',
        'existing_clients' => 'boolean',
        'prospective_clients' => 'boolean',
        'employees' => 'boolean',
        'min' => 'integer',
        'max' => 'integer',
        'flexible_deadlines' => 'boolean',
        'flexible_breaks' => 'boolean',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public array $translatable = [
        'name',
        'slug',
        'goals',
        'impact',
        'out_of_scope',
        'timeline',
        'payment_terms',
        'priority_outreach',
        'locality',
        'location_description',
        'anything_else',
    ];

    /**
     * The project's steps and the number of corresponding substeps for each.
     *
     * @var array
     */
    public array $substeps = [
        1 => [
            'published_at',
        ],
        2 => [
            'found_consultants',
            'confirmed_consultants',
        ],
        3 => [
            'scheduled_planning_meeting',
            'notified_of_planning_meeting',
            'prepared_project_orientation',
            'prepared_contractual_documents',
            'booked_access_services_for_planning',
            'finished_planning_meeting',
        ],
        4 => [
            'scheduled_consultation_meetings',
            'notified_of_consultation_meetings',
            'prepared_consultation_materials',
            'booked_access_services_for_consultations',
            'finished_consultation_meetings',
        ],
        5 => [
            'prepared_accessibility_plan',
            'prepared_follow_up_plan',
            'shared_plans_with_consultants',
        ],
    ];

    /**
     * Get the options for generating the slug.
     *
     * @return \Spatie\Sluggable\SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug->' . locale();
    }

    /**
     * Get the project steps for entities.
     *
     * @return array
     */
    public function getEntitySteps(): array
    {
        return [
            1 => [
                'title' => __('Publish your project'),
            ],
            2 => [
                'title' => __('Build a consulting team'),
            ],
            3 => [
                'title' => __('Learn how to work together'),
                'subtitle' => __('Meet with the consulting team and have a conversation with them about how you’d like to work together.'),
            ],
            4 => [
                'title' => __('Hold consultations'),
                'subtitle' => __('Have consultations with the consulting team to work on your accessibility project.'),
            ],
            5 => [
                'title' => __('Prepare and share your plans'),
            ],
        ];
    }

    /**
     * Get the project steps for consultants.
     *
     * @return array
     */
    public function getConsultantSteps(): array
    {
        return [
            1 => [
                'title' => __('Learn how to work together'),
                'subtitle' => __('Meet with the other consultants and the entity. Have a conversation with them about how you’d like to work together.'),
            ],
            2 => [
                'title' => __('Take part in consultations'),
                'subtitle' => __('Take part in consultations with the entity, and work together on their accessibility plan.'),
            ],
            3 => [
                'title' => __('Review and reflect'),
            ],
        ];
    }

    /**
     * Get the project substeps for entities.
     *
     * @return array
     */
    public function getEntitySubsteps(): array
    {
        return [
            1 => [
                1 => [
                    'link' => localized_route('projects.edit', $this),
                    'label' => __('Publish project page'),
                    'description' => false,
                    'status' => $this->checkStatus('published') ? 'complete' : 'in-progress',
                ],
            ],
            2 => [
                1 => [
                    'link' => localized_route('projects.find-interested-consultants', $this),
                    'label' => __('Find consultants'),
                    'description' => __('Build your shortlist of consultants who you would like to consult on your project.'),
                    'status' => $this->found_consultants ?? null,
                ],
                2 => [
                    'link' => "#",
                    'label' => __('Confirm consultants’ participation'),
                    'description' => __('Reach out to each consultant and ask whether they’d like to consult on your project.'),
                    'status' => $this->confirmed_consultants ?? null,
                ],
            ],
            3 => [
                1 => [
                    'link' => "#",
                    'label' => __('Schedule the meeting'),
                    'description' => __('Pick a date and time to have this meeting with the consulting team.'),
                    'status' => $this->scheduled_planning_meeting ?? null,
                ],
                2 => [
                    'link' => "#",
                    'label' => __('Contact consulting team'),
                    'description' => __('Contact the consulting team about the meeting.'),
                    'status' => $this->notified_of_planning_meeting ?? null,
                ],
                3 => [
                    'link' => "#",
                    'label' => __('Prepare a project orientation'),
                    'description' => __('Provide the consulting team with all the information they need about your project.'),
                    'status' => $this->prepared_project_orientation ?? null,
                ],
                4 => [
                    'link' => "#",
                    'label' => __('Prepare contracts and other legal documents'),
                    'description' => __('Provide the consulting team with any contracts they may need to sign.'),
                    'status' => $this->prepared_contractual_documents ?? null,
                ],
                5 => [
                    'link' => "#",
                    'label' => __('Provide access accommodations and book service providers'),
                    'description' => __('Make sure the access needs of the consulting team are met.'),
                    'status' => $this->booked_access_services_for_planning ?? null,
                ],
                6 => [
                    'link' => "#",
                    'label' => __('Hold the meeting'),
                    'description' => false,
                    'status' => $this->finished_planning_meeting ?? null,
                ],
            ],
            4 => [
                1 => [
                    'link' => "#",
                    'label' => __('Schedule the meetings'),
                    'description' => __('Pick the dates and times to have these meetings with the consulting team.'),
                    'status' => $this->scheduled_consultation_meetings ?? null,
                ],
                2 => [
                    'link' => "#",
                    'label' => __('Contact consulting team'),
                    'description' => __('Contact the consulting team about the meeting.'),
                    'status' => $this->notified_of_consultation_meetings ?? null,
                ],
                3 => [
                    'link' => "#",
                    'label' => __('Prepare consultation materials'),
                    'description' => __('Provide the consulting team with all the information they need for the consultation.'),
                    'status' => $this->prepared_consultation_materials ?? null,
                ],
                4 => [
                    'link' => "#",
                    'label' => __('Provide access accommodations and book service providers'),
                    'description' => __('Make sure the access needs of the consulting team are met.'),
                    'status' => $this->booked_access_services_for_consultations ?? null,
                ],
                5 => [
                    'link' => "#",
                    'label' => __('Hold the meetings'),
                    'description' => false,
                    'status' => $this->finished_consultation_meetings ?? null,
                ],
            ],
            5 => [
                1 => [
                    'link' => "#",
                    'label' => __('Prepare your accessibility plan'),
                    'description' => __('See examples and templates for this in :link.', ['link' => '<a href="' . localized_route('collections.index') . '">' . __('the Resource hub') . '</a>']),
                    'status' => $this->prepared_accessibility_plan ?? null,
                ],
                2 => [
                    'link' => "#",
                    'label' => __('Prepare your follow-up plan'),
                    'description' => __('Agree on a follow-up plan with the consulting team.'),
                    'status' => $this->prepared_follow_up_plan ?? null,
                ],
                3 => [
                    'link' => "#",
                    'label' => __('Share your accessibility plan and follow-up plan with the consulting team.'),
                    'description' => false,
                    'status' => $this->shared_plans_with_consultants ?? null,
                ],
                4 => [
                    'link' => "#",
                    'label' => __('Publish your accessibility plan (optional)'),
                    'description' => __('By uploading your completed plan, you can build trust with the larger disability community, which may make consultants more eager to work with you in the future.'),
                    'status' => $this->published_accessibility_plan ?? null,
                ],
                5 => [
                    'link' => localized_route('projects.create-update', $this),
                    'label' => __('Update consultants based on your follow-up plan'),
                    'description' => __('Based on your follow-up plan, update the consulting team on the progress of your accessibility project.'),
                    'status' => $this->published_accessibility_plan ?? null,
                ],
            ],
        ];
    }

    /**
     * Get the project substeps for consultants.
     *
     * @return array
     */
    public function getConsultantSubsteps(): array
    {
        return [
            1 => [],
            2 => [],
            3 => [
                1 => [
                    'link' => '#',
                    'label' => __('Review the plans'),
                    'description' => __('Review the plans that the entity has published.'),
                    'status' => null,
                ],
                2 => [
                    'link' => '#',
                    'label' => __('Reflect on the completed plan'),
                    'description' => __('You can review the completed plan and share your reflections. What input you gave made it in, what didn’t?'),
                    'status' => null,
                ],
                3 => [
                    'link' => '#',
                    'label' => __('Share your experience'),
                    'description' => __('Share your experience of what it was like to consult with this regulated entity. This will help other consultants understand what it’s like to work with them.'),
                    'status' => null,
                ],
                4 => [
                    'link' => localized_route('projects.index-updates', $this),
                    'label' => __('Review project updates'),
                    'description' => __('Review updates from the entity as they put the accessibility plan into action.'),
                    'status' => null,
                ],
            ],
        ];
    }

    /**
     * Has the project started?
     *
     * @return bool
     */
    public function started(): bool
    {
        return $this->start_date < Carbon::now();
    }

    /**
     * Has the project completed?
     *
     * @return bool
     */
    public function completed(): bool
    {
        return $this->hasReportedFindings();
    }

    /**
     * Get the project's timespan.
     *
     * @return string
     */
    public function timespan(): string
    {
        if ($this->end_date) {
            if ($this->start_date->translatedFormat('Y') === $this->end_date->translatedFormat('Y')) {
                return $this->start_date->translatedFormat('F') . '&mdash;' . $this->end_date->translatedFormat('F Y');
            } else {
                return $this->start_date->translatedFormat('F Y') . '&mdash;' . $this->end_date->translatedFormat('F Y');
            }
        }

        return $this->start_date > Carbon::now()
            ? __('project.starting', ['date' => $this->start_date->translatedFormat('F Y')])
            : __('project.started', ['date' => $this->start_date->translatedFormat('F Y')]);
    }

    /**
     * Has the entity built a consulting team for the project?
     *
     * @return bool
     */
    public function hasBuiltTeam(): bool
    {
        return ! is_null($this->found_consultants)
            && ! is_null($this->confirmed_consultants);
    }

    /**
     * Has the entity and the consulting team learned how to work together?
     *
     * @return bool
     */
    public function hasLearnedHowToWorkTogether(): bool
    {
        return ! is_null($this->scheduled_planning_meeting)
            && ! is_null($this->notified_of_planning_meeting)
            && ! is_null($this->prepared_project_orientation)
            && ! is_null($this->prepared_contractual_documents)
            && ! is_null($this->booked_access_services_for_planning)
            && ! is_null($this->finished_planning_meeting);
    }

    /**
     * Has the entity held consultations?
     *
     * @return bool
     */
    public function hasHeldConsultations(): bool
    {
        return ! is_null($this->scheduled_consultation_meetings)
            && ! is_null($this->notified_of_consultation_meetings)
            && ! is_null($this->prepared_consultation_materials)
            && ! is_null($this->booked_access_services_for_consultations)
            && ! is_null($this->finished_consultation_meetings);
    }

    /**
     * Has the entity prepared and shared their accessibility and follow-up plans?
     *
     * @return bool
     */
    public function hasReportedFindings(): bool
    {
        return ! is_null($this->prepared_accessibility_plan)
            && ! is_null($this->prepared_follow_up_plan)
            && ! is_null($this->shared_plans_with_consultants);
    }

    /**
     * What numeric step is the entity on?
     *
     * @return int
     */
    public function currentEntityStep(): int
    {
        $step = 1;

        if ($this->hasReportedFindings()) {
            $step = 6;
        } elseif ($this->hasHeldConsultations()) {
            $step = 5;
        } elseif ($this->hasLearnedHowToWorkTogether()) {
            $step = 4;
        } elseif ($this->hasBuiltTeam()) {
            $step = 3;
        } elseif ($this->checkStatus('published')) {
            $step = 2;
        } elseif ($this->checkStatus('draft')) {
            $step = 1;
        }

        return $step;
    }

    /**
     * What numeric step is the consultant on?
     *
     * @return int
     */
    public function currentConsultantStep(): int
    {
        $step = 1;

        if ($this->hasHeldConsultations()) {
            $step = 2;
        } elseif ($this->hasLearnedHowToWorkTogether()) {
            $step = 2;
        } elseif ($this->hasBuiltTeam()) {
            $step = 1;
        }

        return $step;
    }

    /**
     * What named step is the project on?
     *
     * @return string
     */
    public function step(): string
    {
        return match ($this->currentEntityStep()) {
            1 => __('Publishing project'),
            2 => __('Building consulting team'),
            3 => __('Learning how to work together'),
            4 => __('Holding consultations'),
            5 => __('Writing report'),
            6 => __('Completed'),
        };
    }

    /**
     * How far along is a given step?
     *
     * @param int $step
     *
     * @return int
     */
    public function getProgress(int $step): int
    {
        $progress = 0;

        if ($step === 1) {
            if ($this->checkStatus('published')) {
                $progress = 1;
            }
        } else {
            foreach ($this->substeps[$step] as $substep) {
                if (! is_null($this[$substep]) && $this[$substep]) {
                    $progress++;
                }
            }
        }

        return $progress;
    }

    /**
     * The entity that created the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    /**
     * The impacts that the project aims to have.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function impacts(): BelongsToMany
    {
        return $this->belongsToMany(Impact::class);
    }

    /**
     * The sectors that the project is working within.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sectors(): BelongsToMany
    {
        return $this->entity->belongsToMany(Sector::class);
    }

    /**
     * The payment methods that the project can offer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function paymentMethods(): BelongsToMany
    {
        return $this->belongsToMany(PaymentMethod::class);
    }

    /**
     * The consulting methods that the project will use.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function consultingMethods(): BelongsToMany
    {
        return $this->belongsToMany(ConsultingMethod::class);
    }

    /**
     * The access supports that the project can provide.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function accessSupports(): BelongsToMany
    {
        return $this->belongsToMany(AccessSupport::class);
    }

    /**
     * The communication tools that the project uses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function communicationTools(): BelongsToMany
    {
        return $this->belongsToMany(CommunicationTool::class);
    }

    /**
     * The communities that the project is focussed on.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function communities(): BelongsToMany
    {
        return $this->belongsToMany(Community::class);
    }

    /**
     * The consultants that are interested in the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function interestedConsultants(): BelongsToMany
    {
        return $this->belongsToMany(Consultant::class, 'projects_of_interest');
    }

    /**
     * The consultants that are affiliated with the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function consultants(): BelongsToMany
    {
        return $this->belongsToMany(Consultant::class)
            ->withPivot('status');
    }

    /**
     * The consultants that are shortlisted for the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function shortlistedConsultants(): BelongsToMany
    {
        return $this->belongsToMany(Consultant::class)
            ->wherePivot('status', 'shortlisted');
    }

    /**
     * The consultants that have been requested for the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function requestedConsultants(): BelongsToMany
    {
        return $this->belongsToMany(Consultant::class)
            ->wherePivot('status', 'requested');
    }

    /**
     * The consultants that are shortlisted for the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function confirmedConsultants(): BelongsToMany
    {
        return $this->belongsToMany(Consultant::class)
            ->wherePivot('status', 'confirmed');
    }

    /**
     * The consultants that have exited the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function exitedConsultants(): BelongsToMany
    {
        return $this->belongsToMany(Consultant::class)
            ->wherePivot('status', 'exited');
    }

    /**
     * An aggregated list of lived experiences reflected in the consulting team.
     *
     * @return array
     */
    public function presentLivedExperiences()
    {
        return $this->consultants->pluck('livedExperiences')->flatten()->pluck('name')->unique()->toArray();
    }

    /**
     * An aggregated list of lived experiences not reflected in the consulting team.
     *
     * @return array
     */
    public function absentLivedExperiences(): array
    {
        $livedExperiences = LivedExperience::all()->pluck('name')->toArray();

        return array_diff($livedExperiences, $this->presentLivedExperiences());
    }

    /**
     * An aggregated list of communities reflected in the consulting team.
     *
     * @return array
     */
    public function presentCommunities(): array
    {
        return $this->consultants->pluck('communities')->flatten()->pluck('name')->unique()->toArray();
    }

    /**
     * An aggregated list of communities not reflected in the consulting team.
     *
     * @return array
     */
    public function absentCommunities(): array
    {
        $communities = Community::all()->pluck('name')->toArray();

        return array_diff($communities, $this->presentCommunities());
    }

    /**
     * An aggregated list of access requirements listed by the consulting team.
     *
     * @return array
     */
    public function accessRequirements(): array
    {
        return $this->consultants->pluck('accessSupports')->flatten()->sortBy('id')->pluck('name')->unique()->toArray();
    }

    /**
     * An aggregated list of regions reflected in the consulting team.
     *
     * @return array
     */
    public function presentRegions(): array
    {
        $regions = $this->consultants->pluck('region')->unique()->toArray();
        $present = [];

        if (in_array('BC', $regions)) {
            $present[] = __('West Coast');
        }

        if (in_array('AB', $regions) || in_array('SK', $regions) || in_array('MB', $regions)) {
            $present[] = __('Prairie Provinces');
        }

        if (in_array('ON', $regions) || in_array('QC', $regions)) {
            $present[] = __('Central Canada');
        }

        if (in_array('YT', $regions) || in_array('NT', $regions) || in_array('NU', $regions)) {
            $present[] = __('Northern Territories');
        }

        if (in_array('NB', $regions) || in_array('NS', $regions) || in_array('PE', $regions) || in_array('NL', $regions)) {
            $present[] = __('Atlantic Provinces');
        }

        return $present;
    }

    /**
     * An aggregated list of regions not reflected in the consulting team.
     *
     * @return array
     */
    public function absentRegions(): array
    {
        $regions = [
            __('West Coast'),
            __('Prairie Provinces'),
            __('Central Provinces'),
            __('Northern Territories'),
            __('Quebec'),
            __('Atlantic Provinces'),
        ];

        return array_diff($regions, $this->presentRegions());
    }

    /**
     * A collection of the project's reviews.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Calculate the average rating for a given criteria.
     *
     * @param string $key
     *
     * @return float|int The average rating rounded to the nearest 0.5.
     */
    public function averageRatingFor(string $key): float|int
    {
        return round(2 * $this->reviews->avg($key)) / 2;
    }

    /**
     * Calculate consultant retention.
     *
     * @return float The consultant retention as a decimal fraction of 1.
     */
    public function consultantRetention(): float
    {
        return round(count($this->confirmedConsultants) / (count($this->confirmedConsultants) + count($this->exitedConsultants)), 2);
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'entity_id',
        'start_date',
        'end_date',
        'published_at',
        'found_consultants',
        'confirmed_consultants',
        'scheduled_planning_meeting',
        'notified_of_planning_meeting',
        'prepared_project_orientation',
        'prepared_contractual_documents',
        'booked_access_services_for_planning',
        'finished_planning_meeting',
        'scheduled_consultation_meetings',
        'notified_of_consultation_meetings',
        'prepared_consultation_materials',
        'booked_access_services_for_consultations',
        'finished_consultation_meetings',
        'prepared_accessibility_plan',
        'prepared_follow_up_plan',
        'shared_plans_with_consultants',
        'published_accessibility_plan',
        'payment_negotiable',
        'goals',
        'impact',
        'out_of_scope',
        'virtual_consultation',
        'timeline',
        'payment_terms',
        'existing_clients',
        'prospective_clients',
        'employees',
        'priority_outreach',
        'regions',
        'locality',
        'location_description',
        'min',
        'max',
        'anything_else',
        'flexible_deadlines',
        'flexible_breaks',
    ];

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
     * The attributes that are transterms
     *
     * @var array
     */
    public $translatable = [
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
    public $substeps = [
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
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the project steps for entities.
     *
     * @return array
     */
    public function getEntitySteps()
    {
        return [
            1 => [
                'title' => __('Publish your project'),
            ],
            2 => [
                'title' => __('Build your consulting team'),
            ],
            3 => [
                'title' => __('Learn how to work together'),
                'subtitle' => __('Meet with the consultants and have a conversation with them about how you’d like to work together.'),
            ],
            4 => [
                'title' => __('Hold consultations'),
                'subtitle' => __('Have consultations with your consultants to work on your accessibility project.'),
            ],
            5 => [
                'title' => __('Write your report'),
            ],
        ];
    }

    /**
     * Get the project steps for consultants.
     *
     * @return array
     */
    public function getConsultantSteps()
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
                'title' => __('Review the reports'),
            ],
        ];
    }

    /**
     * Get the project substeps for entities.
     *
     * @return array
     */
    public function getEntitySubsteps()
    {
        return [
            1 => [
                1 => [
                    'link' => \localized_route('projects.edit', $this),
                    'label' => __('Publish project page'),
                    'description' => false,
                    'status' => $this->checkStatus('published') ? 'complete' : 'in-progress',
                ],
            ],
            2 => [
                1 => [
                    'link' => \localized_route('projects.find-interested-consultants', $this),
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
                    'description' => __('Pick a date and time to have this meeting with your consultants.'),
                    'status' => $this->scheduled_planning_meeting ?? null,
                ],
                2 => [
                    'link' => "#",
                    'label' => __('Contact consultant team'),
                    'description' => __('Contact your consultants about the meeting.'),
                    'status' => $this->notified_of_planning_meeting ?? null,
                ],
                3 => [
                    'link' => "#",
                    'label' => __('Prepare a project orientation'),
                    'description' => __('Provide your consultants with all the information they need about your project.'),
                    'status' => $this->prepared_project_orientation ?? null,
                ],
                4 => [
                    'link' => "#",
                    'label' => __('Prepare contracts and other legal documents'),
                    'description' => __('Provide your consultants with any contracts they may need to sign.'),
                    'status' => $this->prepared_contractual_documents ?? null,
                ],
                5 => [
                    'link' => "#",
                    'label' => __('Provide access accommodations and book service providers'),
                    'description' => __('Make sure the access needs of your consultants are met.'),
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
                    'description' => __('Pick the dates and times to have these meetings with your consultants.'),
                    'status' => $this->scheduled_consultation_meetings ?? null,
                ],
                2 => [
                    'link' => "#",
                    'label' => __('Contact consultant team'),
                    'description' => __('Contact your consultants about the meeting.'),
                    'status' => $this->notified_of_consultation_meetings ?? null,
                ],
                3 => [
                    'link' => "#",
                    'label' => __('Prepare consultation materials'),
                    'description' => __('Provide your consultants with all the information they need for the consultation.'),
                    'status' => $this->prepared_consultation_materials ?? null,
                ],
                4 => [
                    'link' => "#",
                    'label' => __('Provide access accommodations and book service providers'),
                    'description' => __('Make sure the access needs of your consultants are met.'),
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
                    'description' => false,
                    'status' => $this->prepared_accessibility_plan ?? null,
                ],
                2 => [
                    'link' => "#",
                    'label' => __('Prepare your follow-up plan'),
                    'description' => false,
                    'status' => $this->prepared_follow_up_plan ?? null,
                ],
                3 => [
                    'link' => "#",
                    'label' => __('Share your accessibility plan and follow-up plan with your consultant team'),
                    'description' => false,
                    'status' => $this->shared_plans_with_consultants ?? null,
                ],
                4 => [
                    'link' => "#",
                    'label' => __('Publish your accessibility plan (optional)'),
                    'description' => false,
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
    public function getConsultantSubsteps()
    {
        return [
            1 => [
                1 => [
                    'link' => "#",
                    'label' => __('Schedule the meeting'),
                    'description' => __('Pick a date and time to have this meeting with your consultants.'),
                    'status' => $this->scheduled_planning_meeting ?? null,
                ],
                2 => [
                    'link' => "#",
                    'label' => __('Contact consultant team'),
                    'description' => __('Contact your consultants about the meeting.'),
                    'status' => $this->notified_of_planning_meeting ?? null,
                ],
                3 => [
                    'link' => "#",
                    'label' => __('Prepare a project orientation'),
                    'description' => __('Provide your consultants with all the information they need about your project.'),
                    'status' => $this->prepared_project_orientation ?? null,
                ],
                4 => [
                    'link' => "#",
                    'label' => __('Prepare contracts and other legal documents'),
                    'description' => __('Provide your consultants with any contracts they may need to sign.'),
                    'status' => $this->prepared_contractual_documents ?? null,
                ],
                5 => [
                    'link' => "#",
                    'label' => __('Provide access accommodations and book service providers'),
                    'description' => __('Make sure the access needs of your consultants are met.'),
                    'status' => $this->booked_access_services_for_planning ?? null,
                ],
                6 => [
                    'link' => "#",
                    'label' => __('Hold the meeting'),
                    'description' => false,
                    'status' => $this->finished_planning_meeting ?? null,
                ],
            ],
            2 => [
                1 => [
                    'link' => "#",
                    'label' => __('Schedule the meetings'),
                    'description' => __('Pick the dates and times to have these meetings with your consultants.'),
                    'status' => $this->scheduled_consultation_meetings ?? null,
                ],
                2 => [
                    'link' => "#",
                    'label' => __('Contact consultant team'),
                    'description' => __('Contact your consultants about the meeting.'),
                    'status' => $this->notified_of_consultation_meetings ?? null,
                ],
                3 => [
                    'link' => "#",
                    'label' => __('Prepare consultation materials'),
                    'description' => __('Provide your consultants with all the information they need for the consultation.'),
                    'status' => $this->prepared_consultation_materials ?? null,
                ],
                4 => [
                    'link' => "#",
                    'label' => __('Provide access accommodations and book service providers'),
                    'description' => __('Make sure the access needs of your consultants are met.'),
                    'status' => $this->booked_access_services_for_consultations ?? null,
                ],
                5 => [
                    'link' => "#",
                    'label' => __('Hold the meetings'),
                    'description' => false,
                    'status' => $this->finished_consultation_meetings ?? null,
                ],
            ],
            3 => [
                1 => [
                    'link' => "#",
                    'label' => __('Prepare your accessibility plan'),
                    'description' => false,
                    'status' => $this->prepared_accessibility_plan ?? null,
                ],
                2 => [
                    'link' => "#",
                    'label' => __('Prepare your follow-up plan'),
                    'description' => false,
                    'status' => $this->prepared_follow_up_plan ?? null,
                ],
                3 => [
                    'link' => "#",
                    'label' => __('Share your accessibility plan and follow-up plan with your consultant team'),
                    'description' => false,
                    'status' => $this->shared_plans_with_consultants ?? null,
                ],
                4 => [
                    'link' => "#",
                    'label' => __('Publish your accessibility plan (optional)'),
                    'description' => false,
                    'status' => $this->published_accessibility_plan ?? null,
                ],
            ],
        ];
    }

    /**
     * Has the project started?
     *
     * @return bool
     */
    public function started()
    {
        return $this->start_date < Carbon::now();
    }

    /**
     * Has the project completed?
     *
     * @return bool
     */
    public function completed()
    {
        return $this->hasReportedFindings();
    }

    /**
     * Get the project's timespan.
     *
     * @return string
     */
    public function timespan()
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

    public function hasBuiltTeam()
    {
        return ! is_null($this->found_consultants)
            && ! is_null($this->confirmed_consultants);
    }

    public function hasLearnedHowToWorkTogether()
    {
        return ! is_null($this->scheduled_planning_meeting)
            && ! is_null($this->notified_of_planning_meeting)
            && ! is_null($this->prepared_project_orientation)
            && ! is_null($this->prepared_contractual_documents)
            && ! is_null($this->booked_access_services_for_planning)
            && ! is_null($this->finished_planning_meeting);
    }

    public function hasHeldConsultations()
    {
        return ! is_null($this->scheduled_consultation_meetings)
            && ! is_null($this->notified_of_consultation_meetings)
            && ! is_null($this->prepared_consultation_materials)
            && ! is_null($this->booked_access_services_for_consultations)
            && ! is_null($this->finished_consultation_meetings);
    }

    public function hasReportedFindings()
    {
        return ! is_null($this->prepared_accessibility_plan)
            && ! is_null($this->prepared_follow_up_plan)
            && ! is_null($this->shared_plans_with_consultants);
    }

    public function currentEntityStep()
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

    public function currentConsultantStep()
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

    public function step()
    {
        switch ($this->currentEntityStep()) {
            case 1:
                return __('Publishing project');

                break;
            case 2:
                return __('Building consulting team');

                break;
            case 3:
                return __('Learning how to work together');

                break;
            case 4:
                return __('Holding consultations');

                break;
            case 5:
                return __('Writing report');

                break;
            case 6:
                return __('Completed');

                break;
        };
    }

    /**
     * How far along is a given step?
     *
     * @param int $step
     *
     * @return float
     */
    public function getProgress(int $step)
    {
        $progress = 0;

        if ($step === 1) {
            if ($this->checkStatus('published')) {
                $progress = 1;
            }
        } else {
            foreach ($this->substeps[$step] as $key => $substep) {
                if (! is_null($this[$substep]) && $this[$substep]) {
                    $progress++;
                }
            }
        }

        return $progress;
    }

    /**
     * Get the entity that owns the project.
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    /**
     * The impacts that belong to the project.
     */
    public function impacts(): BelongsToMany
    {
        return $this->belongsToMany(Impact::class);
    }

    /**
     * The impacts that belong to the project.
     */
    public function sectors(): BelongsToMany
    {
        return $this->entity->belongsToMany(Sector::class);
    }

    /**
     * The payment methods that belong to the project.
     */
    public function paymentMethods(): BelongsToMany
    {
        return $this->belongsToMany(PaymentMethod::class);
    }

    /**
     * The consulting methods that belong to the project.
     */
    public function consultingMethods(): BelongsToMany
    {
        return $this->belongsToMany(ConsultingMethod::class);
    }

    /**
     * The access supports that belong to the project.
     */
    public function accessSupports(): BelongsToMany
    {
        return $this->belongsToMany(AccessSupport::class);
    }

    /**
     * The access supports that belong to the project.
     */
    public function communicationTools(): BelongsToMany
    {
        return $this->belongsToMany(CommunicationTool::class);
    }

    /**
     * The communities that belong to the project.
     */
    public function communities(): BelongsToMany
    {
        return $this->belongsToMany(Community::class);
    }

    /**
     * The consultants that are interested in the project.
     */
    public function interestedConsultants(): BelongsToMany
    {
        return $this->belongsToMany(Consultant::class, 'projects_of_interest');
    }

    /**
     * The consultants that belong to the project.
     */
    public function consultants(): BelongsToMany
    {
        return $this->belongsToMany(Consultant::class)
            ->withPivot('status');
    }

    /**
     * The consultants that are shortlisted for the project.
     */
    public function shortlistedConsultants(): BelongsToMany
    {
        return $this->belongsToMany(Consultant::class)
            ->wherePivot('status', 'shortlisted');
    }

    /**
     * The consultants that have been requested for the project.
     */
    public function requestedConsultants(): BelongsToMany
    {
        return $this->belongsToMany(Consultant::class)
            ->wherePivot('status', 'requested');
    }

    /**
     * The consultants that are shortlisted for the project.
     */
    public function confirmedConsultants(): BelongsToMany
    {
        return $this->belongsToMany(Consultant::class)
            ->wherePivot('status', 'confirmed');
    }

    public function presentLivedExperiences()
    {
        return $this->consultants->pluck('livedExperiences')->flatten()->pluck('name')->unique()->toArray();
    }

    public function absentLivedExperiences()
    {
        $livedExperiences = LivedExperience::all()->pluck('name')->toArray();

        return array_diff($livedExperiences, $this->presentLivedExperiences());
    }

    public function presentCommunities()
    {
        return $this->consultants->pluck('communities')->flatten()->pluck('name')->unique()->toArray();
    }

    public function absentCommunities()
    {
        $communities = Community::all()->pluck('name')->toArray();

        return array_diff($communities, $this->presentCommunities());
    }

    public function accessRequirements()
    {
        return $this->consultants->pluck('accessSupports')->flatten()->sortBy('id')->pluck('name')->unique()->toArray();
    }

    public function presentRegions()
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

    public function absentRegions()
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
}

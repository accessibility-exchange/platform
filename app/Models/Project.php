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
        'regions',
        'payment_methods',
        'payment_negotiable',
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
        'payment_methods' => 'array',
        'payment_negotiable' => 'boolean',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [];

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
            if ($this->start_date->format('Y') === $this->end_date->format('Y')) {
                return $this->start_date->format('F') . '&mdash;' . $this->end_date->format('F Y');
            } else {
                return $this->start_date->format('F Y') . '&mdash;' . $this->end_date->format('F Y');
            }
        }

        return $this->start_date > Carbon::now()
            ? __('project.starting', ['date' => $this->start_date->format('F Y')])
            : __('project.started', ['date' => $this->start_date->format('F Y')]);
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

    public function currentStep()
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

    public function step()
    {
        switch ($this->currentStep()) {
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
     * The consultants that belong to the project.
     */
    public function consultants(): BelongsToMany
    {
        return $this->belongsToMany(Consultant::class)
            ->as('relationship')
            ->withPivot('status');
    }

    /**
     * The consultants that are saved to the project.
     */
    public function savedConsultants(): BelongsToMany
    {
        return $this->belongsToMany(Consultant::class)
            ->wherePivot('status', 'saved');
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
}

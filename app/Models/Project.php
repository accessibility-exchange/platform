<?php

namespace App\Models;

use App\States\Project\Completed;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [];

    /**
     * @var bool|null
     */
    public $found_consultants;

    /**
     * @var bool|null
     */
    public $confirmed_consultants;

    /**
     * @var bool|null
     */
    public $scheduled_planning_meeting;

    /**
     * @var bool|null
     */
    public $notified_of_planning_meeting;

    /**
     * @var bool|null
     */
    public $prepared_project_orientation;

    /**
     * @var bool|null
     */
    public $prepared_contractual_documents;

    /**
     * @var bool|null
     */
    public $booked_access_services_for_planning;

    /**
     * @var bool|null
     */
    public $finished_planning_meeting;

    /**
     * @var bool|null
     */
    public $scheduled_consultation_meetings;

    /**
     * @var bool|null
     */
    public $notified_of_consultation_meetings;

    /**
     * @var bool|null
     */
    public $prepared_consultation_materials;

    /**
     * @var bool|null
     */
    public $booked_access_services_for_consultations;

    /**
     * @var bool|null
     */
    public $finished_consultation_meetings;

    /**
     * @var bool|null
     */
    public $prepared_accessibility_plan;

    /**
     * @var bool|null
     */
    public $prepared_follow_up_plan;

    /**
     * @var bool|null
     */
    public $shared_plans_with_consultants;

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
     * Return the substep count for a particular step.
     *
     * @param int $step The current step.
     * @return int The number of substeps for this step.
     */
    public function substepCount($step)
    {
        return count($this->substeps[$step]);
    }

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
        return $this->found_consultants
            && $this->confirmed_consultants;
    }

    public function hasLearnedHowToWorkTogether()
    {
        return $this->scheduled_planning_meeting
            && $this->notified_of_planning_meeting
            && $this->prepared_project_orientation
            && $this->prepared_contractual_documents
            && $this->booked_access_services_for_planning
            && $this->finished_planning_meeting;
    }

    public function hasHeldConsultations()
    {
        return $this->scheduled_consultation_meetings
            && $this->notified_of_consultation_meetings
            && $this->prepared_consultation_materials
            && $this->booked_access_services_for_consultations
            && $this->finished_consultation_meetings;
    }

    public function hasReportedFindings()
    {
        return $this->prepared_accessibility_plan
            && $this->prepared_follow_up_plan
            && $this->shared_plans_with_consultants;
    }

    public function currentStep()
    {
        $step = 1;

        if ($this->hasHeldConsultations()) {
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
     * Is a given step's substep completed?
     *
     * @param string $substep
     *
     * @return bool|null
     */
    public function isSubstepComplete($substep)
    {
        return $this[$substep] ?? null;
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
}

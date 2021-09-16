<?php

namespace App\Models;

use App\States\Project\ProjectState;
use App\States\PublicationState;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Spatie\ModelStates\HasStates;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Project extends Model
{
    use HasFactory;
    use HasSlug;
    use HasStates;
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
        'progress->1',
        'progress->2',
        'progress->3',
        'progress->4',
        'progress->5',
        'progress->6',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
        'progress' => 'array',
        'state' => ProjectState::class,
        'publication_state' => PublicationState::class,
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [];

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
     * Is the project active?
     *
     * @return bool
     */
    public function active()
    {
        return in_array($this->state->slug(), [
            'preparing',
            'confirmating_consultants',
            'negotiating_consultations',
            'holding_consultations',
            'writing_report',
        ]);
    }

    /**
     * Has the project completed?
     *
     * @return bool
     */
    public function completed()
    {
        return $this->state->slug() === 'completed';
    }

    /**
     * Get the project's timespan.
     *
     * @return string
     */
    public function timespan()
    {
        if ($this->start_date && $this->end_date) {
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

    /**
     * Is a given step's substep completed?
     *
     * @param int $step
     * @param int $substep
     *
     * @return bool
     */
    public function isComplete($step, $substep)
    {
        if ($this->progress && isset($this->progress[$step])) {
            return in_array($substep, $this->progress[$step]);
        }

        return false;
    }

    /**
     * Get the entity that owns the project.
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }
}

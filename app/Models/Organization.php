<?php

namespace App\Models;

use Carbon\Carbon;
use Hearth\Traits\HasInvitations;
use Hearth\Traits\HasMembers;
use Hearth\Traits\HasRequestsToJoin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Organization extends Model
{
    use CascadesDeletes;
    use HasFactory;
    use HasInvitations;
    use HasMembers;
    use HasRequestsToJoin;
    use HasTranslations;
    use HasTranslatableSlug;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'locality',
        'region',
    ];

    /**
     * The relationships that should be deleted when an organization is deleted.
     *
     * @var array
     */
    protected mixed $cascadeDeletes = [
        'users',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array<string>
     */
    public array $translatable = [
        'name',
        'slug',
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
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the route prefix for the model.
     *
     * @return string
     */
    public function getRoutePrefix(): string
    {
        return 'organizations';
    }

    /**
     * Get the projects that belong to this organization.
     *
     * @return MorphMany
     */
    public function projects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->orderBy('start_date');
    }

    /**
     * Get the projects that belong to this organization that are in progress.
     *
     * @return MorphMany
     */
    public function inProgressProjects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->whereDate('start_date', '<=', Carbon::now())
            ->where(function ($query) {
                $query->whereDate('end_date', '>=', Carbon::now())
                    ->orWhereNull('end_date');
            })
            ->orderBy('start_date');
    }

    /**
     * Get the projects that belong to this organization that have been completed.
     *
     * @return MorphMany
     */
    public function completedProjects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->whereDate('end_date', '<', Carbon::now())
            ->orderBy('start_date');
    }

    /**
     * Get the projects that belong to this organization that haven't started yet.
     *
     * @return MorphMany
     */
    public function upcomingProjects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->whereDate('start_date', '>', Carbon::now())
            ->orderBy('start_date');
    }
}

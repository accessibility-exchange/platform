<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Translatable\HasTranslations;

class Engagement extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'name',
        'project_id',
        'goals',
        'recruitment',
    ];

    protected $casts = [
        'name' => 'array',
        'goals' => 'array',
    ];

    public array $translatable = [
        'name',
        'goals',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Individual::class)->withPivot('status');
    }

    public function confirmedParticipants(): BelongsToMany
    {
        return $this->belongsToMany(Individual::class)->wherePivot('status', 'confirmed');
    }

    public function organizationalParticipants(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class)->withPivot('status');
    }

    public function confirmedOrganizationalParticipants(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class)->wherePivot('status', 'confirmed');
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Individual::class, 'individual_consultant_id');
    }

    public function organizationalConsultant(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organizational_consultant_id');
    }

    public function connector(): BelongsTo
    {
        return $this->belongsTo(Individual::class, 'individual_connector_id');
    }

    public function organizationalConnector(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organizational_connector_id');
    }

    public function matchingStrategy(): MorphOne
    {
        return $this->morphOne(MatchingStrategy::class, 'matchable');
    }
}

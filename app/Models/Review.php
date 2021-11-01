<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'consultant_id',
        'project_id',
        'body',
        'met_access_needs',
        'open_to_feedback',
        'kind_and_patient',
        'valued_input',
        'respectful_of_identity',
        'sensitive_to_comfort_levels',
    ];

    /**
     * Get the parent consultant model.
     */
    public function consultant()
    {
        return $this->belongsTo(Consultant::class);
    }

    /**
     * Get the parent project model.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

<?php

namespace App\Models;

use App\Observers\DocumentObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;
use Spatie\Translatable\HasTranslations;

#[ObservedBy([DocumentObserver::class])]
class Document extends Model
{
    use CascadesDeletes;
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
    ];

    public array $translatable = [
        'name',
        'description',
    ];

    protected $cascadeDeletes = [
        'revisions',
    ];

    public function revisions(): HasMany
    {
        return $this->hasMany(Revision::class);
    }

    public function getRevisionFilename(Carbon $datetime, string $language, string $extension, ?string $document = null): string
    {
        if (is_null($document)) {
            $document = Str::slug($this->getTranslation('name', $language));
        }
        $date = $datetime->format('Y-m-d');

        return "$document-$date-$language.$extension";
    }
}

<?php

namespace App\Traits;

use App\Enums\ProvinceOrTerritory;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasDisplayRegion
{
    public function displayRegion(): Attribute
    {
        return Attribute::make(
            get: fn () => ProvinceOrTerritory::labels()[$this->region],
        );
    }
}

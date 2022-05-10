<?php

use App\Models\Sector;
use Illuminate\Support\Collection;

it('collection can be prepared for form display', function () {
    $sector = Sector::create([
        'name' => 'Example',
        'description' => 'This is the description.',
    ]);

    $collection = new Collection([$sector]);

    $preparedCollection = $collection->prepareForForm();

    expect($preparedCollection)
        ->toBeArray()
        ->toHaveCount(1)
        ->toHaveKey($sector->id);

    expect($preparedCollection[$sector->id])->toEqual([
        'label' => 'Example',
        'hint' => 'This is the description.',
    ]);
});

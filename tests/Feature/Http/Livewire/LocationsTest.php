<?php

use App\Enums\ProvinceOrTerritory;
use App\Http\Livewire\Locations;
use function Pest\Faker\faker;

test('locations can be added', function () {
    $this->livewire(Locations::class, ['locations' => []])
        ->call('addLocation')
        ->assertSet('locations', [['region' => '', 'locality' => '']]);
});

test('no more than 10 locations can be added', function () {
    $locations = [];

    for ($i = 0; $i < 10; $i++) {
        $locations[] = [
            'region' => faker()->randomElement(array_column(ProvinceOrTerritory::cases(), 'value')),
            'locality' => faker()->city,
        ];
    }

    $this->livewire(Locations::class, ['locations' => $locations])
        ->call('addLocation')
        ->assertCount('locations', 10);
});

test('location can be removed', function () {
    $this->livewire(Locations::class, ['locations' => [['region' => 'NS', 'locality' => 'Halifax']]])
        ->call('removeLocation', 0)
        ->assertSet('locations', []);
});

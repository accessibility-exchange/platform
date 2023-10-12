<?php

use App\Http\Livewire\TeamTrainings;

use function Pest\Livewire\livewire;

test('training can be added', function () {
    livewire(TeamTrainings::class, ['trainings' => [['name' => 'Basic First Aid', 'date' => '2022-04-05', 'trainer_name' => 'Example Training Ltd.', 'trainer_url' => 'https://example.com']]])
        ->call('addTraining')
        ->assertSet('trainings', [['name' => 'Basic First Aid', 'date' => '2022-04-05', 'trainer_name' => 'Example Training Ltd.', 'trainer_url' => 'https://example.com'], ['name' => '', 'date' => '', 'trainer_name' => '', 'trainer_url' => '']]);
});

test('no more than five trainings can be added', function () {
    livewire(TeamTrainings::class, ['trainings' => [
        ['name' => 'Basic First Aid', 'date' => '2022-04-05', 'trainer_name' => 'Example Training Ltd.', 'trainer_url' => 'https://example.com'],
        ['name' => 'Basic First Aid', 'date' => '2022-04-05', 'trainer_name' => 'Example Training Ltd.', 'trainer_url' => 'https://example.com'],
        ['name' => 'Basic First Aid', 'date' => '2022-04-05', 'trainer_name' => 'Example Training Ltd.', 'trainer_url' => 'https://example.com'],
        ['name' => 'Basic First Aid', 'date' => '2022-04-05', 'trainer_name' => 'Example Training Ltd.', 'trainer_url' => 'https://example.com'],
        ['name' => 'Basic First Aid', 'date' => '2022-04-05', 'trainer_name' => 'Example Training Ltd.', 'trainer_url' => 'https://example.com'],
    ]])
        ->call('addTraining')
        ->assertCount('trainings', 5);
});

test('training can be removed', function () {
    livewire(TeamTrainings::class, ['trainings' => [['name' => 'Basic First Aid', 'date' => '2022-04-05', 'trainer_name' => 'Example Training Ltd.', 'trainer_url' => 'https://example.com']]])
        ->call('removeTraining', 0)
        ->assertSet('trainings', []);
});

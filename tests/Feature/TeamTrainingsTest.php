<?php

namespace Tests\Feature;

use App\Http\Livewire\TeamTrainings;
use Livewire\Livewire;
use Tests\TestCase;

class TeamTrainingsTest extends TestCase
{
    public function test_training_can_be_added(): void
    {
        Livewire::test(TeamTrainings::class, ['trainings' => [['name' => 'Basic First Aid', 'date' => '2022-04-05', 'trainer_name' => 'Example Training Ltd.', 'trainer_url' => 'https://example.com']]])
            ->call('addTraining')
            ->assertSet('trainings', [['name' => 'Basic First Aid', 'date' => '2022-04-05', 'trainer_name' => 'Example Training Ltd.', 'trainer_url' => 'https://example.com'], ['name' => '', 'date' => '', 'trainer_name' => '', 'trainer_url' => '']]);
    }

    public function test_no_more_than_five_trainings_can_be_added(): void
    {
        Livewire::test(TeamTrainings::class, ['trainings' => [
            ['name' => 'Basic First Aid', 'date' => '2022-04-05', 'trainer_name' => 'Example Training Ltd.', 'trainer_url' => 'https://example.com'],
            ['name' => 'Basic First Aid', 'date' => '2022-04-05', 'trainer_name' => 'Example Training Ltd.', 'trainer_url' => 'https://example.com'],
            ['name' => 'Basic First Aid', 'date' => '2022-04-05', 'trainer_name' => 'Example Training Ltd.', 'trainer_url' => 'https://example.com'],
            ['name' => 'Basic First Aid', 'date' => '2022-04-05', 'trainer_name' => 'Example Training Ltd.', 'trainer_url' => 'https://example.com'],
            ['name' => 'Basic First Aid', 'date' => '2022-04-05', 'trainer_name' => 'Example Training Ltd.', 'trainer_url' => 'https://example.com'],
        ]])
            ->call('addTraining')
            ->assertCount('trainings', 5);
    }

    public function test_training_can_be_removed(): void
    {
        Livewire::test(TeamTrainings::class, ['trainings' => [['name' => 'Basic First Aid', 'date' => '2022-04-05', 'trainer_name' => 'Example Training Ltd.', 'trainer_url' => 'https://example.com']]])
            ->call('removeTraining', 0)
            ->assertSet('trainings', []);
    }
}

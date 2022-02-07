<?php

namespace Tests\Feature;

use App\Http\Livewire\WorkAndVolunteerExperiences;
use Livewire\Livewire;
use Tests\TestCase;

class WorkAndVolunteerExperiencesTest extends TestCase
{
    public function test_experience_can_be_added(): void
    {
        Livewire::test(WorkAndVolunteerExperiences::class, ['experiences' => []])
            ->call('addExperience')
            ->assertSet('experiences', [['title' => '', 'start_year' => '', 'end_year' => '', 'current' => false]]);
    }

    public function test_no_more_than_twenty_experiences_can_be_added(): void
    {
        Livewire::test(WorkAndVolunteerExperiences::class, ['experiences' => [
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ]])
            ->call('addExperience')
            ->assertCount('experiences', 20);
    }

    public function test_experience_can_be_removed(): void
    {
        Livewire::test(WorkAndVolunteerExperiences::class, ['experiences' => [
            ['title' => 'Some job', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ]])
            ->call('removeExperience', 0)
            ->assertSet('experiences', []);
    }
}

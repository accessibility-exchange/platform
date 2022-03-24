<?php

namespace Tests\Feature;

use App\Models\Engagement;
use App\Models\Entity;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EngagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_with_entity_admin_role_can_create_engagements()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support  is not enabled.');
        }

        $user = User::factory()->create();
        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();
        $project = Project::factory()->create([
            'entity_id' => $entity->id,
        ]);

        $response = $this->actingAs($user)->get(localized_route('engagements.create', $project));
        $response->assertOk();

        $response = $this->actingAs($user)->post(localized_route('engagements.create', $project), [
            'project_id' => $project->id,
            'name' => ['en' => 'Test Engagement'],
            'goals' => ['en' => 'This is what we want to do.'],
            'recruitment' => 'automatic',
        ]);

        $engagement = Engagement::where('name->en', 'Test Engagement')->get()->first();

        $url = localized_route('engagements.manage', ['project' => $project, 'engagement' => $engagement->id]);

        $response->assertSessionHasNoErrors();

        $response->assertRedirect($url);
    }
}

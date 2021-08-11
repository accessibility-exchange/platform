<?php

namespace Tests\Feature;

use App\Models\Entity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_with_entity_admin_role_can_create_projects()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create();
        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->actingAs($user)->get(localized_route('projects.create', $entity));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->post(localized_route('projects.create', $entity), [
            'entity_id' => $entity->id,
            'name' => 'Test Project',
            'start_date' => '2021-01-01',
            'end_date' => '2021-12-31',
        ]);

        $url = localized_route('projects.show', ['project' => 'test-project']);

        $response->assertSessionHasNoErrors();

        $response->assertRedirect($url);
    }
}

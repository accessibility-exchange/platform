<?php

namespace Tests\Feature;

use App\Models\Entity;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_with_entity_admin_role_can_create_projects()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support  is not enabled.');
        }

        $user = User::factory()->create();
        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->actingAs($user)->get(localized_route('projects.create', $entity));
        $response->assertOk();

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

    public function test_users_without_entity_admin_role_cannot_create_projects()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $response = $this->actingAs($user)->get(localized_route('projects.create', $entity));
        $response->assertForbidden();

        $response = $this->actingAs($other_user)->get(localized_route('projects.create', $entity));
        $response->assertForbidden();
    }

    public function test_users_can_view_projects()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support  is not enabled.');
        }

        $user = User::factory()->create();
        $entity = Entity::factory()->create();
        $project = Project::factory()->create([
            'entity_id' => $entity->id,
        ]);

        $response = $this->actingAs($user)->get(localized_route('projects.index'));
        $response->assertOk();

        $response = $this->actingAs($user)->get(localized_route('projects.entity-index', $entity));
        $response->assertOk();

        $response = $this->actingAs($user)->get(localized_route('projects.show', $project));
        $response->assertOk();
    }

    public function test_guests_cannot_view_projects()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support  is not enabled.');
        }

        $entity = Entity::factory()->create();
        $project = Project::factory()->create([
            'entity_id' => $entity->id,
        ]);

        $response = $this->get(localized_route('projects.index'));
        $response->assertRedirect(localized_route('login'));

        $response = $this->get(localized_route('projects.entity-index', $entity));
        $response->assertRedirect(localized_route('login'));

        $response = $this->get(localized_route('projects.show', $project));
        $response->assertRedirect(localized_route('login'));
    }

    public function test_users_with_entity_admin_role_can_edit_projects()
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

        $response = $this->actingAs($user)->get(localized_route('projects.edit', $project));
        $response->assertOk();

        $response = $this->actingAs($user)->put(localized_route('projects.update', $project), [
            'name' => 'My renamed accessibility project',
            'start_date' => $project->start_date,
            'end_date' => null,
        ]);

        $updated_project = Project::where('name', 'My renamed accessibility project')->first();

        $response->assertRedirect(localized_route('projects.show', $updated_project));
    }

    public function test_users_without_entity_admin_role_cannot_edit_projects()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();
        $project = Project::factory()->create([
            'entity_id' => $entity->id,
        ]);

        $response = $this->actingAs($user)->get(localized_route('projects.edit', $project));
        $response->assertForbidden();

        $response = $this->actingAs($user)->put(localized_route('projects.update', $project), [
            'name' => 'My updated project name',
            'start_date' => $project->start_date,
            'end_date' => null,
        ]);
        $response->assertForbidden();

        $response = $this->actingAs($other_user)->get(localized_route('projects.edit', $project));
        $response->assertForbidden();

        $response = $this->actingAs($other_user)->put(localized_route('projects.update', $project), [
            'name' => 'My updated project name',
            'start_date' => $project->start_date,
            'end_date' => null,
        ]);
        $response->assertForbidden();
    }
}

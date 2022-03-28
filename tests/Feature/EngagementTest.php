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

    public function test_users_without_entity_admin_role_cannot_create_engagements()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support  is not enabled.');
        }

        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();
        $project = Project::factory()->create([
            'entity_id' => $entity->id,
        ]);

        $response = $this->actingAs($user)->get(localized_route('engagements.create', $project));
        $response->assertForbidden();

        $response = $this->actingAs($other_user)->get(localized_route('engagements.create', $project));
        $response->assertForbidden();
    }

    public function test_users_can_view_engagements()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support  is not enabled.');
        }

        $user = User::factory()->create();
        $engagement = Engagement::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('engagements.show', ['project' => $engagement->project, 'engagement' => $engagement->id]));
        $response->assertOk();
    }

    public function test_guests_cannot_view_engagements()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support  is not enabled.');
        }

        $engagement = Engagement::factory()->create();

        $response = $this->get(localized_route('engagements.show', ['project' => $engagement->project, 'engagement' => $engagement->id]));
        $response->assertRedirect(localized_route('login'));
    }

    public function test_users_with_entity_admin_role_can_edit_engagements()
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
        $engagement = Engagement::factory()->create([
            'project_id' => $project->id,
        ]);

        $response = $this->actingAs($user)->get(localized_route('engagements.edit', ['project' => $project, 'engagement' => $engagement]));
        $response->assertOk();

        $response = $this->actingAs($user)->put(localized_route('engagements.update', ['project' => $project, 'engagement' => $engagement]), [
            'name' => ['en' => 'My renamed engagement'],
            'recruitment' => 'automatic',
        ]);

        $updated_engagement = Engagement::where('name->en', 'My renamed engagement')->first();

        $response->assertRedirect(localized_route('engagements.manage', ['project' => $project, 'engagement' => $engagement]));
    }

    public function test_users_without_entity_admin_role_cannot_edit_engagements()
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
        $engagement = Engagement::factory()->create([
            'project_id' => $project->id,
        ]);

        $response = $this->actingAs($user)->get(localized_route('engagements.edit', ['project' => $project, 'engagement' => $engagement]));
        $response->assertForbidden();

        $response = $this->actingAs($user)->put(localized_route('engagements.update', ['project' => $project, 'engagement' => $engagement]), [
            'name' => ['en' => 'My renamed engagement'],
        ]);
        $response->assertForbidden();

        $response = $this->actingAs($other_user)->get(localized_route('engagements.edit', ['project' => $project, 'engagement' => $engagement]));
        $response->assertForbidden();

        $response = $this->actingAs($other_user)->put(localized_route('engagements.update', ['project' => $project, 'engagement' => $engagement]), [
            'name' => ['en' => 'My renamed engagement'],
        ]);
        $response->assertForbidden();
    }

    public function test_users_with_entity_admin_role_can_manage_engagements()
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
        $engagement = Engagement::factory()->create([
            'project_id' => $project->id,
        ]);

        $response = $this->actingAs($user)->get(localized_route('engagements.manage', ['project' => $project, 'engagement' => $engagement]));
        $response->assertOk();
    }

    public function test_users_without_entity_admin_role_cannot_manage_engagements()
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
        $engagement = Engagement::factory()->create([
            'project_id' => $project->id,
        ]);

        $response = $this->actingAs($user)->get(localized_route('engagements.manage', ['project' => $project, 'engagement' => $engagement]));
        $response->assertForbidden();

        $response = $this->actingAs($other_user)->get(localized_route('engagements.manage', ['project' => $project, 'engagement' => $engagement]));
        $response->assertForbidden();
    }
}

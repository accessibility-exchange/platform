<?php

namespace Tests\Feature;

use App\Models\CommunityMember;
use App\Models\Entity;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
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
            'name' => ['en' => 'Test Project'],
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

    public function test_projects_can_be_published_and_unpublished()
    {
        $user = User::factory()->create();
        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();
        $project = Project::factory()->create([
            'entity_id' => $entity->id,
        ]);

        $response = $this->actingAs($user)->from(localized_route('projects.show', $project))->put(localized_route('projects.update-publication-status', $project), [
            'publish' => true,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('projects.show', $project));
        $this->assertTrue($project->checkStatus('published'));

        $response = $this->actingAs($user)->from(localized_route('projects.show', $project))->put(localized_route('projects.update-publication-status', $project), [
            'unpublish' => true,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('projects.show', $project));

        $project = $project->fresh();

        $this->assertTrue($project->checkStatus('draft'));

        $response = $this->actingAs($user)->get(localized_route('projects.show', $project));
        $response->assertSee('draft');
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

        $updated_project = Project::where('name->en', 'My renamed accessibility project')->first();

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

    public function test_users_with_entity_admin_role_can_delete_projects()
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

        $response = $this->actingAs($user)->from(localized_route('projects.edit', $project))->delete(localized_route('projects.destroy', $project), [
            'current_password' => 'password',
        ]);

        $response->assertRedirect(localized_route('dashboard'));
    }

    public function test_users_without_entity_admin_role_cannot_delete_projects()
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

        $response = $this->actingAs($user)->from(localized_route('dashboard'))->delete(localized_route('projects.destroy', $project), [
            'current_password' => 'password',
        ]);
        $response->assertForbidden();

        $response = $this->actingAs($other_user)->from(localized_route('dashboard'))->delete(localized_route('projects.destroy', $project), [
            'current_password' => 'password',
        ]);
        $response->assertForbidden();
    }

    public function test_users_with_entity_admin_role_can_create_project_updates()
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

        $response = $this->actingAs($user)->get(localized_route('projects.create-update', $project));
        $response->assertOk();
    }

    public function test_projects_appear_in_chronological_groups()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support  is not enabled.');
        }

        $entity = Entity::factory()->create();
        $past_project = Project::factory()->create([
            'entity_id' => $entity->id,
            'start_date' => Carbon::now()->subMonths(4)->format('Y-m-d'),
            'end_date' => Carbon::now()->subMonths(1)->format('Y-m-d'),
        ]);
        $current_project = Project::factory()->create([
            'entity_id' => $entity->id,
            'start_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
            'end_date' => Carbon::now()->addMonths(3)->format('Y-m-d'),
        ]);
        $future_project = Project::factory()->create([
            'entity_id' => $entity->id,
            'start_date' => Carbon::now()->addMonths(1)->format('Y-m-d'),
            'end_date' => Carbon::now()->addMonths(4)->format('Y-m-d'),
        ]);

        $this->assertEquals(count($entity->projects), 3);
        $this->assertEquals(count($entity->pastProjects), 1);
        $this->assertEquals(count($entity->currentProjects), 1);
        $this->assertEquals(count($entity->futureProjects), 1);
    }

    public function test_community_members_can_express_interest_in_projects()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support  is not enabled.');
        }

        $user = User::factory()->create();
        $admin_user = User::factory()->create();
        $communityMember = CommunityMember::factory()->create([
            'user_id' => $user->id,
        ]);

        $entity = Entity::factory()
            ->hasAttached($admin_user, ['role' => 'admin'])
            ->create();
        $project = Project::factory()->create([
            'entity_id' => $entity->id,
            'published_at' => Carbon::now(),
        ]);

        $response = $this->from(localized_route('projects.show', $project))->actingAs($user)->post(localized_route('community-members.express-interest', $communityMember), [
            'project_id' => $project->id,
        ]);

        $response->assertRedirect(localized_route('projects.show', $project));

        $this->assertTrue($project->interestedCommunityMembers->contains($communityMember));

        $response = $this->from(localized_route('projects.show', $project))->actingAs($user)->post(localized_route('community-members.remove-interest', $communityMember), [
            'project_id' => $project->id,
        ]);

        $response->assertRedirect(localized_route('projects.show', $project));

        $project = $project->fresh();

        $this->assertFalse($project->interestedCommunityMembers->contains($communityMember));
    }

    public function test_community_members_can_be_attached_to_projects()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support  is not enabled.');
        }

        $user = User::factory()->create();
        $shortlisted_community_member = CommunityMember::factory()->create();
        $requested_community_member = CommunityMember::factory()->create();

        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();
        $project = Project::factory()->create([
            'entity_id' => $entity->id,
            'published_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($user)->get(localized_route('projects.manage', $project));

        $response->assertOk();

        $response->assertSee('Participant shortlist');

        $response = $this->actingAs($user)->get(localized_route('projects.find-all-participants', $project));

        $response->assertOk();

        // Add two community_members to shortlist.
        $response = $this->actingAs($user)->from(localized_route('projects.find-all-participants', $project))->put(localized_route('projects.add-participant', $project), [
            'participant_id' => $shortlisted_community_member->id,
        ]);

        $response->assertSessionHasNoErrors();

        $response = $this->actingAs($user)->from(localized_route('projects.find-all-participants', $project))->put(localized_route('projects.add-participant', $project), [
            'participant_id' => $requested_community_member->id,
        ]);

        $response->assertSessionHasNoErrors();

        $project = $project->fresh();

        $this->assertEquals(2, count($project->shortlistedParticipants));

        // Request service from one community_member.
        $response = $this->actingAs($user)->from(localized_route('projects.manage', $project))->put(localized_route('projects.update-participant', $project), [
            'participant_id' => $requested_community_member->id,
            'status' => 'requested',
        ]);

        $response->assertSessionHasNoErrors();

        $project = $project->fresh();

        $this->assertEquals(1, count($project->shortlistedParticipants));
        $this->assertEquals(1, count($project->requestedParticipants));

        // Verify community_member project counts.
        $this->assertEquals(1, count($shortlisted_community_member->projects));
        $this->assertEquals(1, count($requested_community_member->projects));
    }

    public function test_confirmed_participants_can_participate_in_projects()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support  is not enabled.');
        }

        $entity = Entity::factory()->create();
        $project = Project::factory()->create([
            'entity_id' => $entity->id,
            'published_at' => Carbon::now(),
        ]);
        $communityMember = CommunityMember::factory()->create();
        $project->participants()->attach($communityMember->id, ['status' => 'confirmed']);

        $response = $this->actingAs($communityMember->user)->get(localized_route('projects.participate', $project));

        $response->assertOk();

        $response = $this->actingAs($communityMember->user)->get(localized_route('projects.index-updates', $project));

        $response->assertOk();
    }
}

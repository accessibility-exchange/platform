<?php

namespace Tests\Feature;

use App\Models\CommunityMember;
use App\Models\Impact;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\Sector;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\ImpactSeeder;
use Database\Seeders\SectorSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_with_regulated_organization_admin_role_can_create_projects()
    {
        $user = User::factory()->create();
        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->actingAs($user)->get(localized_route('projects.create', $regulatedOrganization));
        $response->assertOk();

        $response = $this->actingAs($user)->post(localized_route('projects.store-context', $regulatedOrganization), [
            'context' => 'new',
        ]);

        $response->assertSessionHas('context', 'new');

        $response = $this->actingAs($user)->post(localized_route('projects.store-focus', $regulatedOrganization), [
            'focus' => 'define',
        ]);

        $response->assertSessionHas('focus', 'define');

        $response = $this->actingAs($user)->post(localized_route('projects.store-languages', $regulatedOrganization), [
            'languages' => ['en', 'fr', 'ase', 'fcs'],
        ]);

        $response->assertSessionHas('languages', ['en', 'fr', 'ase', 'fcs']);

        $response = $this->actingAs($user)->post(localized_route('projects.store', $regulatedOrganization), [
            'regulated_organization_id' => $regulatedOrganization->id,
            'name' => ['en' => 'Test Project'],
            'start_date' => '2022-04-01',
            'goals' => ['en' => 'Here’s a brief description of what we hope to accomplish in this consultation process.'],
            'scope' => ['en' => 'The outcomes of this project will impact existing and new customers who identify as having a disability, or who are support people for someone with a disability.'],
        ]);

        $project = Project::all()->first();
        $url = localized_route('projects.edit', ['project' => $project, 'step' => 2]);

        $response->assertSessionHasNoErrors();

        $this->assertEquals($project->name, 'Test Project');

        $response->assertRedirect($url);

        $previous_project = Project::factory()->create([
            'regulated_organization_id' => $regulatedOrganization->id,
        ]);

        $response = $this->actingAs($user)->post(localized_route('projects.store-context', $regulatedOrganization), [
            'context' => 'follow-up',
            'ancestor' => $previous_project->id,
        ]);
        $response->assertSessionHas('context', 'follow-up');
        $response->assertSessionHas('ancestor', $previous_project->id);
    }

    public function test_users_without_regulated_organization_admin_role_cannot_create_projects()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $response = $this->actingAs($user)->get(localized_route('projects.create', $regulatedOrganization));
        $response->assertForbidden();

        $response = $this->actingAs($other_user)->get(localized_route('projects.create', $regulatedOrganization));
        $response->assertForbidden();
    }

    public function test_projects_can_be_published_and_unpublished()
    {
        $user = User::factory()->create();
        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();
        $project = Project::factory()->create([
            'regulated_organization_id' => $regulatedOrganization->id,
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
        $user = User::factory()->create();
        $regulatedOrganization = RegulatedOrganization::factory()->create();
        $project = Project::factory()->create([
            'regulated_organization_id' => $regulatedOrganization->id,
        ]);

        $response = $this->actingAs($user)->get(localized_route('projects.index'));
        $response->assertOk();

        $response = $this->actingAs($user)->get(localized_route('projects.show', $project));
        $response->assertOk();

        $response = $this->actingAs($user)->get(localized_route('projects.show-team', $project));
        $response->assertOk();

        $response = $this->actingAs($user)->get(localized_route('projects.show-engagements', $project));
        $response->assertOk();

        $response = $this->actingAs($user)->get(localized_route('projects.show-outcomes', $project));
        $response->assertOk();
    }

    public function test_community_members_can_express_interest_in_projects()
    {
        $user = User::factory()->create();
        $communityMember = CommunityMember::factory()->create([
            'user_id' => $user->id,
        ]);
        $regulatedOrganization = RegulatedOrganization::factory()->create();
        $project = Project::factory()->create([
            'regulated_organization_id' => $regulatedOrganization->id,
        ]);

        $response = $this->actingAs($user)->followingRedirects()->from(localized_route('projects.show', $project))->post(localized_route('community-members.express-interest', $communityMember), ['project_id' => $project->id]);
        $response->assertSee('You have expressed your interest in this project.');
        $response->assertOk();

        $response = $this->actingAs($user)->followingRedirects()->from(localized_route('projects.show', $project))->post(localized_route('community-members.remove-interest', $communityMember), ['project_id' => $project->id]);
        $response->assertSee('You have removed your expression of interest in this project.');
        $response->assertOk();
    }

    public function test_guests_cannot_view_projects()
    {
        $regulatedOrganization = RegulatedOrganization::factory()->create();
        $project = Project::factory()->create([
            'regulated_organization_id' => $regulatedOrganization->id,
        ]);

        $response = $this->get(localized_route('projects.index'));
        $response->assertRedirect(localized_route('login'));

        $response = $this->get(localized_route('projects.show', $project));
        $response->assertRedirect(localized_route('login'));
    }

    public function test_users_with_regulated_organization_admin_role_can_edit_projects()
    {
        $this->seed(ImpactSeeder::class);

        $user = User::factory()->create();
        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();
        $project = Project::factory()->create([
            'regulated_organization_id' => $regulatedOrganization->id,
        ]);

        $response = $this->actingAs($user)->get(localized_route('projects.edit', $project));
        $response->assertOk();

        $response = $this->actingAs($user)->put(localized_route('projects.update', $project), [
            'name' => ['en' => $project->name],
            'goals' => ['en' => 'Some new goals'],
            'scope' => ['en' => $project->scope],
            'impacts' => [Impact::first()->id],
            'start_date' => $project->start_date,
            'save' => __('Save'),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('projects.edit', ['project' => $project, 'step' => 1]));

        $project = $project->fresh();
        $this->assertEquals(count($project->impacts), 1);

        $response = $this->actingAs($user)->put(localized_route('projects.update', $project), [
            'name' => ['en' => $project->name],
            'goals' => ['en' => 'Some newer goals'],
            'scope' => ['en' => $project->scope],
            'start_date' => $project->start_date,
            'save_and_next' => __('Save and next'),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('projects.edit', ['project' => $project, 'step' => 2]));

        $response = $this->actingAs($user)->put(localized_route('projects.update-team', $project), [
            'team_count' => '42',
            'team_languages' => ['en'],
            'has_consultant' => false,
            'save_and_previous' => __('Save and previous'),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('projects.edit', ['project' => $project, 'step' => 1]));
    }

    public function test_users_without_regulated_organization_admin_role_cannot_edit_projects()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();
        $project = Project::factory()->create([
            'regulated_organization_id' => $regulatedOrganization->id,
        ]);

        $response = $this->actingAs($user)->get(localized_route('projects.edit', $project));
        $response->assertForbidden();

        $response = $this->actingAs($user)->put(localized_route('projects.update', $project), [
            'name' => 'My updated project name',
        ]);
        $response->assertForbidden();

        $response = $this->actingAs($other_user)->get(localized_route('projects.edit', $project));
        $response->assertForbidden();

        $response = $this->actingAs($other_user)->put(localized_route('projects.update', $project), [
            'name' => 'My updated project name',
        ]);
        $response->assertForbidden();
    }

    public function test_users_with_regulated_organization_admin_role_can_manage_projects()
    {
        $user = User::factory()->create();
        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();
        $project = Project::factory()->create([
            'regulated_organization_id' => $regulatedOrganization->id,
        ]);

        $response = $this->actingAs($user)->get(localized_route('projects.manage', $project));
        $response->assertOk();
    }

    public function test_users_without_regulated_organization_admin_role_cannot_manage_projects()
    {
        $user = User::factory()->create();
        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();
        $project = Project::factory()->create([
            'regulated_organization_id' => $regulatedOrganization->id,
        ]);

        $response = $this->actingAs($user)->get(localized_route('projects.manage', $project));
        $response->assertForbidden();
    }

    public function test_users_with_regulated_organization_admin_role_can_delete_projects()
    {
        $user = User::factory()->create();
        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();
        $project = Project::factory()->create([
            'regulated_organization_id' => $regulatedOrganization->id,
        ]);

        $response = $this->actingAs($user)->get(localized_route('projects.edit', $project));
        $response->assertOk();

        $response = $this->actingAs($user)->from(localized_route('projects.edit', $project))->delete(localized_route('projects.destroy', $project), [
            'current_password' => 'password',
        ]);

        $response->assertRedirect(localized_route('dashboard'));
    }

    public function test_users_without_regulated_organization_admin_role_cannot_delete_projects()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();
        $project = Project::factory()->create([
            'regulated_organization_id' => $regulatedOrganization->id,
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

    public function test_project_timeframes()
    {
        $regulatedOrganization = RegulatedOrganization::factory()->create();
        $past_project = Project::factory()->create([
            'regulated_organization_id' => $regulatedOrganization->id,
            'start_date' => '2020-01-01',
            'end_date' => '2020-12-31',
        ]);
        $past_project_multi_year = Project::factory()->create([
            'regulated_organization_id' => $regulatedOrganization->id,
            'start_date' => '2020-01-01',
            'end_date' => '2021-12-31',
        ]);
        $current_project = Project::factory()->create([
            'regulated_organization_id' => $regulatedOrganization->id,
            'start_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
        ]);
        $future_project = Project::factory()->create([
            'regulated_organization_id' => $regulatedOrganization->id,
            'start_date' => Carbon::now()->addMonths(1)->format('Y-m-d'),
        ]);

        $this->assertStringContainsString('January&ndash;December 2020', $past_project->timeframe());
        $this->assertStringContainsString('January 2020&ndash;December 2021', $past_project_multi_year->timeframe());
        $this->assertStringContainsString('Started', $current_project->timeframe());
        $this->assertStringContainsString('Starting', $future_project->timeframe());

        $this->assertEquals(4, count($regulatedOrganization->projects));
        $this->assertEquals(2, count($regulatedOrganization->pastProjects));
        $this->assertEquals(1, count($regulatedOrganization->currentProjects));
        $this->assertEquals(1, count($regulatedOrganization->futureProjects));
    }

    public function test_project_sectors()
    {
        $this->seed(SectorSeeder::class);
        $regulatedOrganization = RegulatedOrganization::factory()->create();
        $regulatedOrganization->sectors()->attach(Sector::pluck('id')->toArray());
        $project = Project::factory()->create([
            'regulated_organization_id' => $regulatedOrganization->id,
        ]);

        $this->assertEquals($regulatedOrganization->sectors->toArray(), $project->sectors->toArray());
    }

    public function test_consultant_origin()
    {
        $community_member = CommunityMember::factory()->create();

        $project_with_external_consultant = Project::factory()->create([
            'has_consultant' => true,
            'consultant_name' => 'Joe Consultant',
        ]);

        $project_with_platform_consultant = Project::factory()->create([
            'has_consultant' => true,
            'consultant_id' => $community_member->id,
        ]);

        $this->assertEquals('external', $project_with_external_consultant->consultant_origin());
        $this->assertEquals('platform', $project_with_platform_consultant->consultant_origin());
        $this->assertEquals($community_member->id, $project_with_platform_consultant->accessibilityConsultant->id);
    }

    public function test_team_experience()
    {
        $project = Project::factory()->create([
            'team_has_disability_or_deaf_lived_experience' => true,
        ]);

        $this->assertEquals('Our team includes people with disabilities and/or Deaf people.', $project->teamExperience());

        $project->update([
            'team_has_other_lived_experience' => true,
        ]);

        $project = $project->fresh();

        $this->assertEquals('Our team includes people with disabilities and/or Deaf people as well as people from other equity-seeking groups.', $project->teamExperience());

        $project->update([
            'team_has_disability_or_deaf_lived_experience' => false,
        ]);

        $project = $project->fresh();

        $this->assertEquals('Our team includes people from equity-seeking groups.', $project->teamExperience());

        $project->update([
            'team_has_other_lived_experience' => false,
        ]);

        $project = $project->fresh();

        $this->assertEquals('Our team does not include people with disabilities and/or Deaf people or people from other equity-seeking groups.', $project->teamExperience());
    }
}

<?php

namespace Tests\Feature;

use App\Models\CommunityMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityMemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_create_community_member_pages()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('community-members.create'));
        $response->assertOk();

        $response = $this->actingAs($user)->post(localized_route('community-members.create-save-roles'), [
            'roles' => ['participant', 'consultant'],
        ]);

        $response->assertRedirect(localized_route('community-members.create', ['step' => 2]));
        $response->assertSessionHas('roles', ['participant', 'consultant']);

        $response = $this->actingAs($user)->from(localized_route('community-members.create', ['step' => 2]))->post(localized_route('community-members.create'), [
            'user_id' => $user->id,
            'name' => $user->name,
            'bio' => 'Hi, welcome to my page.',
            'locality' => 'Halifax',
            'region' => 'NS',
            'creator' => 'self',
        ]);

        $communityMember = CommunityMember::where('name', $user->name)->get()->first();

        $response->assertSessionHasNoErrors();

        $response->assertRedirect(localized_route('community-members.show', $communityMember));

        $this->assertEquals($communityMember->user->id, $user->id);
    }

    public function test_entity_users_can_not_create_community_member_pages()
    {
        $user = User::factory()->create(['context' => 'entity']);

        $response = $this->actingAs($user)->get(localized_route('community-members.create'));
        $response->assertForbidden();

        $response = $this->from(localized_route('community-members.create'))->post(localized_route('community-members.create'), [
            'user_id' => $user->id,
            'name' => $user->name,
            'bio' => 'Hi, welcome to my page.',
            'locality' => 'Halifax',
            'region' => 'NS',
            'creator' => 'self',
        ]);

        $response->assertForbidden();
    }

    public function test_users_can_not_create_community_member_pages_for_other_users()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('community-members.create'));
        $response->assertOk();

        $response = $this->actingAs($user)->post(localized_route('community-members.create'), [
            'user_id' => $other_user->id,
            'name' => $user->name,
            'bio' => 'Hi, welcome to my page.',
            'locality' => 'Halifax',
            'region' => 'NS',
            'creator' => 'self',
        ]);

        $response->assertForbidden();
    }

    public function test_users_can_not_create_multiple_community_member_pages()
    {
        $user = User::factory()->create();
        $communityMember = CommunityMember::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(localized_route('community-members.create'));
        $response->assertForbidden();

        $response = $this->actingAs($user)->post(localized_route('community-members.create'), [
            'user_id' => $user->id,
            'name' => $user->name,
            'bio' => 'Hi, welcome to my page.',
            'locality' => 'Halifax',
            'region' => 'NS',
            'creator' => 'self',
        ]);

        $response->assertForbidden();
    }

    public function test_community_member_pages_can_be_published_and_unpublished()
    {
        $user = User::factory()->create();
        $communityMember = CommunityMember::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->from(localized_route('community-members.show', $communityMember))->put(localized_route('community-members.update-publication-status', $communityMember), [
            'publish' => true,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('community-members.show', $communityMember));
        $this->assertTrue($communityMember->checkStatus('published'));

        $response = $this->actingAs($user)->from(localized_route('community-members.show', $communityMember))->put(localized_route('community-members.update-publication-status', $communityMember), [
            'unpublish' => true,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('community-members.show', $communityMember));

        $communityMember = $communityMember->fresh();

        $this->assertTrue($communityMember->checkStatus('draft'));
    }

    public function test_users_can_edit_community_member_pages()
    {
        $user = User::factory()->create();
        $communityMember = CommunityMember::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(localized_route('community-members.edit', $communityMember));
        $response->assertOk();

        $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
            'name' => $communityMember->name,
            'bio' => $communityMember->bio,
            'locality' => 'St John\'s',
            'region' => 'NL',
            'creator' => $communityMember->creator,
        ]);

        $response->assertRedirect(localized_route('community-members.show', $communityMember));

        $draft_user = User::factory()->create();
        $draft_community_member = CommunityMember::factory()->create([
            'user_id' => $draft_user->id,
            'published_at' => null,
        ]);

        $response = $this->actingAs($draft_user)->get(localized_route('community-members.edit', $draft_community_member));
        $response->assertOk();

        $response = $this->actingAs($draft_user)->put(localized_route('community-members.update', $draft_community_member), [
            'name' => $draft_community_member->name,
            'bio' => $draft_community_member->bio,
            'locality' => 'St John\'s',
            'region' => 'NL',
            'creator' => $draft_community_member->creator,
        ]);

        $response->assertRedirect(localized_route('community-members.show', $draft_community_member));
    }

    public function test_users_can_not_edit_others_community_member_pages()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $communityMember = CommunityMember::factory()->create([
            'user_id' => $other_user->id,
        ]);

        $response = $this->actingAs($user)->get(localized_route('community-members.edit', $communityMember));
        $response->assertForbidden();

        $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
            'name' => $communityMember->name,
            'bio' => $communityMember->bio,
            'locality' => 'St John\'s',
            'region' => 'NL',
            'creator' => $communityMember->creator,
        ]);
        $response->assertForbidden();
    }

    public function test_users_can_delete_community_member_pages()
    {
        $user = User::factory()->create();
        $communityMember = CommunityMember::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->from(localized_route('community-members.edit', $communityMember))->delete(localized_route('community-members.destroy', $communityMember), [
            'current_password' => 'password',
        ]);
        $response->assertRedirect(localized_route('dashboard'));
    }

    public function test_users_can_not_delete_community_member_pages_with_wrong_password()
    {
        $user = User::factory()->create();
        $communityMember = CommunityMember::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->from(localized_route('community-members.edit', $communityMember))->delete(localized_route('community-members.destroy', $communityMember), [
            'current_password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('community-members.edit', $communityMember));
    }

    public function test_users_can_not_delete_others_community_member_pages()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $communityMember = CommunityMember::factory()->create([
            'user_id' => $other_user->id,
        ]);

        $response = $this->actingAs($user)->from(localized_route('community-members.edit', $communityMember))->delete(localized_route('community-members.destroy', $communityMember), [
            'current_password' => 'password',
        ]);
        $response->assertForbidden();
    }

    public function test_users_can_view_own_draft_community_member_pages()
    {
        $user = User::factory()->create();
        $communityMember = CommunityMember::factory()->create([
            'user_id' => $user->id,
            'published_at' => null,
        ]);

        $response = $this->actingAs($user)->get(localized_route('community-members.show', $communityMember));
        $response->assertOk();
    }

    public function test_users_can_not_view_others_draft_community_member_pages()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $communityMember = CommunityMember::factory()->create([
            'user_id' => $user->id,
            'published_at' => null,
        ]);

        $response = $this->actingAs($other_user)->get(localized_route('community-members.show', $communityMember));
        $response->assertForbidden();
    }

    public function test_users_can_view_private_sections_of_own_community_member_pages()
    {
        $user = User::factory()->create();
        $communityMember = CommunityMember::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(localized_route('community-members.show-lived-experience', $communityMember));
        $response->assertOk();
    }

    public function test_users_can_not_view_private_sections_of_others_community_member_pages()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $communityMember = CommunityMember::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($other_user)->get(localized_route('community-members.show-lived-experience', $communityMember));
        $response->assertForbidden();
    }

    public function test_guests_can_not_view_community_member_pages()
    {
        $user = User::factory()->create();
        $communityMember = CommunityMember::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(localized_route('community-members.index'));
        $response->assertRedirect(localized_route('login'));

        $response = $this->get(localized_route('community-members.show', $communityMember));
        $response->assertRedirect(localized_route('login'));
    }

    public function test_community_member_pages_can_be_published()
    {
        $user = User::factory()->create();
        $communityMember = CommunityMember::factory()->create([
            'user_id' => $user->id,
            'published_at' => null,
        ]);

        $response = $this->actingAs($user)->from(localized_route('community-members.show', $communityMember))->put(localized_route('community-members.update-publication-status', $communityMember), [
            'publish' => true,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('community-members.show', $communityMember));

        $communityMember = $communityMember->fresh();

        $this->assertTrue($communityMember->checkStatus('published'));
    }

    public function test_community_member_pages_can_be_unpublished()
    {
        $user = User::factory()->create();
        $communityMember = CommunityMember::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->from(localized_route('community-members.show', $communityMember))->put(localized_route('community-members.update-publication-status', $communityMember), [
            'unpublish' => true,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('community-members.show', $communityMember));

        $communityMember = $communityMember->fresh();

        $this->assertTrue($communityMember->checkStatus('draft'));
    }

    public function test_draft_community_members_do_not_appear_on_community_member_index()
    {
        $user = User::factory()->create();
        $communityMember = CommunityMember::factory()->create([
            'published_at' => null,
        ]);

        $response = $this->actingAs($user)->get(localized_route('community-members.index'));
        $response->assertDontSee($communityMember->name);
    }

    public function test_published_community_members_appear_on_community_member_index()
    {
        $user = User::factory()->create();
        $communityMember = CommunityMember::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('community-members.index'));
        $response->assertSee($communityMember->name);
    }
}

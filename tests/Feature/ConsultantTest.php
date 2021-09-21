<?php

namespace Tests\Feature;

use App\Models\Consultant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultantTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_create_consultant_pages()
    {
        $user = User::factory()->create();

        $response = $this->post(localized_route('login-store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('consultants.create'));
        $response->assertOk();

        $response = $this->post(localized_route('consultants.create'), [
            'user_id' => $user->id,
            'name' => $user->name,
            'bio' => 'Hi, welcome to my page.',
            'locality' => 'Truro',
            'region' => 'NS',
            'creator' => 'self',
        ]);

        $consultant = Consultant::where('name', $user->name)->get()->first();

        $response->assertSessionHasNoErrors();

        $response->assertRedirect(localized_route('consultants.show', $consultant));
    }

    public function test_entity_users_can_not_create_consultant_pages()
    {
        $user = User::factory()->create(['context' => 'entity']);

        $response = $this->post(localized_route('login-store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('consultants.create'));
        $response->assertForbidden();

        $response = $this->from(localized_route('consultants.create'))->post(localized_route('consultants.create'), [
            'user_id' => $user->id,
            'name' => $user->name,
            'bio' => 'Hi, welcome to my page.',
            'locality' => 'Truro',
            'region' => 'NS',
            'creator' => 'self',
        ]);

        $response->assertForbidden();
    }

    public function test_users_can_not_create_consultant_pages_for_other_users()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $response = $this->post(localized_route('login-store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('consultants.create'));
        $response->assertOk();

        $response = $this->post(localized_route('consultants.create'), [
            'user_id' => $other_user->id,
            'name' => $user->name,
            'bio' => 'Hi, welcome to my page.',
            'locality' => 'Truro',
            'region' => 'NS',
            'creator' => 'self',
        ]);

        $response->assertForbidden();
    }

    public function test_users_can_not_create_multiple_consultant_pages()
    {
        $user = User::factory()->create();
        $consultant = Consultant::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->post(localized_route('login-store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('consultants.create'));
        $response->assertForbidden();

        $response = $this->post(localized_route('consultants.create'), [
            'user_id' => $user->id,
            'name' => $user->name,
            'bio' => 'Hi, welcome to my page.',
            'locality' => 'Truro',
            'region' => 'NS',
            'creator' => 'self',
        ]);

        $response->assertForbidden();
    }

    public function test_users_can_edit_consultant_pages()
    {
        $user = User::factory()->create();
        $consultant = Consultant::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->post(localized_route('login-store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('consultants.edit', $consultant));
        $response->assertOk();

        $response = $this->put(localized_route('consultants.update', $consultant), [
            'name' => $consultant->name,
            'bio' => $consultant->bio,
            'locality' => 'St John\'s',
            'region' => 'NL',
            'creator' => $consultant->creator,
        ]);

        $response->assertRedirect(localized_route('consultants.show', $consultant));
    }

    public function test_users_can_not_edit_others_consultant_pages()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $consultant = Consultant::factory()->create([
            'user_id' => $other_user->id,
        ]);

        $response = $this->post(localized_route('login-store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('consultants.edit', $consultant));
        $response->assertForbidden();

        $response = $this->put(localized_route('consultants.update', $consultant), [
            'name' => $consultant->name,
            'bio' => $consultant->bio,
            'locality' => 'St John\'s',
            'region' => 'NL',
            'creator' => $consultant->creator,
        ]);
        $response->assertForbidden();
    }

    public function test_users_can_delete_consultant_pages()
    {
        $user = User::factory()->create();
        $consultant = Consultant::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->post(localized_route('login-store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('consultants.edit', $consultant))->delete(localized_route('consultants.destroy', $consultant), [
            'current_password' => 'password',
        ]);
        $response->assertRedirect(localized_route('dashboard'));
    }

    public function test_users_can_not_delete_consultant_pages_with_wrong_password()
    {
        $user = User::factory()->create();
        $consultant = Consultant::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->post(localized_route('login-store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('consultants.edit', $consultant))->delete(localized_route('consultants.destroy', $consultant), [
            'current_password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('consultants.edit', $consultant));
    }

    public function test_users_can_not_delete_others_consultant_pages()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $consultant = Consultant::factory()->create([
            'user_id' => $other_user->id,
        ]);

        $response = $this->post(localized_route('login-store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('consultants.edit', $consultant))->delete(localized_route('consultants.destroy', $consultant), [
            'current_password' => 'password',
        ]);
        $response->assertForbidden();
    }

    public function test_users_can_view_own_draft_consultant_pages()
    {
        $user = User::factory()->create();
        $consultant = Consultant::factory()->create([
            'user_id' => $user->id,
            'published_at' => null,
        ]);

        $response = $this->post(localized_route('login-store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('consultants.show', $consultant));
        $response->assertOk();
    }

    public function test_users_can_not_view_others_draft_consultant_pages()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $consultant = Consultant::factory()->create([
            'user_id' => $user->id,
            'published_at' => null,
        ]);

        $response = $this->post(localized_route('login-store'), [
            'email' => $other_user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('consultants.show', $consultant));
        $response->assertForbidden();
    }

    public function test_guests_can_not_view_consultant_pages()
    {
        $user = User::factory()->create();
        $consultant = Consultant::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(localized_route('consultants.index'));
        $response->assertRedirect(localized_route('login'));

        $response = $this->get(localized_route('consultants.show', $consultant));
        $response->assertRedirect(localized_route('login'));
    }

    public function test_consultant_pages_can_be_published()
    {
        $user = User::factory()->create();
        $consultant = Consultant::factory()->create([
            'user_id' => $user->id,
            'published_at' => null,
        ]);

        $response = $this->actingAs($user)->from(localized_route('consultants.show', $consultant))->put(localized_route('consultants.update-publication-status', $consultant), [
            'publish' => true,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('consultants.show', $consultant));

        $consultant = $consultant->fresh();

        $this->assertTrue($consultant->checkStatus('published'));
    }

    public function test_consultant_pages_can_be_unpublished()
    {
        $user = User::factory()->create();
        $consultant = Consultant::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->from(localized_route('consultants.show', $consultant))->put(localized_route('consultants.update-publication-status', $consultant), [
            'unpublish' => true,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('consultants.show', $consultant));

        $consultant = $consultant->fresh();

        $this->assertTrue($consultant->checkStatus('draft'));
    }
}

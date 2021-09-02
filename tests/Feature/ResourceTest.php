<?php

namespace Tests\Feature;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_create_resources()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('resources.create'));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->post(localized_route('resources.create'), [
            'user_id' => $user->id,
            'title' => 'Test resource',
            'language' => 'en',
            'summary' => 'This is my resource.',
        ]);

        $url = localized_route('resources.show', ['resource' => Str::slug('Test resource')]);

        $response->assertSessionHasNoErrors();

        $response->assertRedirect($url);
    }

    public function test_users_can_edit_resources_belonging_to_them()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $user = User::factory()->create();
        $resource = Resource::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(localized_route('resources.edit', $resource));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->put(localized_route('resources.update', $resource), [
            'title' => $resource->title,
            'language' => $resource->language,
            'summary' => 'This is my updated resource.',
        ]);
        $response->assertRedirect(localized_route('resources.show', $resource));
    }

    public function test_users_can_not_edit_resources_belonging_to_others()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $user = User::factory()->create();
        $resource = Resource::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('resources.edit', $resource));
        $response->assertStatus(403);

        $response = $this->actingAs($user)->put(localized_route('resources.update', $resource), [
            'title' => $resource->title,
            'language' => $resource->language,
            'summary' => 'This is my updated resource.',
        ]);
        $response->assertStatus(403);
    }

    public function test_users_can_delete_resources_belonging_to_them()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $user = User::factory()->create();
        $resource = Resource::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->from(localized_route('resources.edit', $resource))->delete(localized_route('resources.destroy', $resource), [
            'current_password' => 'password',
        ]);

        $response->assertRedirect(localized_route('dashboard'));
    }

    public function test_users_can_not_delete_resources_belonging_to_them_with_wrong_password()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $user = User::factory()->create();
        $resource = Resource::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->from(localized_route('resources.edit', $resource))->delete(localized_route('resources.destroy', $resource), [
            'current_password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('resources.edit', $resource));
    }

    public function test_users_can_not_delete_resources_belonging_to_others()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $user = User::factory()->create();
        $resource = Resource::factory()->create();

        $response = $this->actingAs($user)->from(localized_route('resources.edit', $resource))->delete(localized_route('resources.destroy', $resource), [
            'current_password' => 'password',
        ]);

        $response->assertStatus(403);
    }
}

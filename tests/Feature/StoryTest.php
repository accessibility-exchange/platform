<?php

namespace Tests\Feature;

use App\Models\Story;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class StoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_create_stories()
    {
        if (! config('hearth.stories.enabled')) {
            return $this->markTestSkipped('Story support is not enabled.');
        }

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('stories.create'));
        $response->assertOk();

        $response = $this->actingAs($user)->post(localized_route('stories.create'), [
            'user_id' => $user->id,
            'title' => 'Test story',
            'language' => 'en',
            'summary' => 'This is my story.',
        ]);

        $url = localized_route('stories.show', ['story' => Str::slug('Test story')]);

        $response->assertSessionHasNoErrors();

        $response->assertRedirect($url);
    }

    public function test_users_can_edit_stories_belonging_to_them()
    {
        if (! config('hearth.stories.enabled')) {
            return $this->markTestSkipped('Story support is not enabled.');
        }

        $user = User::factory()->create();
        $story = Story::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(localized_route('stories.edit', $story));
        $response->assertOk();

        $response = $this->actingAs($user)->put(localized_route('stories.update', $story), [
            'title' => $story->title,
            'language' => $story->language,
            'summary' => 'This is my updated story.',
        ]);
        $response->assertRedirect(localized_route('stories.show', $story));
    }

    public function test_users_can_not_edit_stories_belonging_to_others()
    {
        if (! config('hearth.stories.enabled')) {
            return $this->markTestSkipped('Story support is not enabled.');
        }

        $user = User::factory()->create();
        $story = Story::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('stories.edit', $story));
        $response->assertForbidden();

        $response = $this->actingAs($user)->put(localized_route('stories.update', $story), [
            'title' => $story->title,
            'language' => $story->language,
            'summary' => 'This is my updated story.',
        ]);
        $response->assertForbidden();
    }

    public function test_users_can_delete_stories_belonging_to_them()
    {
        if (! config('hearth.stories.enabled')) {
            return $this->markTestSkipped('Story support is not enabled.');
        }

        $user = User::factory()->create();
        $story = Story::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->from(localized_route('stories.edit', $story))->delete(localized_route('stories.destroy', $story), [
            'current_password' => 'password',
        ]);

        $response->assertRedirect(localized_route('dashboard'));
    }

    public function test_users_can_not_delete_stories_belonging_to_them_with_wrong_password()
    {
        if (! config('hearth.stories.enabled')) {
            return $this->markTestSkipped('Story support is not enabled.');
        }

        $user = User::factory()->create();
        $story = Story::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->from(localized_route('stories.edit', $story))->delete(localized_route('stories.destroy', $story), [
            'current_password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('stories.edit', $story));
    }

    public function test_users_can_not_delete_stories_belonging_to_others()
    {
        if (! config('hearth.stories.enabled')) {
            return $this->markTestSkipped('Story support is not enabled.');
        }

        $user = User::factory()->create();
        $story = Story::factory()->create();

        $response = $this->actingAs($user)->from(localized_route('stories.edit', $story))->delete(localized_route('stories.destroy', $story), [
            'current_password' => 'password',
        ]);

        $response->assertForbidden();
    }
}

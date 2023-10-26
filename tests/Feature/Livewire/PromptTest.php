<?php

use App\Livewire\Prompt;
use App\Models\User;

use function Pest\Livewire\livewire;

test('prompt rendered', function () {
    $modelPath = 'dismissed_customize_prompt_at';
    $user = User::factory()->create([$modelPath => null]);
    $heading = 'Prompt heading';
    $description = 'Prompt description';
    $actionLabel = 'Complete action';
    $actionUrl = 'http://example.com';

    $component = livewire(Prompt::class, [
        'model' => $user,
        'modelPath' => $modelPath,
        'heading' => $heading,
        'description' => $description,
        'actionLabel' => $actionLabel,
        'actionUrl' => $actionUrl,
    ]);

    $component->assertStatus(200);
    $component->assertSet('model', $user);
    $component->assertSet('modelPath', $modelPath);
    $component->assertSet('heading', $heading);
    $component->assertSet('description', $description);
    $component->assertSet('actionLabel', $actionLabel);
    $component->assertSet('actionUrl', $actionUrl);
    $component->assertSet('level', 3);
    $component->assertSeeInOrder([
        'h3',
        $heading,
        $description,
        $actionUrl,
        $actionLabel,
    ]);
});

test('prompt rendered with custom heading level', function () {
    $modelPath = 'dismissed_customize_prompt_at';
    $user = User::factory()->create([$modelPath => null]);
    $heading = 'Prompt heading';
    $description = 'Prompt description';
    $actionLabel = 'Complete action';
    $actionUrl = 'http://example.com';
    $level = 4;

    $component = livewire(Prompt::class, [
        'model' => $user,
        'modelPath' => $modelPath,
        'level' => $level,
        'heading' => $heading,
        'description' => $description,
        'actionLabel' => $actionLabel,
        'actionUrl' => $actionUrl,
    ]);

    $component->assertStatus(200);
    $component->assertSet('level', $level);
    $component->assertSee('h4');
    $component->assertDontSee('h3');
});

test('prompt calls dismiss', function () {
    $modelPath = 'dismissed_customize_prompt_at';
    $user = User::factory()->create([$modelPath => null]);
    $heading = 'Prompt heading';
    $description = 'Prompt description';
    $actionLabel = 'Complete action';
    $actionUrl = 'http://example.com';

    $component = livewire(Prompt::class, [
        'model' => $user,
        'modelPath' => $modelPath,
        'heading' => $heading,
        'description' => $description,
        'actionLabel' => $actionLabel,
        'actionUrl' => $actionUrl,
    ]);

    $component->assertStatus(200);
    $component->call('dismiss');

    $user->refresh();
    expect($user->$modelPath)->toBeString();
});

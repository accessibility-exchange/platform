<?php

use App\Livewire\ModuleContent;
use App\Models\Course;
use App\Models\Module;
use App\Models\User;
use Illuminate\Support\Facades\DB;

use function Pest\Livewire\livewire;

test('ModuleContent mounts with status about module for the user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $module = Module::factory()->for(Course::factory()->create())->create();
    $moduleContent = livewire(ModuleContent::class, ['module' => $module]);
    expect($user)->toEqual($moduleContent->user);
    expect($module)->toEqual($moduleContent->module);
    expect($moduleContent->module->startedContentAt)->toBeNull();
    expect($moduleContent->module->finishedContentAt)->toBeNull();
});

test('On player start, intermediate table values are set', function () {
    $course = Course::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($user);
    $module = Module::factory()->for($course)->create();
    $moduleContent = livewire(ModuleContent::class, ['module' => $module]);

    $this->assertDatabaseMissing('course_user', [
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]);
    $this->assertDatabaseMissing('module_user', [
        'user_id' => $user->id,
        'module_id' => $module->id,
    ]);
    expect(DB::table('module_user')->where('user_id', $user->id)->first()->started_content_at ?? null)->toBeNull();
    $moduleContent->dispatch('onPlayerStart');
    $this->assertDatabaseHas('course_user', [
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]);
    $this->assertDatabaseHas('module_user', [
        'user_id' => $user->id,
        'module_id' => $module->id,
    ]);
});

test('On player end, intermediate table values are updated', function () {
    $course = Course::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($user);
    $module = Module::factory()->for($course)->create();
    $moduleContent = livewire(ModuleContent::class, ['module' => $module]);
    $moduleContent->dispatch('onPlayerStart');

    $this->assertDatabaseCount('course_user', 1);
    $this->assertDatabaseCount('module_user', 1);
    expect(DB::table('module_user')->where([['module_id', $module->id], ['user_id', $user->id]])->first()->finished_content_at)->toBeNull();

    $moduleContent->dispatch('onPlayerEnd');

    $this->assertDatabaseCount('course_user', 1);
    $this->assertDatabaseCount('module_user', 1);
    expect(DB::table('module_user')->where([['module_id', $module->id], ['user_id', $user->id]])->first()->finished_content_at)->not->toBeNull();
});

test('Users have to complete all the modules in a course to finish a course', function () {
    $course = Course::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($user);
    $firstModule = Module::factory()->for($course)->create();
    $secondModule = Module::factory()->for($course)->create();
    $moduleContent = livewire(ModuleContent::class, ['module' => $firstModule]);
    $moduleContent->dispatch('onPlayerStart');
    $moduleContent->dispatch('onPlayerEnd');
    $moduleContent = livewire(ModuleContent::class, ['module' => $secondModule]);
    $moduleContent->dispatch('onPlayerStart');
    $moduleContent->dispatch('onPlayerEnd');

    expect($course->isFinished($user))->toBeTrue();
});

<?php

use App\Http\Livewire\ModuleContent;
use App\Models\Course;
use App\Models\Module;
use App\Models\User;
use Illuminate\Support\Facades\DB;

test('ModuleContent mounts with status about module for the user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $module = Module::factory()->for(Course::factory()->create())->create();
    $moduleContent = $this->livewire(ModuleContent::class, ['module' => $module]);
    $this->assertEquals($moduleContent->user, $user);
    $this->assertEquals($moduleContent->module, $module);
    $this->assertEquals($moduleContent->startedContentAt, null);
    $this->assertEquals($moduleContent->finishedContentAt, null);
});

test('On player start, intermediate table values are set', function () {
    $course = Course::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($user);
    $module = Module::factory()->for($course)->create();
    $moduleContent = $this->livewire(ModuleContent::class, ['module' => $module]);

    $this->assertDatabaseMissing('course_user', [
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]);
    $this->assertDatabaseMissing('module_user', [
        'user_id' => $user->id,
        'module_id' => $module->id,
    ]);
    $this->assertNull(DB::table('module_user')->where('user_id', $user->id)->first()->started_content_at ?? null);
    $moduleContent->emit('onPlayerStart');
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
    $moduleContent = $this->livewire(ModuleContent::class, ['module' => $module]);
    $moduleContent->emit('onPlayerStart');

    $this->assertDatabaseCount('course_user', 1);
    $this->assertDatabaseCount('module_user', 1);
    $this->assertNull(DB::table('module_user')->where([['module_id', $module->id], ['user_id', $user->id]])->first()->finished_content_at);
    $this->assertNull(DB::table('course_user')->where([['course_id', $course->id], ['user_id', $user->id]])->first()->finished_at);

    $moduleContent->emit('onPlayerEnd');

    $this->assertDatabaseCount('course_user', 1);
    $this->assertDatabaseCount('module_user', 1);
    $this->assertNotNull(DB::table('module_user')->where([['module_id', $module->id], ['user_id', $user->id]])->first()->finished_content_at);
    $this->assertNotNull(DB::table('course_user')->where([['course_id', $course->id], ['user_id', $user->id]])->first()->finished_at);
});

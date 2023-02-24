<?php

use App\Models\Course;
use App\Models\Module;
use App\Models\User;

test('module should belongs to a course', function () {
    $course = Course::factory()->create();
    $module = Module::factory()->for($course)->create();

    expect($module->course->id)->toBe($course->id);
});

test('a module can belongs to many users', function () {
    $module = Module::factory()->for(Course::factory()->create())->create();

    $user = User::factory()->create();
    $anotherUser = User::factory()->create();

    $user->modules()->attach($module);
    $anotherUser->modules()->attach($module);

    expect($module->users->contains($user))->toBeTrue();
    expect($module->users->contains($anotherUser))->toBeTrue();
});

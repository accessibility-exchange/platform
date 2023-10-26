<?php

use Illuminate\Console\Scheduling\Schedule;

beforeEach(function () {
    $this->events = Arr::mapWithKeys(collect(app()->make(Schedule::class)->events())->pluck('expression', 'command')->toArray(), function (string $item, string $key) {
        return [explode(" 'artisan' ", $key)[1] => $item];
    });
});

test('database seed backups are in the schedule', function () {
    expect($this->events)->toHaveKey('db:seed:backup --all');
    expect($this->events['db:seed:backup --all'])->toEqual('0 0 * * *');
});

test('notification removals are in the schedule', function () {
    expect($this->events)->toHaveKey('notifications:remove:old --days=30');
    expect($this->events['notifications:remove:old --days=30'])->toEqual('0 0 * * *');
});

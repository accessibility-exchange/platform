<?php

use Illuminate\Console\Scheduling\Schedule;

beforeEach(function () {
    $this->events = Arr::mapWithKeys(collect(app()->make(Schedule::class)->events())->pluck('expression', 'command')->toArray(), function (string $item, string $key) {
        return [explode(" 'artisan' ", $key)[1] => $item];
    });
});

test('dev refresh is in the schedule', function () {
    expect($this->events)->toHaveKey('app:refresh-dev');
    expect($this->events['app:refresh-dev'])->toEqual('0 0 * * *');
});

test('notification removals are in the schedule', function () {
    expect($this->events)->toHaveKey('notifications:remove:old --days=30');
    expect($this->events['notifications:remove:old --days=30'])->toEqual('0 0 * * *');
});

test('seo file generation is in the schedule', function () {
    expect($this->events)->toHaveKey('seo:generate');
    expect($this->events['seo:generate'])->toEqual('0 0 * * *');
});

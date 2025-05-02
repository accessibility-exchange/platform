<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:refresh-dev') // use custom command to make sure that the commands are chained
    ->daily() // Run daily at midnight
    ->environments(['dev']) // only run for APP_ENV tagged dev
    ->timezone('America/Los_Angeles') // Run as PST timezone
    ->onOneServer(); // run only on a single server at once

Schedule::command('notifications:remove:old --days=30') // remove notifications older than 30 days old and read
    ->daily() // Run daily at midnight
    ->timezone('America/Los_Angeles') // Run as PST timezone
    ->onOneServer(); // run only on a single server at once

Schedule::command('seo:generate') // generate sitemap
    ->daily() // Run daily at midnight
    ->timezone('America/Los_Angeles'); // Run as PST timezone

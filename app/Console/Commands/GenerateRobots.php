<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateRobots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-robots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        file_put_contents('./public/robots.txt', view('robots')->render());

        return 0;
    }
}

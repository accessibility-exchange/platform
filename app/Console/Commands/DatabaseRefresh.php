<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DatabaseRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:refresh
                            {--backup : Whether to backup first}?';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshes and seeds the database for development work.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $options = $this->options();

        // Don't run command in production or testing environment
        if (in_array(config('app.env'), ['testing', 'production']) !== true) {

            // whether to backup seeds first
            if ($options['backup']) {
                $this->call('db:seed:backup', ['--all' => true]);
            }

            $this->call('migrate:fresh', ['--force' => true]);
            $this->call('db:seed', ['--class' => 'DevSeeder', '--force' => true]);

            print("Truncating filament tables:\r\n");

            $truncate_tables = array("identities", "interpretations", "resource_collections", "resources", "topics");
            foreach($truncate_tables as $table) {
                printf("Truncating values in table %s.\r\n", $table);
                DB::table($table)->delete();
            }

            print("\r\n");
            print("Running filament seeders:\r\n");

            $seeder_classes = array("IdentitySeeder", "InterpretationSeeder", "ResourceCollectionSeeder", "ResourceSeeder", "TopicSeeder");
            foreach($seeder_classes as $seeder_class) {
                printf("Running seeder %s.", $seeder_class);
                $this->call('db:seed', ['--class' => $seeder_class]);
            }
        }

        return 0;
    }
}

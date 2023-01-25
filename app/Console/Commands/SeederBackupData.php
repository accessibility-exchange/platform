<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SeederBackupData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:seed:backup ${table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create backup of table for seeder.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $table = $this->argument('table');
        $available_tables = array("identities", "interpretations", "resource_collections", "resources", "topics");

        if (in_array($table, $available_tables)) {
            Storage::disk('seeds')->put($table . ".json", DB::table($table)->get()->toJson());

            return 0;
        } else {

            printf("Table '%s' is not in the list of available tables to backup.\r\n", $table);
            return 1;
        }

    }
}

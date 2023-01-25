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
    protected $signature = 'db:seed:backup
                            {--all : Whether to backup all available tables}?
                            {--table= : Backup specific table}?*';

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
        $options = $this->options();

        $available_tables = array("identities", "interpretations", "resource_collections", "resources", "topics");

        if ($options['all']) {
            foreach($available_tables as $table) {
                Storage::disk('seeds')->put($table . ".json", DB::table($table)->get()->toJson());
            }

            return 0;
        } elseif (isset($options['table']) && in_array($options['table'], $available_tables)) {
            Storage::disk('seeds')->put($options['table'] . ".json", DB::table($options['table'])->get()->toJson());

            return 0;
        } else {

            printf("Must use option --all or --table=. See --help for more details.\r\n");
            return 1;
        }

    }
}

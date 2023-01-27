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
                            {--a|all : Whether to run through all available backups/restores in config}?
                            {--remove : Remove backed up files}?
                            {--restore : Restore the filament table}?
                            {--t|table=* : Create/remove specific table file}?';

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

        $available_tables = config('backup.filament_seeders.tables');

        // removes backuped up files
        if ($options['remove']) {
            // if the table option is set only remove that tables file
            // else remove all found in the config
            if (isset($options['table'])) {
                foreach($options['table'] as $table) {
                    if (in_array($table, $available_tables)) {
                        Storage::disk('seeds')->delete($table . ".json");
                    } else {
                        printf("You have might have misspelled the tablename.\r\nTable %s not found.\r\nAvailable tables: %s\r\n", $table, implode(', ', $available_tables));
                        return 1;
                    }
                }
                return 0;
            } else {
                foreach($available_tables as $table) {
                    Storage::disk('seeds')->delete($table . ".json");
                }
            }
            return 0;

        // restores backups
        } else if ($options['restore']) {
            if (in_array(config('app.env'), ['testing', 'production']) === true) {
                printf("This command cannot run on environment %s\r\n", config('app.env'));
                return 1;
            } else {
                $seeder_classes = config('backup.filament_seeders.classes');

                // run through all the seeder classes
                // else provide a choice of which to restore
                if ($options['all']) {
                    foreach($seeder_classes as $seeder_class) {
                        printf("Running seeder %s\r\n", $seeder_class);
                        $this->call('db:seed', ['--class' => $seeder_class]);
                    }
                } else {
                    $seeder_classes = $this->choice(
                        'Which class would you like to restore?',
                        $seeder_classes,
                        $defaultIndex = 1,
                        $maxAttempts = null,
                        $allowMultipleSelections = true
                    );
                    foreach($seeder_classes as $seeder_class) {
                        printf("Running seeder %s\r\n", $seeder_class);
                        $this->call('db:seed', ['--class' => $seeder_class]);
                    }
                }
                return 0;
            }

        // default --all is to backup all tables
        } else if ($options['all']) {
            foreach($available_tables as $table) {
                printf("Backing up table seeder %s\r\n", $table);
                Storage::disk('seeds')->put($table . ".json", DB::table($table)->get()->toJson());
            }
            return 0;

        // if table is set the default will be to backup the table
        } elseif (isset($options['table'])) {
            foreach($options['table'] as $table) {
                if (in_array($table, $available_tables)) {
                    printf("Backing up table seeder %s\r\n", $table);
                    Storage::disk('seeds')->put($table . ".json", DB::table($table)->get()->toJson());
                } else {
                    printf("You have might have misspelled the tablename.\r\nTable %s not found.\r\nAvailable tables: %s\r\n", $table, implode(', ', $available_tables));
                    return 1;
                }
            }
            return 0;
        } else {
            printf("Must use at least one option.\r\nTry php artisan help db:seed:backup\r\n");
            return 1;
        }

    }
}

<?php

namespace App\Console\Commands;

use App\Models\Organization;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;

class MigrateSettingsData extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-settings-data
                            {--list : lists out available migrations}
                            {--from=1.6.0 : when running all migrations, indicate which version the application is being migrated from. Previous migrations will be skipped.}
                            {--migration= : a specific migration to run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates settings data that is not performed by database migrations; such as modifying the contents of database fields.';

    protected $migrations = [
        'EnableEngagementNotifications' => [
            'version' => '1.7.0',
            'handler' => 'enableEngagementNotificationsMigration',
            'description' => 'Replaces older format of notifications_settings with only ["engagements" => "1"]. Setting the engagement notifications on be default. If the notifications_settings contains a valid engagements setting, then no changes are made.',
        ],
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $verbose = $this->output->isVerbose();

        if ($this->options()['list']) {

            $this->listMigrations($this->migrations);

            return 0;
        }

        if ($this->options()['migration']) {
            try {
                $migration = $this->migrations[$this->options()['migration']];
            } catch (Exception $e) {
                $this->fail('Could not find migration: '.$this->options()['migration']);
            }

            if (isset($migration)) {
                $handler = $migration['handler'];
                $this->$handler();

                return 0;
            }
        }

        $this->runMigrations($this->migrations, $this->options()['from'], $verbose);
    }

    public function listMigrations($migrations)
    {
        $definitions = '';

        foreach ($migrations as $name => $migration) {
            $version = $migration['version'];
            $description = $migration['description'];
            $definitions .= "<dt>$name</dt><dd>Version added: $version</dd><dd class=\"pb-1\">$description</dd>";

            $this->line("<options=bold>$name</> (Version added: $version)");
            $this->info("$description");
            $this->newLine();
        }
    }

    public function runMigrations($migrations, $from = '1.6.0', $verbose = false)
    {
        $from = str_starts_with($from, 'v') || str_starts_with($from, 'V') ? substr($from, 1) : $from;
        $migrationRunCount = 0;

        foreach ($migrations as $name => $migration) {
            if (version_compare($from, $migration['version'], '<')) {
                if ($verbose) {
                    $this->line("<fg=cyan>Run migration - $name</>");
                }
                $handler = $migration['handler'];
                $this->$handler($verbose);
                $migrationRunCount++;
            } elseif ($verbose) {
                $this->comment("Skipped migration - $name");
            }

            if ($verbose) {
                $this->newLine();
            }
        }

        $this->line('<options=bold;fg=green>Completed '.$migrationRunCount.'</>');
        $this->line('<options=bold;fg=yellow>Skipped   '.(count($migrations) - $migrationRunCount).'</>');
    }

    // Migrations

    public function enableEngagementNotificationsMigration($verbose = false)
    {
        $updatedNotificationSettings = ['engagements' => '1'];

        if ($verbose) {
            $this->info('  - Migrating engagement notifications for users');
        }

        $users = User::where('context', 'individual')->whereNull('notification_settings->engagements')
            ->get();

        $users->each(function ($user) use ($updatedNotificationSettings) {
            // @phpstan-ignore assign.propertyType
            $user->notification_settings = $updatedNotificationSettings;
            $user->save();
        });

        if ($verbose) {
            $this->info('    - Migrated '.$users->count().' users');
            $this->info('  - Migrating engagement notification settings for Organizations');
        }

        $orgs = Organization::whereNull('notification_settings->engagements')
            ->get();

        $orgs->each(function ($organization) use ($updatedNotificationSettings) {
            // @phpstan-ignore assign.propertyType
            $organization->notification_settings = $updatedNotificationSettings;
            $organization->save();
        });

        if ($verbose) {
            $this->info('    - Migrated '.$orgs->count().' Organizations');
        }
    }
}

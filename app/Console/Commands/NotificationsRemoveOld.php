<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class NotificationsRemoveOld extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:remove:old
                            {--days= : How many days before today to delete notifications}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes user notifications over n days old.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');

        if (! is_numeric($days) || $days <= 1) {
            if (is_null($days)) {
                $this->error(__('Must specify a number of days greater than 1 using the "--days" flag. Example --days=30'));
            } else {
                $this->error(__('Must specify a number of days greater than 1 using the "--days" flag. Example --days=30. The specified "--days=:days" is invalid.', ['days' => $days]));
            }

            return 1;
        }

        DB::table('notifications')->where('read_at', '<', DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' day)'))->delete();
    }
}

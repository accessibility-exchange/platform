<?php

namespace Database\Seeders;

use App\Models\Interpretation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InterpretationSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        // option to use environment to restore or backup to different environment files
        if (config('seeder.environment') !== null && in_array(config('seeder.environment'), ['production', 'staging', 'local']) === true) {
            $environment = config('seeder.environment');
        } else {
            $environment = config('app.env');
        }

        // check if there is a JSON file that has stored data for the seeder
        if (in_array(config('app.env'), ['testing', 'production']) !== true) {

<<<<<<< HEAD
            if (Storage::disk('seeds')->exists(sprintf("interpretations.%s.json", $environment))) {

                $interpretations = json_decode(Storage::disk('seeds')->get(sprintf("interpretations.%s.json", $environment)), true);

                foreach ($interpretations as $interpretation) {
                    Interpretation::firstOrCreate([
                        'name' => $interpretation['name'],
                        'namespace' => $interpretation['namespace'],
                        'route' => $interpretation['route'],
                        'route_has_params' => $interpretation['route_has_params'],
                        'video' => json_decode($interpretation['video'], true),
                    ]);
                }
            } else {
                printf("Seeder file not found interpretations.json.%s\r\n", $environment);
            }
=======
            // if trucate was set via seeder restore command then truncate the table prior to seeding data
            if (config('seeder.truncate')) {
                DB::statement("SET foreign_key_checks=0");
                Interpretation::truncate();
                DB::statement("SET foreign_key_checks=1");
            }

            if (Storage::disk('seeds')->exists(sprintf("interpretations.%s.json", $environment))) {

                $interpretations = json_decode(Storage::disk('seeds')->get(sprintf("interpretations.%s.json", $environment)), true);

                foreach ($interpretations as $interpretation) {
                    Interpretation::firstOrCreate([
                        'name' => $interpretation['name'],
                        'namespace' => $interpretation['namespace'],
                        'route' => $interpretation['route'],
                        'route_has_params' => $interpretation['route_has_params'],
                        'video' => json_decode($interpretation['video'], true),
                    ]);
                }
            } else {
                printf("Seeder file not found interpretations.json.%s\r\n", $environment);
            }
>>>>>>> 1483-create-seedersreplication-for-data-addededited-via-the-admin-interfaces
        } else {
            $environment = config('app.env');
            printf("Seeder cannot be run on environment: %s\r\n", $environment);
        }
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ([
            'age_bracket_organization',
            'area_type_organization',
            'constituency_individual',
            'constituency_organization',
            'disability_type_organization',
            'ethnoracial_identity_organization',
            'gender_identity_organization',
            'indigenous_identity_organization',
            'individual_lived_experience',
            'lived_experience_organization',
            'age_brackets',
            'area_types',
            'constituencies',
            'criteria',
            'disability_types',
            'ethnoracial_identities',
            'gender_identities',
            'indigenous_identities',
            'lived_experiences',
        ] as $table) {
            Schema::drop($table);
        }
    }
};

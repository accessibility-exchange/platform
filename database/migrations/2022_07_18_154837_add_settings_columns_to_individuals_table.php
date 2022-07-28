<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('individuals', function (Blueprint $table) {
            $table->json('other_access_need')->nullable();
            $table->string('signed_language_for_interpretation')->nullable();
            $table->string('spoken_language_for_interpretation')->nullable();
            $table->string('signed_language_for_translation')->nullable();
            $table->string('written_language_for_translation')->nullable();
            $table->string('street_address')->nullable();
            $table->string('unit_apartment_suite')->nullable();
            $table->string('postal_code')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('individuals', function (Blueprint $table) {
            $table->dropColumn([
                'other_access_need',
                'signed_language_for_interpretation',
                'spoken_language_for_interpretation',
                'signed_language_for_translation',
                'written_language_for_translation',
                'street_address',
                'unit_apartment_suite',
                'postal_code',
            ]);
        });
    }
};

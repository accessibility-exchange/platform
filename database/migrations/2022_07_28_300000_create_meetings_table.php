<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('engagement_id')
                ->constrained()
                ->onDelete('cascade');
            $table->json('title');
            $table->dateTimeTz('start_datetime');
            $table->dateTimeTz('end_datetime');
            $table->json('ways_to_attend');
            $table->string('street_address')->nullable();
            $table->string('unit_apartment_suite')->nullable();
            $table->string('locality')->nullable();
            $table->string('region')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('web_conference_tool')->nullable();
            $table->boolean('alternate_web_conference_tool')->nullable();
            $table->string('web_conference_url')->nullable();
            $table->json('web_conference_information')->nullable();
            $table->json('phone')->nullable();
            $table->json('phone_information');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};

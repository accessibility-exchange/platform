<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('engagements', function (Blueprint $table) {
            $table->dropColumn('payment');
            $table->time('window_start_time')->after('window_end_date')->nullable();
            $table->time('window_end_time')->after('window_start_time')->nullable();
            $table->boolean('window_flexibility')->after('timezone')->nullable();
            $table->json('meeting_types')->after('weekday_availabilities')->nullable();
            $table->string('street_address')->after('meeting_types')->nullable();
            $table->string('unit_suite_floor')->after('street_address')->nullable();
            $table->string('locality')->after('unit_suite_floor')->nullable();
            $table->string('region')->after('locality')->nullable();
            $table->string('postal_code')->after('region')->nullable();
            $table->json('directions')->after('postal_code')->nullable();
            $table->string('meeting_software')->after('directions')->nullable();
            $table->boolean('alternative_meeting_software')->after('meeting_software')->nullable();
            $table->string('meeting_url')->after('alternative_meeting_software')->nullable();
            $table->json('additional_video_information')->after('meeting_url')->nullable();
            $table->string('meeting_phone')->after('additional_video_information')->nullable();
            $table->json('additional_phone_information')->after('meeting_phone')->nullable();
            $table->json('other_accepted_format')->after('accepted_formats')->nullable();
            $table->boolean('open_to_other_formats')->after('other_accepted_format')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('engagements', function (Blueprint $table) {
            $table->json('payment')->nullable();
            $table->dropColumn([
                'window_start_time',
                'window_end_time',
                'window_flexibility',
                'meeting_types',
                'street_address',
                'unit_suite_floor',
                'locality',
                'region',
                'postal_code',
                'directions',
                'meeting_software',
                'alternative_meeting_software',
                'meeting_url',
                'additional_video_information',
                'meeting_phone',
                'additional_phone_information',
                'other_accepted_format',
                'open_to_other_formats',
            ]);
        });
    }
};

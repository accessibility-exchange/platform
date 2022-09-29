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
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropColumn(['published_at', 'start_datetime', 'end_datetime', 'phone', 'phone_information']);
            $table->date('date')->after('title');
            $table->time('start_time')->after('date');
            $table->time('end_time')->after('start_time');
            $table->string('timezone')->after('end_time')->nullable();
            $table->json('directions')->after('postal_code')->nullable();
            $table->renameColumn('ways_to_attend', 'meeting_types');
            $table->renameColumn('unit_apartment_suite', 'unit_suite_floor');
            $table->renameColumn('web_conference_tool', 'meeting_software');
            $table->renameColumn('alternate_web_conference_tool', 'alternative_meeting_software');
            $table->renameColumn('web_conference_url', 'meeting_url');
            $table->renameColumn('web_conference_information', 'additional_video_information');
            $table->string('meeting_phone')->nullable();
            $table->json('additional_phone_information')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropColumn(['date', 'start_time', 'end_time', 'timezone', 'directions', 'meeting_phone', 'additional_phone_information']);
            $table->dateTime('published_at')->after('updated_at')->nullable();
            $table->dateTimeTz('start_datetime')->after('title');
            $table->dateTimeTz('end_datetime')->after('start_datetime');
            $table->renameColumn('meeting_types', 'ways_to_attend');
            $table->renameColumn('unit_suite_floor', 'unit_apartment_suite');
            $table->renameColumn('meeting_software', 'web_conference_tool');
            $table->renameColumn('alternative_meeting_software', 'alternate_web_conference_tool');
            $table->renameColumn('meeting_url', 'web_conference_url');
            $table->renameColumn('additional_video_information', 'web_conference_information');
            $table->json('phone')->nullable();
            $table->json('phone_information');
        });
    }
};

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
        Schema::table('regulated_organizations', function (Blueprint $table) {
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->boolean('contact_person_vrs')->nullable();
            $table->string('preferred_contact_method')->default('email');
            $table->string('preferred_notification_method')->default('email');
            $table->schemalessAttributes('notification_settings')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('regulated_organizations', function (Blueprint $table) {
            $table->dropColumn([
                'contact_person_name',
                'contact_person_email',
                'contact_person_phone',
                'contact_person_vrs',
                'preferred_contact_method',
                'preferred_notification_method',
                'notification_settings',
            ]);
        });
    }
};

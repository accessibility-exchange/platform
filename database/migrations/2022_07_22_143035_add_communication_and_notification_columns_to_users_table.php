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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->boolean('vrs')->nullable();
            $table->string('support_person_name')->nullable();
            $table->string('support_person_email')->nullable();
            $table->string('support_person_phone')->nullable();
            $table->boolean('support_person_vrs')->nullable();
            $table->string('preferred_contact_person')->default('me');
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'vrs',
                'support_person_name',
                'support_person_email',
                'support_person_phone',
                'support_person_vrs',
                'preferred_contact_person',
                'preferred_contact_method',
                'preferred_notification_method',
                'notification_settings',
            ]);
        });
    }
};

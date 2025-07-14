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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('oriented_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('locale')->default('en');
            $table->string('signed_language')->nullable();
            $table->string('context')->default('individual');
            $table->boolean('finished_introduction')->nullable();
            $table->string('theme')->default('light');
            $table->boolean('text_to_speech')->nullable();
            $table->string('sign_language_translations')->nullable();
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
            $table->schemalessAttributes('extra_attributes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};

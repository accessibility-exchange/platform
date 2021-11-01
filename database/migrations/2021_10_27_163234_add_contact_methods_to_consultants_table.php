<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactMethodsToConsultantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consultants', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->string('support_person_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('support_person_email')->nullable();
            $table->enum('preferred_contact_method', [
                'phone',
                'support_person_phone',
                'email',
                'support_person_email',
            ])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consultants', function (Blueprint $table) {
            $table->dropColumn(['phone', 'support_person_phone', 'email', 'support_person_email', 'preferred_contact_method']);
        });
    }
}

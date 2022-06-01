<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('individuals', function (Blueprint $table) {
            $table->dropColumn('support_people');
            $table->string('support_person_name')->nullable();
            $table->string('support_person_email')->nullable();
            $table->string('support_person_phone')->nullable();
            $table->boolean('support_person_vrs')->nullable();
            $table->renameColumn('links', 'social_links');
            $table->renameColumn('other_links', 'web_links');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('individuals', function (Blueprint $table) {
            $table->json('support_people')->nullable();
            $table->dropColumn(['support_person_name', 'support_person_email', 'support_person_phone', 'support_person_vrs']);
            $table->renameColumn('social_links', 'links');
            $table->renameColumn('web_links', 'other_links');
        });
    }
};

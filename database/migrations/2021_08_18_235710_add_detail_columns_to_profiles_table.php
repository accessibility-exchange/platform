<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailColumnsToProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->text('bio');
            $table->date('birth_date')->nullable();
            $table->string('pronouns')->nullable();
            $table->enum('creator', ['self', 'other'])->default('self');
            $table->string('creator_name')->nullable();
            $table->string('creator_relationship')->nullable();
            $table->enum('visibility', ['project', 'all'])->default('all');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'bio',
                'birth_year',
                'pronouns',
                'creator',
                'creator_name',
                'creator_relationship',
                'visibility',
            ]);
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailColumnsToConsultantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consultants', function (Blueprint $table) {
            $table->dateTime('published_at')->nullable();
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
        Schema::table('consultants', function (Blueprint $table) {
            $table->dropColumn([
                'published_at',
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

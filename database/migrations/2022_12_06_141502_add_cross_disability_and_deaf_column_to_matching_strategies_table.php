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
        Schema::table('matching_strategies', function (Blueprint $table) {
            $table->boolean('cross_disability_and_deaf')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matching_strategies', function (Blueprint $table) {
            $table->$table->dropColumn('cross_disability_and_deaf');
        });
    }
};

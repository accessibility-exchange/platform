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
        Schema::drop('phaseables');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('phaseables', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('phase_id')
                ->constrained()
                ->onDelete('cascade');
            $table->morphs('phaseable');
        });
    }
};

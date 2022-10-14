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
        Schema::dropIfExists('projects_of_interest');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('projects_of_interest', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('individual_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('project_id')
                ->constrained()
                ->onDelete('cascade');
        });
    }
};

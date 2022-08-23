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
        Schema::create('engagements', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('project_id')
                ->constrained()
                ->onDelete('cascade');
            $table->json('name');
            $table->json('description')->nullable();
            $table->string('format');
            $table->integer('ideal_participants');
            $table->integer('minimum_participants');
            $table->string('who')->nullable();
            $table->string('recruitment')->nullable();
            $table->json('regions')->nullable();
            $table->json('localities')->nullable();
            $table->boolean('paid')->default(1);
            $table->json('payment')->nullable();
            $table->date('signup_by_date')->nullable();
            $table->date('materials_by_date')->nullable();
            $table->date('complete_by_date')->nullable();
            $table->date('window_start_date')->nullable();
            $table->date('window_end_date')->nullable();
            $table->string('timezone')->nullable();
            $table->json('weekday_availabilities')->nullable();
            $table->json('document_languages')->nullable();
            $table->json('accepted_formats')->nullable();
            $table->bigInteger('individual_connector_id')
                ->references('id')
                ->on('individuals')
                ->nullable();
            $table->bigInteger('organizational_connector_id')
                ->references('id')
                ->on('organizations')
                ->nullable();
            $table->bigInteger('individual_consultant_id')
                ->references('id')
                ->on('individuals')
                ->nullable();
            $table->bigInteger('organizational_consultant_id')
                ->references('id')
                ->on('organizations')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('engagements');
    }
};

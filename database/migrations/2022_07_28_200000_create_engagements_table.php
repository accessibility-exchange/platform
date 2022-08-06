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
            $table->json('name');
            $table->foreignId('project_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('recruitment')->default('automatic');
            $table->json('goals');
            $table->json('timeline')->nullable();
            $table->json('meetings')->nullable();
            $table->json('reporting')->nullable();
            $table->json('other_reporting')->nullable();
            $table->json('contacts')->nullable();
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

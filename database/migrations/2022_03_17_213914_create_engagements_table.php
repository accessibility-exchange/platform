<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('engagements', function (Blueprint $table) {
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
            $table->id();
            $table->timestamps();
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
}

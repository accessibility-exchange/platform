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
        Schema::create('resource_collections', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->json('title');
            $table->json('slug');
            $table->json('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resource_collections');
    }
};

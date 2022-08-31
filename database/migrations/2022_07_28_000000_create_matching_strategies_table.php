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
        Schema::create('matching_strategies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->nullableMorphs('matchable');
            $table->json('regions')->nullable();
            $table->json('locations')->nullable();
            $table->schemalessAttributes('extra_attributes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matching_strategies');
    }
};

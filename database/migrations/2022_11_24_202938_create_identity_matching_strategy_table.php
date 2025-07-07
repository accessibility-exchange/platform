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
        Schema::create('identity_matching_strategy', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('identity_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('matching_strategy_id')
                ->constrained()
                ->onDelete('cascade');
            $table->float('weight')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('identity_matching_strategy');
    }
};

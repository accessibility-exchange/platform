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
        Schema::drop('consulting_method_individual');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('consulting_method_individual', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('consulting_method_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('individual_id')
                ->constrained()
                ->onDelete('cascade');
        });
    }
};

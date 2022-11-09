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
        Schema::drop('formattables');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('formattables', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('format_id')
                ->constrained()
                ->onDelete('cascade');
            $table->morphs('formattable');
            $table->string('language');
            $table->boolean('original')->nullable();
        });
    }
};

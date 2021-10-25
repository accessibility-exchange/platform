<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormattablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formattables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('format_id')
                ->constrained()
                ->onDelete('cascade');
            $table->morphs('formattable');
            $table->boolean('original')->nullable();
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
        Schema::dropIfExists('formatables');
    }
}

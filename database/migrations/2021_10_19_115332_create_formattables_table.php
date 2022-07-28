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
            $table->timestamps();
            $table->foreignId('format_id')
                ->constrained()
                ->onDelete('cascade');
            $table->morphs('formattable');
            $table->string('language');
            $table->boolean('original')->nullable();
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

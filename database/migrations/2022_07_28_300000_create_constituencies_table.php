<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('constituencies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->json('name');
            $table->json('name_plural');
            $table->json('adjective');
            $table->json('description');
        });
    }

    public function down()
    {
        Schema::dropIfExists('constituencies');
    }
};

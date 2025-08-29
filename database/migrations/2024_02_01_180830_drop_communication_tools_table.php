<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('communication_tools');
    }

    public function down(): void
    {
        Schema::create('communication_tools', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->json('name');
        });
    }
};

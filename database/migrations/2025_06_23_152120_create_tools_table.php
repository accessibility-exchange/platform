<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->json('slug');
            $table->json('title');
            $table->json('description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};

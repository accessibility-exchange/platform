<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_resource', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('library_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('resource_id')
                ->constrained()
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_resource');
    }
};

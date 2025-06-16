<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revisions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('document_id');
            $table->date('date')->unique();
            $table->json('file');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revisions');
    }
};

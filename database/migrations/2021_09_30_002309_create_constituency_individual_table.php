<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('constituency_individual', function (Blueprint $table) {
            $table->foreignId('constituency_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('individual_id')
                ->constrained()
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('constituency_individual');
    }
};

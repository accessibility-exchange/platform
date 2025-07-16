<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('engagement_payment_type', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('engagement_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('payment_type_id')
                ->constrained()
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('engagement_payment_type');
    }
};

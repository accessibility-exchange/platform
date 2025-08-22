<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('external_user_invitation', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('invitation_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('user_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_user_invitation');
    }
};

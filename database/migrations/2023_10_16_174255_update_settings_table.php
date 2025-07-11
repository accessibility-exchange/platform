<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('locked')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('interpretations', function (Blueprint $table) {
            $table->integer('active')->default(0)->change();
        });
    }
};

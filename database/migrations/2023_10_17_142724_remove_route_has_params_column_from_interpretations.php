<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interpretations', function (Blueprint $table) {
            $table->dropColumn('route_has_params');
        });
    }

    public function down(): void
    {
        Schema::table('interpretations', function (Blueprint $table) {
            $table->boolean('route_has_params')->nullable();
        });
    }
};

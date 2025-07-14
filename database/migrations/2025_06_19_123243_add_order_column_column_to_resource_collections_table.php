<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resource_collections', function (Blueprint $table) {
            $table->integer('order');
        });
    }

    public function down(): void
    {
        Schema::table('resource_collections', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};

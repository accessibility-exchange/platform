<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('regulated_organizations', function (Blueprint $table) {
            $table->schemalessAttributes('prompts');
        });
    }

    public function down(): void
    {
        Schema::table('regulated_organizations', function (Blueprint $table) {
            $table->dropColumn('prompts');
        });
    }
};

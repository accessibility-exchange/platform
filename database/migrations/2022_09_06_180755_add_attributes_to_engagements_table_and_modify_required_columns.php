<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('engagements', function (Blueprint $table) {
            $table->schemalessAttributes('extra_attributes');
            $table->string('format')->nullable()->change();
            $table->string('who')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('engagements', function (Blueprint $table) {
            $table->dropColumn('extra_attributes');
            $table->string('format')->nullable(false)->change();
            $table->string('who')->nullable()->change();
        });
    }
};

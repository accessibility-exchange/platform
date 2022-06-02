<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('type');
            $table->string('locality')->nullable()->change();
            $table->string('region')->nullable()->change();
            $table->json('languages')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {

            $table->string('locality')->nullable(false)->change();
            $table->string('region')->nullable(false)->change();
            $table->dropColumn(['type', 'languages']);
        });
    }
};

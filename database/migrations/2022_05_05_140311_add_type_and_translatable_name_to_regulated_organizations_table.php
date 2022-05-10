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
        Schema::table('regulated_organizations', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->json('name')->change();
            $table->string('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('regulated_organizations', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('slug');
            $table->dropColumn('type');
        });
    }
};

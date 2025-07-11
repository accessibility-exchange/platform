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
        Schema::table('regulated_organizations', function (Blueprint $table) {
            $table->timestamp('suspended_at')->after('oriented_at')->nullable();
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->timestamp('suspended_at')->after('oriented_at')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('suspended_at')->after('oriented_at')->nullable();
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
            $table->dropColumn('suspended_at');
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('suspended_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('suspended_at');
        });
    }
};

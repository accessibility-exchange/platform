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
        Schema::table('community_members', function (Blueprint $table) {
            $table->renameColumn('links', 'social_links');
            $table->renameColumn('other_links', 'web_links');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('community_members', function (Blueprint $table) {
            $table->renameColumn('social_links', 'links');
            $table->renameColumn('web_links', 'other_links');
        });
    }
};

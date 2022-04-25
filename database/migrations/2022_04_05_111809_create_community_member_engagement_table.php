<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunityMemberEngagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_member_engagement', function (Blueprint $table) {
            $table->enum('status', ['invited', 'confirmed', 'declined', 'removed', 'exited'])->default('invited');
            $table->foreignId('community_member_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('engagement_id')
                ->constrained()
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('community_member_engagement');
    }
}

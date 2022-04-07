<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunityMemberRegulatedOrgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_member_regulated_org', function (Blueprint $table) {
            $table->foreignId('regulated_organization_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('community_member_id')
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
        Schema::dropIfExists('community_member_regulated_org');
    }
}

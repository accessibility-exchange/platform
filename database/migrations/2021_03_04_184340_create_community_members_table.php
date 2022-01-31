<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunityMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('locality');
            $table->string('region');
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->dateTime('published_at')->nullable();
            $table->json('bio');
            $table->json('links')->nullable();
            $table->date('birth_date')->nullable();
            $table->json('pronouns')->nullable();
            $table->json('picture_alt')->nullable();
            $table->enum('creator', ['self', 'other'])->default('self');
            $table->string('creator_name')->nullable();
            $table->json('creator_relationship')->nullable();
            $table->json('areas_of_interest')->nullable();
            $table->string('phone')->nullable();
            $table->string('support_person_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('support_person_email')->nullable();
            $table->enum('preferred_contact_method', [
                'phone',
                'support_person_phone',
                'email',
                'support_person_email',
            ])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('community_members');
    }
}

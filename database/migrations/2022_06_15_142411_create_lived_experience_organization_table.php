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
        Schema::create('lived_experience_organization', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lived_experience_id');
            $table->foreign('lived_experience_id', 'lived_experience_foreign')
                ->references('id')
                ->on('lived_experiences')
                ->onDelete('cascade');
            $table->foreignId('organization_id')
                ->constrained()
                ->onDelete('cascade');
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
        Schema::dropIfExists('lived_experience_organization');
    }
};

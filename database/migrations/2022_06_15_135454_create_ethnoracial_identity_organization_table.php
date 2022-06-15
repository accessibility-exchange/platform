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
        Schema::create('ethnoracial_identity_organization', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ethnoracial_identity_id');
            $table->foreign('ethnoracial_identity_id', 'ethnoracial_identity_foreign')
                ->references('id')
                ->on('ethnoracial_identities')
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
        Schema::dropIfExists('ethnoracial_identity_organization');
    }
};

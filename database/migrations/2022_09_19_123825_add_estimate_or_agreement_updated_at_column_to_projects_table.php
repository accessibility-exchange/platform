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
        Schema::table('projects', function (Blueprint $table) {
            $table->dateTime('estimate_or_agreement_updated_at')
                ->storedAs('
                    CASE
                        WHEN agreement_received_at IS NOT NULL THEN agreement_received_at
                        WHEN estimate_approved_at IS NOT NULL THEN estimate_approved_at
                        WHEN estimate_returned_at IS NOT NULL THEN estimate_returned_at
                        WHEN estimate_requested_at IS NOT NULL THEN estimate_requested_at
                        ELSE NULL
                    END
                ')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('estimate_or_agreement_updated_at');
        });
    }
};

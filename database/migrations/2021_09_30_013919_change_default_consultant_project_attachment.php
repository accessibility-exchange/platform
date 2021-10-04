<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeDefaultConsultantProjectAttachment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE consultant_project MODIFY COLUMN status ENUM('shortlisted', 'requested', 'confirmed', 'removed', 'exited') NOT NULL DEFAULT 'shortlisted'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE consultant_project MODIFY COLUMN status ENUM('saved', 'shortlisted', 'requested', 'confirmed', 'removed', 'exited') NOT NULL DEFAULT 'saved'");
    }
}
